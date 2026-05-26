<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AOW_Tiendas_Sync {

    private $odoo;
    private $log = [];

    public function __construct() {
        $settings = get_option( 'aow_settings', [] );

        $this->odoo = new AOW_Odoo_Client(
            $settings['url']      ?? '',
            $settings['db']       ?? '',
            $settings['username'] ?? '',
            $settings['api_key']  ?? ''
        );
    }

    // -------------------------------------------------------------------------
    // IMPORTACIÓN COMPLETA (botón manual)
    // -------------------------------------------------------------------------

    public function run_full_import() {
        $this->log = [];

        try {
            $this->odoo->authenticate();
        } catch ( Exception $e ) {
            $this->guardar_log( 'Error de autenticación: ' . $e->getMessage(), 'importacion' );
            return;
        }

        try {
            $tiendas_odoo = $this->odoo->get_tiendas();
        } catch ( Exception $e ) {
            $this->guardar_log( 'Error al obtener tiendas de Odoo: ' . $e->getMessage(), 'importacion' );
            return;
        }

        $ids_procesados = [];
        $un_año_atras   = new DateTime( '-1 year' );

        foreach ( $tiendas_odoo as $partner ) {
            $fecha_str = $this->odoo->get_last_order_date( $partner['id'] );

            if ( ! $fecha_str ) {
                $this->log[] = [
                    'nombre'  => $partner['name'],
                    'accion'  => 'omitida',
                    'detalle' => 'Sin pedidos confirmados en Odoo.',
                ];
                continue;
            }

            $fecha = new DateTime( $fecha_str );

            if ( $fecha < $un_año_atras ) {
                $this->log[] = [
                    'nombre'  => $partner['name'],
                    'accion'  => 'omitida',
                    'detalle' => 'Último pedido hace más de 1 año: ' . $fecha->format( 'd/m/Y' ),
                ];
                continue;
            }

            $this->crear_o_actualizar_tienda( $partner, $fecha );
            $ids_procesados[] = (string) $partner['id'];

            $this->log[] = [
                'nombre'  => $partner['name'],
                'accion'  => 'importada',
                'detalle' => 'Último pedido: ' . $fecha->format( 'd/m/Y' ),
            ];
        }

        $this->eliminar_todas_menos_importadas( $ids_procesados );

        $total      = count( $tiendas_odoo );
        $importadas = count( $ids_procesados );
        $this->guardar_log( "Importación completada. {$importadas} tiendas importadas de {$total} en Odoo.", 'importacion' );
    }

    private function crear_o_actualizar_tienda( $partner, DateTime $fecha_ultimo_pedido ) {
        $post_id    = $this->buscar_tienda_por_odoo_id( $partner['id'] );
        $datos_post = [
            'post_title'  => sanitize_text_field( $partner['name'] ),
            'post_type'   => 'tienda',
            'post_status' => 'publish',
        ];

        if ( $post_id ) {
            $datos_post['ID'] = $post_id;
            wp_update_post( $datos_post );
        } else {
            $post_id = wp_insert_post( $datos_post );
        }

        update_post_meta( $post_id, 'tienda_id',            (string) $partner['id'] );
        update_post_meta( $post_id, 'tienda_direccion',     sanitize_text_field( $partner['street'] ?? '' ) );
        update_post_meta( $post_id, 'tienda_ciudad',        sanitize_text_field( $partner['city'] ?? '' ) );
        update_post_meta( $post_id, 'tienda_codigo_postal', sanitize_text_field( $partner['zip'] ?? '' ) );
        update_post_meta( $post_id, 'tienda_telefono',      sanitize_text_field( $partner['phone'] ?? $partner['mobile'] ?? '' ) );
        update_post_meta( $post_id, 'fecha_ultimo_pedido',  $fecha_ultimo_pedido->format( 'Ymd' ) );

        $this->asignar_zona( $post_id, $partner['state_id'] );

        return $post_id;
    }

    private function asignar_zona( $post_id, $state_id ) {
        if ( empty( $state_id ) || ! is_array( $state_id ) ) {
            return;
        }

        $nombre = preg_replace( '/\s*\([^)]+\)$/', '', $state_id[1] );
        $term   = get_term_by( 'name', trim( $nombre ), 'zona' );

        if ( $term ) {
            wp_set_post_terms( $post_id, [ $term->term_id ], 'zona' );
        }
    }

    private function eliminar_todas_menos_importadas( $ids_procesados ) {
        $tiendas_wp = get_posts( [
            'post_type'      => 'tienda',
            'post_status'    => [ 'publish', 'draft', 'private' ],
            'posts_per_page' => -1,
        ] );

        foreach ( $tiendas_wp as $tienda ) {
            $odoo_id = get_post_meta( $tienda->ID, 'tienda_id', true );

            if ( ! in_array( (string) $odoo_id, $ids_procesados, true ) ) {
                wp_delete_post( $tienda->ID, true );
                $this->log[] = [
                    'nombre'  => $tienda->post_title,
                    'accion'  => 'eliminada',
                    'detalle' => 'Eliminada en reset de importación.',
                ];
            }
        }
    }

    // -------------------------------------------------------------------------
    // REVISIÓN DIARIA (cron)
    // -------------------------------------------------------------------------

    public function run() {
        $this->log = [];

        try {
            $this->odoo->authenticate();
        } catch ( Exception $e ) {
            $this->guardar_log( 'Error de autenticación: ' . $e->getMessage(), 'cron' );
            return;
        }

        $tiendas = get_posts( [
            'post_type'      => 'tienda',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [ [
                'key'     => 'tienda_id',
                'value'   => '',
                'compare' => '!=',
            ] ],
        ] );

        if ( empty( $tiendas ) ) {
            $this->guardar_log( 'No hay tiendas con ID de Odoo para revisar.', 'cron' );
            return;
        }

        foreach ( $tiendas as $tienda ) {
            $this->revisar_tienda( $tienda );
        }

        $this->guardar_log( 'Revisión diaria completada. ' . count( $tiendas ) . ' tiendas revisadas.', 'cron' );
    }

    private function revisar_tienda( $tienda ) {
        $partner_id = get_post_meta( $tienda->ID, 'tienda_id', true );

        try {
            $fecha_str = $this->odoo->get_last_order_date( $partner_id );
        } catch ( Exception $e ) {
            $this->log[] = [
                'nombre'  => $tienda->post_title,
                'accion'  => 'error',
                'detalle' => $e->getMessage(),
            ];
            return;
        }

        if ( ! $fecha_str ) {
            $this->log[] = [
                'nombre'  => $tienda->post_title,
                'accion'  => 'sin_pedidos',
                'detalle' => 'No tiene pedidos confirmados en Odoo.',
            ];
            return;
        }

        $fecha        = new DateTime( $fecha_str );
        $un_año_atras = new DateTime( '-1 year' );

        if ( $fecha < $un_año_atras ) {
            wp_delete_post( $tienda->ID, true );
            $this->log[] = [
                'nombre'  => $tienda->post_title,
                'accion'  => 'eliminada',
                'detalle' => 'Último pedido: ' . $fecha->format( 'd/m/Y' ) . ' (más de 1 año)',
            ];
        } else {
            update_post_meta( $tienda->ID, 'fecha_ultimo_pedido', $fecha->format( 'Ymd' ) );
            $this->log[] = [
                'nombre'  => $tienda->post_title,
                'accion'  => 'actualizada',
                'detalle' => 'Último pedido: ' . $fecha->format( 'd/m/Y' ),
            ];
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function buscar_tienda_por_odoo_id( $odoo_id ) {
        $posts = get_posts( [
            'post_type'      => 'tienda',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => 1,
            'meta_query'     => [ [
                'key'   => 'tienda_id',
                'value' => (string) $odoo_id,
            ] ],
        ] );

        return ! empty( $posts ) ? $posts[0]->ID : null;
    }

    private function guardar_log( $mensaje, $tipo = 'cron' ) {
        update_option( 'aow_ultimo_sync', [
            'fecha'   => current_time( 'mysql' ),
            'tipo'    => $tipo,
            'mensaje' => $mensaje,
            'detalle' => $this->log,
        ] );
    }
}

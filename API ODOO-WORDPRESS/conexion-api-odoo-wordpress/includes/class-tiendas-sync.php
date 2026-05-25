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

    public function run() {
        $this->log = [];

        try {
            $this->odoo->authenticate();
        } catch ( Exception $e ) {
            $this->guardar_log( 'Error de autenticación: ' . $e->getMessage() );
            return;
        }

        $tiendas = $this->get_tiendas();

        if ( empty( $tiendas ) ) {
            $this->guardar_log( 'No hay tiendas con ID de Odoo para sincronizar.' );
            return;
        }

        foreach ( $tiendas as $tienda ) {
            $this->procesar_tienda( $tienda );
        }

        $this->guardar_log( 'Sincronización completada. ' . count( $tiendas ) . ' tiendas procesadas.' );
    }

    private function get_tiendas() {
        return get_posts( [
            'post_type'      => 'tiendas',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [ [
                'key'     => 'id_odoo_tienda',
                'value'   => '',
                'compare' => '!=',
            ] ],
        ] );
    }

    private function procesar_tienda( $tienda ) {
        $partner_id = get_post_meta( $tienda->ID, 'id_odoo_tienda', true );

        try {
            $fecha_ultimo_pedido = $this->odoo->get_last_order_date( $partner_id );
        } catch ( Exception $e ) {
            $this->log[] = [
                'id'     => $tienda->ID,
                'nombre' => $tienda->post_title,
                'accion' => 'error',
                'detalle' => $e->getMessage(),
            ];
            return;
        }

        if ( ! $fecha_ultimo_pedido ) {
            $this->log[] = [
                'id'     => $tienda->ID,
                'nombre' => $tienda->post_title,
                'accion' => 'sin_pedidos',
                'detalle' => 'No tiene pedidos confirmados en Odoo.',
            ];
            return;
        }

        $fecha    = new DateTime( $fecha_ultimo_pedido );
        $hace_un_año = new DateTime( '-1 year' );

        if ( $fecha < $hace_un_año ) {
            wp_trash_post( $tienda->ID );
            $this->log[] = [
                'id'      => $tienda->ID,
                'nombre'  => $tienda->post_title,
                'accion'  => 'papelera',
                'detalle' => 'Último pedido: ' . $fecha->format( 'd/m/Y' ) . ' (más de 1 año)',
            ];
        } else {
            update_post_meta( $tienda->ID, 'fecha_ultimo_pedido', $fecha->format( 'Ymd' ) );
            $this->log[] = [
                'id'      => $tienda->ID,
                'nombre'  => $tienda->post_title,
                'accion'  => 'actualizada',
                'detalle' => 'Último pedido: ' . $fecha->format( 'd/m/Y' ),
            ];
        }
    }

    private function guardar_log( $mensaje = '' ) {
        update_option( 'aow_ultimo_sync', [
            'fecha'   => current_time( 'mysql' ),
            'mensaje' => $mensaje,
            'detalle' => $this->log,
        ] );
    }
}

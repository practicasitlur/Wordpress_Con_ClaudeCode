<?php
/*
Plugin Name: Integración API Odoo - WordPress
Description: Plugin a medida para recibir tiendas desde Odoo mediante REST API.
Version: 1.0
Author: Desarrollo Interno
*/

// 1. Registrar la URL personalizada en la API de WordPress
add_action('rest_api_init', function () {
    register_rest_route('odoo/v1', '/tiendas', array(
        'methods'             => 'POST',
        'callback'            => 'recibir_tienda_desde_odoo',
        'permission_callback' => 'validar_token_odoo', // Seguridad
    ));
});


// Endpoint de diagnostico: GET /wp-json/odoo/v1/diagnostico
add_action('rest_api_init', function () {
    register_rest_route('odoo/v1', '/diagnostico', array(
        'methods'             => 'GET',
        'callback'            => 'diagnostico_odoo',
        'permission_callback' => 'validar_token_odoo',
    ));
});

function diagnostico_odoo(WP_REST_Request $request) {
    global $wpdb;

    // Post types distintos que existen en la tabla wp_posts
    $tipos_en_bd = $wpdb->get_col(
        "SELECT DISTINCT post_type FROM {$wpdb->posts}
         WHERE post_status NOT IN ('auto-draft','revision')
         ORDER BY post_type"
    );

    // Muestra de posts de tipos que parecen tiendas
    $muestras = array();
    foreach ( $tipos_en_bd as $tipo ) {
        if ( preg_match('/tiend|store|shop|local|punto/i', $tipo) ) {
            $posts = $wpdb->get_results( $wpdb->prepare(
                "SELECT ID, post_title, post_status FROM {$wpdb->posts}
                 WHERE post_type = %s AND post_status = 'publish' LIMIT 3",
                $tipo
            ) );
            $muestras[ $tipo ] = $posts;
        }
    }

    // Meta keys de un post de cada tipo encontrado
    $meta_keys = array();
    foreach ( array_keys($muestras) as $tipo ) {
        $id = $wpdb->get_var( $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish' LIMIT 1",
            $tipo
        ) );
        if ( $id ) {
            $meta_keys[ $tipo ] = $wpdb->get_col( $wpdb->prepare(
                "SELECT DISTINCT meta_key FROM {$wpdb->postmeta}
                 WHERE post_id = %d AND meta_key NOT LIKE '\_%%'",
                $id
            ) );
        }
    }

    return new WP_REST_Response( array(
        'post_types_en_bd'       => $tipos_en_bd,
        'tipos_similares_tienda' => $muestras,
        'meta_keys_por_tipo'     => $meta_keys,
    ), 200 );
}

// 2. Función de Seguridad: Validar que realmente es tu Odoo quien llama
function validar_token_odoo(WP_REST_Request $request) {
    $token_recibido = $request->get_header('X-Odoo-Token');
    $token_secreto  = 'MI_SUPER_TOKEN_SECRETO_12345'; // Cambia esto por algo seguro
    return $token_recibido === $token_secreto;
}

// 3. La lógica: Procesar los datos de Odoo y meterlos en WordPress + ACF
function recibir_tienda_desde_odoo(WP_REST_Request $request) {
    $parametros = $request->get_json_params();

    // Extraer datos del JSON de Odoo
    $odoo_id   = sanitize_text_field($parametros['odoo_id']);
    $nombre    = sanitize_text_field($parametros['nombre']);
    $direccion = sanitize_text_field($parametros['direccion']);
    $telefono  = sanitize_text_field($parametros['telefono']);

    // Comprobar si esta tienda ya existe en WP para actualizarla, o crear una nueva
    $args = array(
        'post_type'  => 'tienda',
        'meta_key'   => 'tienda_id',
        'meta_value' => $odoo_id,
        'posts_per_page' => 1
    );
    $tiendas_existentes = get_posts($args);

    $datos_post = array(
        'post_title'   => $nombre,
        'post_status'  => 'publish',
        'post_type'    => 'tienda',
    );

    if (!empty($tiendas_existentes)) {
        // Si ya existe, actualizamos el post existente
        $post_id = $tiendas_existentes[0]->ID;
        $datos_post['ID'] = $post_id;
        wp_update_post($datos_post);
    } else {
        // Si no existe, creamos uno nuevo
        $post_id = wp_insert_post($datos_post);
    }

    if (is_wp_error($post_id)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $post_id->get_error_message(),
        ), 500);
    }

    // Comprobar que el post realmente existe en la BD tras la inserción
    $post_guardado = get_post($post_id);
    if ( ! $post_guardado ) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'wp_insert_post devolvió ID ' . $post_id . ' pero el post no existe en la BD',
        ), 500);
    }

    // Asignar el idioma por defecto de WPML para que el post sea visible en el admin
    $idioma_defecto = apply_filters('wpml_default_language', null);
    if ( $idioma_defecto ) {
        do_action('wpml_set_element_language_details', array(
            'element_id'           => $post_id,
            'element_type'         => 'post_tienda',
            'trid'                 => false,
            'language_code'        => $idioma_defecto,
            'source_language_code' => null,
        ));
    }

    // Aquí usamos ACF PRO para guardar los datos estructurados en sus cajones
    update_field('tienda_id', $odoo_id, $post_id);
    update_field('tienda_direccion', $direccion, $post_id);
    // telefono no tiene campo ACF en tienda — guardar como meta directa
update_post_meta($post_id, 'tienda_telefono', $telefono);

    return new WP_REST_Response(array(
        'status'          => 'success',
        'wp_post_id'      => $post_id,
        'post_status'     => $post_guardado->post_status,
        'post_title'      => $post_guardado->post_title,
        'post_type'       => $post_guardado->post_type,
        'wpml_lang'       => $idioma_defecto,
    ), 200);
}
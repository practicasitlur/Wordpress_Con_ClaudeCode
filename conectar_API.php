<?php
// 1. Registrar la URL personalizada en la API de WordPress
add_action('rest_api_init', function () {
    register_rest_route('odoo/v1', '/tiendas', array(
        'methods'             => 'POST',
        'callback'            => 'recibir_tienda_desde_odoo',
        'permission_callback' => 'validar_token_odoo', // Seguridad
    ));
});

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
        'post_type'  => 'tiendas', // Asegúrate de tener este Custom Post Type creado
        'meta_key'   => 'id_odoo_tienda',
        'meta_value' => $odoo_id,
        'posts_per_page' => 1
    );
    $tiendas_existentes = get_posts($args);

    $datos_post = array(
        'post_title'   => $nombre,
        'post_status'  => 'publish',
        'post_type'    => 'tiendas',
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
        return new WP_REST_Response(array('status' => 'error', 'message' => 'No se pudo guardar'), 500);
    }

    // Aquí usamos ACF PRO para guardar los datos estructurados en sus cajones
    update_field('id_odoo_tienda', $odoo_id, $post_id);
    update_field('direccion_tienda', $direccion, $post_id); // Nombre del campo en ACF
    update_field('telefono_tienda', $telefono, $post_id);   // Nombre del campo en ACF

    return new WP_REST_Response(array('status' => 'success', 'wp_post_id' => $post_id), 200);
}
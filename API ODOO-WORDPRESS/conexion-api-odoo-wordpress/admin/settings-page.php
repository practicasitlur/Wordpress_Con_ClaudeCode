<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function aow_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos.' );
    }

    // Guardar configuración
    if ( isset( $_POST['aow_guardar'] ) ) {
        check_admin_referer( 'aow_settings_nonce' );
        update_option( 'aow_settings', [
            'url'      => sanitize_text_field( $_POST['aow_url'] ?? '' ),
            'db'       => sanitize_text_field( $_POST['aow_db'] ?? '' ),
            'username' => sanitize_text_field( $_POST['aow_username'] ?? '' ),
            'api_key'  => sanitize_text_field( $_POST['aow_api_key'] ?? '' ),
        ] );
        echo '<div class="notice notice-success"><p>Configuración guardada.</p></div>';
    }

    // Ejecutar sync manual
    if ( isset( $_POST['aow_sync_manual'] ) ) {
        check_admin_referer( 'aow_settings_nonce' );
        $sync = new AOW_Tiendas_Sync();
        $sync->run();
        echo '<div class="notice notice-success"><p>Sincronización ejecutada.</p></div>';
    }

    $settings  = get_option( 'aow_settings', [] );
    $ultimo    = get_option( 'aow_ultimo_sync', null );
    $colores   = [
        'actualizada' => '#d4edda',
        'papelera'    => '#f8d7da',
        'sin_pedidos' => '#fff3cd',
        'error'       => '#f8d7da',
    ];
    ?>
    <div class="wrap">
        <h1>Sincronización Odoo → WordPress</h1>
        <p>Este plugin consulta la API de Odoo diariamente y actualiza o elimina tiendas según su último pedido.</p>

        <form method="POST">
            <?php wp_nonce_field( 'aow_settings_nonce' ); ?>
            <h2>Credenciales Odoo</h2>
            <table class="form-table">
                <tr>
                    <th><label for="aow_url">URL de Odoo</label></th>
                    <td><input type="url" id="aow_url" name="aow_url" class="regular-text"
                        value="<?php echo esc_attr( $settings['url'] ?? '' ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="aow_db">Base de datos</label></th>
                    <td><input type="text" id="aow_db" name="aow_db" class="regular-text"
                        value="<?php echo esc_attr( $settings['db'] ?? '' ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="aow_username">Usuario</label></th>
                    <td><input type="text" id="aow_username" name="aow_username" class="regular-text"
                        value="<?php echo esc_attr( $settings['username'] ?? '' ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="aow_api_key">API Key</label></th>
                    <td><input type="password" id="aow_api_key" name="aow_api_key" class="regular-text"
                        value="<?php echo esc_attr( $settings['api_key'] ?? '' ); ?>"></td>
                </tr>
            </table>
            <p class="submit">
                <button type="submit" name="aow_guardar" class="button button-primary">Guardar configuración</button>
                <button type="submit" name="aow_sync_manual" class="button"
                    onclick="return confirm('¿Ejecutar sincronización ahora?')">
                    Ejecutar sincronización ahora
                </button>
            </p>
        </form>

        <hr>
        <h2>Último resultado</h2>
        <?php if ( $ultimo ) : ?>
            <p><strong>Fecha:</strong> <?php echo esc_html( $ultimo['fecha'] ); ?></p>
            <p><strong>Resumen:</strong> <?php echo esc_html( $ultimo['mensaje'] ); ?></p>

            <?php if ( ! empty( $ultimo['detalle'] ) ) : ?>
                <table class="widefat" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tienda</th>
                            <th>Acción</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $ultimo['detalle'] as $fila ) : ?>
                            <tr style="background:<?php echo esc_attr( $colores[ $fila['accion'] ] ?? '#fff' ); ?>">
                                <td><?php echo esc_html( $fila['id'] ); ?></td>
                                <td><?php echo esc_html( $fila['nombre'] ); ?></td>
                                <td><?php echo esc_html( $fila['accion'] ); ?></td>
                                <td><?php echo esc_html( $fila['detalle'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php else : ?>
            <p>Aún no se ha ejecutado ninguna sincronización.</p>
        <?php endif; ?>
    </div>
    <?php
}

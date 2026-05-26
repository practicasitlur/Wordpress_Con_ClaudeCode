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

    // Importación completa desde Odoo
    if ( isset( $_POST['aow_importar'] ) ) {
        check_admin_referer( 'aow_settings_nonce' );
        $sync = new AOW_Tiendas_Sync();
        $sync->run_full_import();
        echo '<div class="notice notice-success"><p>Importación completada.</p></div>';
    }

    // Revisión diaria manual
    if ( isset( $_POST['aow_revision'] ) ) {
        check_admin_referer( 'aow_settings_nonce' );
        $sync = new AOW_Tiendas_Sync();
        $sync->run();
        echo '<div class="notice notice-success"><p>Revisión diaria ejecutada.</p></div>';
    }

    $settings = get_option( 'aow_settings', [] );
    $ultimo   = get_option( 'aow_ultimo_sync', null );
    $colores  = [
        'importada'   => '#d4edda',
        'actualizada' => '#d4edda',
        'omitida'     => '#fff3cd',
        'papelera'    => '#fff3cd',
        'eliminada'   => '#f8d7da',
        'sin_pedidos' => '#fff3cd',
        'error'       => '#f8d7da',
    ];
    ?>
    <div class="wrap">
        <h1>Sincronización Odoo → WordPress</h1>
        <p>Importa tiendas desde Odoo y mantiene actualizadas las fechas de último pedido.</p>

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
                <button type="submit" name="aow_guardar" class="button button-primary">
                    Guardar configuración
                </button>
            </p>

            <hr>
            <h2>Acciones</h2>
            <p>
                <button type="submit" name="aow_importar" class="button button-primary"
                    onclick="return confirm('Esto importará todas las tiendas de Odoo y mandará a papelera las que ya no existan. ¿Continuar?')">
                    Importar tiendas desde Odoo
                </button>
                &nbsp;
                <button type="submit" name="aow_revision" class="button"
                    onclick="return confirm('¿Ejecutar revisión diaria ahora?')">
                    Ejecutar revisión diaria
                </button>
            </p>
            <p style="color:#666; font-size:13px;">
                <strong>Importar tiendas desde Odoo:</strong> obtiene todas las tiendas con categoría "TIENDA" en Odoo,
                crea o actualiza las que tienen pedidos de menos de 1 año, y manda a papelera las que ya no existen.<br>
                <strong>Revisión diaria:</strong> revisa las tiendas existentes en WordPress y elimina definitivamente las que superan 1 año sin pedidos.
            </p>
        </form>

        <hr>
        <h2>Último resultado</h2>
        <?php if ( $ultimo ) : ?>
            <p>
                <strong>Fecha:</strong> <?php echo esc_html( $ultimo['fecha'] ); ?>
                &nbsp;|&nbsp;
                <strong>Tipo:</strong> <?php echo esc_html( $ultimo['tipo'] ?? '-' ); ?>
            </p>
            <p><strong>Resumen:</strong> <?php echo esc_html( $ultimo['mensaje'] ); ?></p>

            <?php if ( ! empty( $ultimo['detalle'] ) ) : ?>
                <table class="widefat" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th>Tienda</th>
                            <th>Acción</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $ultimo['detalle'] as $fila ) : ?>
                            <tr style="background:<?php echo esc_attr( $colores[ $fila['accion'] ] ?? '#fff' ); ?>">
                                <td><?php echo esc_html( $fila['nombre'] ); ?></td>
                                <td><?php echo esc_html( $fila['accion'] ); ?></td>
                                <td><?php echo esc_html( $fila['detalle'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php else : ?>
            <p>Aún no se ha ejecutado ninguna acción.</p>
        <?php endif; ?>
    </div>
    <?php
}

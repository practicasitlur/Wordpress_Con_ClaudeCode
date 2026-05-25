<?php
/**
 * Plugin Name: API ODOO-WORDPRESS
 * Description: WordPress consulta la API de Odoo para sincronizar el estado de las tiendas.
 * Version: 0.1.0
 * Author: Locopolo
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'AOW_DIR', plugin_dir_path( __FILE__ ) );

require_once AOW_DIR . 'includes/class-odoo-client.php';
require_once AOW_DIR . 'includes/class-tiendas-sync.php';
require_once AOW_DIR . 'admin/settings-page.php';

// --- Cron ---

register_activation_hook( __FILE__, 'aow_activar' );
function aow_activar() {
    if ( ! wp_next_scheduled( 'aow_sync_tiendas' ) ) {
        wp_schedule_event( time(), 'daily', 'aow_sync_tiendas' );
    }
}

register_deactivation_hook( __FILE__, 'aow_desactivar' );
function aow_desactivar() {
    wp_clear_scheduled_hook( 'aow_sync_tiendas' );
}

add_action( 'aow_sync_tiendas', 'aow_ejecutar_sync' );
function aow_ejecutar_sync() {
    $sync = new AOW_Tiendas_Sync();
    $sync->run();
}

// --- Admin ---

add_action( 'admin_menu', 'aow_admin_menu' );
function aow_admin_menu() {
    add_menu_page(
        'Sincronización Odoo',
        'Sync Odoo',
        'manage_options',
        'aow-sync',
        'aow_settings_page',
        'dashicons-update',
        30
    );
}

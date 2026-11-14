<?php
/**
 * Plugin Name: Contact Manager Lite
 * Plugin URI:  https://example.com/contact-manager-lite
 * Description: A lightweight contact form plugin with custom database storage and admin dashboard.
 * Version:     1.0.0
 * Author:      Zin Elaabidine Rahmani
 * License:     GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CML_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CML_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CML_PLUGIN_PATH . 'includes/class-db.php';
require_once CML_PLUGIN_PATH . 'includes/class-form-handler.php';
require_once CML_PLUGIN_PATH . 'includes/class-admin-page.php';

function cml_activate_plugin() {
    CML_DB::create_table();
}
register_activation_hook( __FILE__, 'cml_activate_plugin' );

function cml_init_plugin() {
    wp_register_style( 'cml-frontend-style', CML_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.0' );
    wp_register_script( 'cml-frontend-script', CML_PLUGIN_URL . 'assets/js/script.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'init', 'cml_init_plugin' );

new CML_Form_Handler();
if ( is_admin() ) {
    new CML_Admin_Page();
}
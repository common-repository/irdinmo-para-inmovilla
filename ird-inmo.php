<?php
/*
Plugin Name: Irdinmo para Inmovilla
Plugin URI: https://inforrada.es/irdinmo/
Description: Plugin de soluciones inmobiliarias de Inforrada
Version: 1.4.3
Author: Inforrada
Author URI: https://inforrada.es
License: GPL2
*/
defined('ABSPATH') or die("Bye bye");
define('IRDINMO_BASE',plugin_dir_path(__FILE__));
define('IRDINMO_BASE_URL',plugin_dir_url(__FILE__));

include IRDINMO_BASE . 'includes/ird_shortcodes.php';
include IRDINMO_BASE . 'includes/ird_comun.php';

add_action( 'admin_menu', 'irdinmo_create_admin_menu');

function irdinmo_create_admin_menu() {

 add_menu_page ( 'IRD Inmo', 'Ird Inmo', 'manage_options', IRDINMO_BASE . 'admin/ird_inmo_bienvenida.php', null, 'dashicons-admin-home' );

 add_submenu_page ( IRDINMO_BASE . 'admin/ird_inmo_bienvenida.php', 'General', 'General', 'manage_options', IRDINMO_BASE . 'admin/ird_inmo_opt_general.php' );
 add_submenu_page ( IRDINMO_BASE . 'admin/ird_inmo_bienvenida.php', 'Inmovilla', 'API Inmovilla', 'manage_options', IRDINMO_BASE . 'admin/ird_inmo_opt_inmovilla.php');
}

?>
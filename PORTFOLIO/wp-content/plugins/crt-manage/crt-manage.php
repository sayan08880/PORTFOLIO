<?php
/**
 * Plugin Name: CRT Addons Elementor
 * Description: CRT Addons for Elementor advanced widgets, templates, blocks. Builder header, footer, archive, every page.
 * Version: 1.6.2
 * Author: CRThemes.com
 * Author URI: https://crthemes.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
! defined( 'CRT_MANAGE_PLUGIN_FILE' ) && define( 'CRT_MANAGE_PLUGIN_FILE', __FILE__ );
! defined( 'CRT_MANAGE_URI' ) && define( 'CRT_MANAGE_URI', plugin_dir_url( __FILE__ ) );
! defined( 'CRT_MANAGE_DIR' ) && define( 'CRT_MANAGE_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'CRT_MANAGE_URL_DEMO' ) && define( 'CRT_MANAGE_URL_DEMO', wp_get_theme()->get( 'ThemeURI' ) );
! defined( 'CRT_MANAGE_THEME_NAME' ) && define( 'CRT_MANAGE_THEME_NAME', wp_get_theme()->get( 'Name' ) );
! defined( 'CRT_MANAGE_PRE' ) && define( 'CRT_MANAGE_PRE', 'crt-manage-addons' );
$plugin_data = get_file_data( CRT_MANAGE_PLUGIN_FILE, array( 'Version' => 'Version' ), false );
$plugin_version = $plugin_data['Version'];
$crt_manage_is_woo = false;
if(class_exists( 'WooCommerce' )) {
    $crt_manage_is_woo = true;
}
! defined( 'CRT_MANAGE_VERSION' ) && define( 'CRT_MANAGE_VERSION', $plugin_version );
! defined( 'CRT_MANAGE_IS_WOO' ) && define( 'CRT_MANAGE_IS_WOO', $crt_manage_is_woo );

if (function_exists('is_plugin_active')) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
require_once 'includes/class-crt-manage-base.php';
add_action('plugins_loaded', array('CRT_Manage_Base', 'instance'));

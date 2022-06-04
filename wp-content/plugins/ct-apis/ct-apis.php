<?php 
/**
 * Plugin Name:       CT Apis
 * Description:       CT Apis
 * Version:           1.0.0
 * Author:            Anthony Lê
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'HC_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HC_API_PLUGIN', 'hc_api' );

require_once HC_API_PLUGIN_DIR . '/routes/init-route.php';
require_once HC_API_PLUGIN_DIR . '/functions/init-function.php';

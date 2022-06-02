<?php
/**
 * Plugin Name:       WooCommerce hot deal
 * Description:       WooCommerce hot deal
 * Version:           1.0.0
 * Author:            Anthony Le
 * Text Domain:       woocommerce-hot-deal
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SALE_DATE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SALE_DATE_URL', plugin_dir_url( __FILE__ ) );
define ('BLOCK_TITLE', 'block_title');
define ('BLOCK_CATSLUG', 'block_catslug');
define ('BLOCK_TOTAL', 'block_total');
define ('BLOCK_IMAGE', 'block_image');
define ('BLOCK_TYPE', 'block_type');

if ( class_exists( 'CDN_Enabler' ) ) {
    function check_valid_cdn_hotdeal() {
        $options = CDN_Enabler::get_options();
        if ( !empty( $options['url'] ) ) {
            return $options['url'];
        }
        return false;
    }
}

include SALE_DATE_DIR . '/widget/homepage-widget/homepage.php';

// register api
include SALE_DATE_DIR . '/api/function.php';

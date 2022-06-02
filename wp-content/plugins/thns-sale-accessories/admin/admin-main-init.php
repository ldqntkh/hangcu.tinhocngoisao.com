<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/includes/register-api.php';

function init_tab_label_sale_accessories() {
    include THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/views/sale-tab-label.php';
}

function init_tab_data_sale_accessories() {
    include THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/views/sale-tab-data.php';
} 

add_action( 'woocommerce_product_write_panel_tabs' , 'init_tab_label_sale_accessories' );
add_action( 'woocommerce_product_data_panels' , 'init_tab_data_sale_accessories' );

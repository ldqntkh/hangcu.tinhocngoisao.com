<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add the filter 
// add_filter( 'woocommerce_product_tabs', 'filter_woocommerce_product_tabs', 100, 1 ); 
add_action('woocommerce_after_single_product_summary','display_product_sale_accessories', 5 );

function display_product_sale_accessories() {
    global $product;

    // get setting sale accessories product
    // $_se_group_values = get_post_meta($product->get_id() , '_se_group_values', true);
    $campaign_class = new GEARVNSaleAccessoriesCampaign();
    $_se_group_values = $campaign_class->getInfoCampaignByProductId( $product->get_id() );
    if ( !empty($_se_group_values) ) {
        // $_se_group_values = json_decode(base64_decode( $_se_group_values ), true);

        if ( sizeof( $_se_group_values ) !== 0  && $product->is_type( array( 'simple', 'variable' ) ) ) {
            include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/storefront/views/display-sale-accessories-tab-data.php';
        }
    }
    
    return $tabs; 
}

// define the woocommerce_product_tabs callback 
function filter_woocommerce_product_tabs( $tabs ) { 
    global $product;

    unset( $tabs['accessories'] );

    // get setting sale accessories product
    // $_se_group_values = get_post_meta($product->get_id() , '_se_group_values', true);
    $campaign_class = new GEARVNSaleAccessoriesCampaign();
    $_se_group_values = $campaign_class->getInfoCampaignByProductId( $product->get_id() );
    if ( !empty($_se_group_values) ) {
        // $_se_group_values = json_decode(base64_decode( $_se_group_values ), true);

        if ( sizeof( $_se_group_values ) !== 0  && $product->is_type( array( 'simple', 'variable' ) ) ) {
            $tabs['accessories'] = array(
                'title'		=> esc_html__( 'Accessories', THNS_SALE_ACCESSORIES_PLUGIN ),
                'priority'	=> 5,
                'callback'	=> 'gearvn_product_accessories_tab',
            );
        }
    }
    
    return $tabs; 
}; 

function gearvn_product_accessories_tab() {
    include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/storefront/views/display-sale-accessories-tab-data.php';
}

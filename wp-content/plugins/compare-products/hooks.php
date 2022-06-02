<?php

add_action( 'woocommerce_product_after_tabs', 'display_product_compare', 10 );
add_action( 'render_compare_product_items', 'display_product_compare', 11);
if( !function_exists( 'display_product_compare' ) ) {
    function display_product_compare() {
        global $product;
        $product_type_id = ProductTypeApi::getGroupProductMappingByProductId($product->get_id());
        if ($product_type_id != null) { 
            $GLOBALS['product_type_id'] = $product_type_id; 
        }   
        wc_get_template( 'single-product/compare-product/compare-product-items.php' );
    }
}
<?php
if( !function_exists( 'check_manage_stock_product' ) ) {
    function check_manage_stock_product( $post_ID, $post, $update ) {
        $_product = wc_get_product($post_ID);
        if( is_object( $_product ) ) {
            $_product->set_manage_stock(true);
            $_product->save();
        }
    }
    add_action( 'save_post', 'check_manage_stock_product', 10, 3 );
}

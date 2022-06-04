<?php

if( !function_exists( 'disable_woocommerce_cart_fragments' ) ) {
    function disable_woocommerce_cart_fragments() { 
        wp_dequeue_script( 'wc-cart-fragments' );
    }
}

if( !function_exists( 'hc_mini_cart_fragment' ) ) {
    function hc_mini_cart_fragment( $fragment ) {
        global $woocommerce;
        $count = WC()->cart->get_cart_contents_count();
        $fragments['span.cart-items-count-number'] = $count;
        $fragments['span.cart-items-count'] = '<span class="cart-items-count count header-icon-counter">' . $count . '</span>';
        return $fragments;
    }
}

// add_action( 'woocommerce_update_cart_action_cart_updated', 'set_session_on_action_cart_updated', 20, 1 );
// function set_session_on_action_cart_updated( $cart_updated ){
//     $_SESSION['cart_total_count'] = WC()->cart->get_cart_contents_count(); 
// }


if( !function_exists( 'check_total_cart' ) ) {
    function check_total_cart() {
        if( !session_id() )
        {
            session_start();
        }
        $count = WC()->cart->get_cart_contents_count();
        $cart_hash = WC()->cart->get_cart_hash();
        $_SESSION['cart_hash'] = $cart_hash;
        $_SESSION['cart_total'] = $count;

        $fragments['span.cart-items-count-number'] = $count;
        $fragments['span.cart-items-count'] = '<span class="cart-items-count count header-icon-counter">' . $count . '</span>';
        wp_send_json_success( [
            "cart_hash" => $cart_hash,
            "fragments" => $fragments
        ]);
        die;
    }
    add_action('wp_ajax_check_total_cart', 'check_total_cart' );
    add_action('wp_ajax_nopriv_check_total_cart', 'check_total_cart' );
}

add_action( 'woocommerce_cart_updated', 'on_action_cart_updated', 10, 0 );
function on_action_cart_updated(  ){

    if( !session_id() )
    {
        session_start();
    }
    $_SESSION['cart_total'] = WC()->cart->get_cart_contents_count();
}

<?php

if( !function_exists( 'hc_re_buy_order' ) ) {
    function hc_re_buy_order () {
        defined( 'WC_ABSPATH' ) || exit;

        try {
            $order_id = $_POST['order_id'];

            if( !isset( $order_id ) ) {
                wp_send_json_error( [
                    "msg" => "Tham số không phù hợp"
                ] );
            }

            $order = wc_get_order( $order_id );
            if( !$order ) {
                wp_send_json_error( [
                    "msg" => "Đơn hàng không tồn tại"
                ] );
            }

            // Load cart functions which are loaded only on the front-end.
            include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
            include_once WC_ABSPATH . 'includes/class-wc-cart.php';

            if ( is_null( WC()->cart ) ) {
                wc_load_cart();
            }
            
            foreach ( $order->get_items() as $item_id => $item ) {
                $product_id = $item->get_product_id();
                $quantity  = $item->get_quantity();
                $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
                
                $was_added_to_cart = false;
                $adding_to_cart    = wc_get_product( $product_id );
                
                if ( ! $adding_to_cart ) {
                    continue;
                }
            
                $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );

                $quantity          = apply_filters( 'woocommerce_stock_amount', $quantity );
                $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
                
                if ( $passed_validation ) {
                    $was_added_to_cart = false;
                    if ( 'variable' === $add_to_cart_handler || 'variation' === $add_to_cart_handler ) {
                        if ( $adding_to_cart->is_type( 'variation' ) ) {
                            $variation_id   = $product_id;
                            $product_id     = $adding_to_cart->get_parent_id();
                        } else {
                            $adding_to_cart = wc_get_product( $adding_to_cart->get_visible_children()[0] );
                            $variation_id = $adding_to_cart->get_id();
                        }
                        $was_added_to_cart = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $adding_to_cart->get_variation_attributes() );
                    } else {
                        $was_added_to_cart =  WC()->cart->add_to_cart( $product_id, $quantity );
                    }
                }
            }

            wp_send_json_success( [
                "redirect" => wc_get_checkout_url()
            ] );

        } catch( Exception $e ) {
            wp_send_json_error( [
                "msg" => "Tham số không phù hợp"
            ] );
        }
    
    }
}
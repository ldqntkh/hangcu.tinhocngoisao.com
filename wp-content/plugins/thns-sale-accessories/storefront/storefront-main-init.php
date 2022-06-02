<?php

class StoreFrontMainHelper  {

    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        add_action('wp_ajax_insert_multiple_products_to_cart', array( 'StoreFrontMainHelper', 'insert_multiple_products_to_cart' ));
        add_action('wp_ajax_nopriv_insert_multiple_products_to_cart', array( 'StoreFrontMainHelper', 'insert_multiple_products_to_cart' ));
    }

    public static function insert_multiple_products_to_cart() {
        try {
            $product_data_add_to_cart = explode( ',', $_REQUEST['product_data_add_to_cart'] );
            foreach ( $product_data_add_to_cart as $product_data ) {
    
                // control product quantity
                $data = explode('_', $product_data);
                $product_id = $data[0];
                $_quantity = count($data) === 2 ? $data[1] : 1;
                $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
                $was_added_to_cart = false;
                $adding_to_cart    = wc_get_product( $product_id );
                
                if ( ! $adding_to_cart ) {
                    continue;
                }
            
                $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );
    
                // For now, quantity applies to all products.. This could be changed easily enough, but I didn't need this feature.
                $quantity          = apply_filters( 'woocommerce_stock_amount', $_quantity );
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
            //wc_add_notice( apply_filters( 'wc_add_to_cart_message', __('Sản phẩm đã được thêm thành công', THNS_SALE_ACCESSORIES_PLUGIN) ) );
             // Return fragments
            
            unset( $_SESSION['add_all_to_cart'] );
            $_SESSION['add_all_to_cart'] = true;

            WC_AJAX::get_refreshed_fragments();
        } catch(Exception $e) {
            unset( $_SESSION['add_all_to_cart'] );
            $_SESSION['add_all_to_cart'] = false;
            wp_send_json_error(
                array(
                    "msg" => __('Không thể thêm sản phẩm. Vui lòng thử lại.', THNS_SALE_ACCESSORIES_PLUGIN)
                )
            );
            
        }
        die;
    }
}

StoreFrontMainHelper::init();
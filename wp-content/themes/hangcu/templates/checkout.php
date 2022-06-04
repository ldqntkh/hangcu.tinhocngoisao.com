<?php 
/**
 * Template Name: Thanh toán
 */
    $isMobile = wp_is_mobile() ? '-mobile' : '';
    $checkout_step = 2; // with 1 is login
    require_once( 'checkout/lib/checkout-address-form.php' );

    if (isset($_GET['delete-address']) && !empty($_GET['delete-address'])) {
        hangcu_delete_address();
    }

    if ( isset( $_POST['shipping_account_address_action'] ) ) {
        // die;
        save_shipping_addresses();
    }
    
    if ( ! is_user_logged_in() ) {
        wp_redirect(home_url());
        exit;
        // if ( isset( $wp->query_vars['order-received'] ) ) {
        //     wp_redirect(home_url());
        // } else {
        //     WC()->session->set('checkoutstep', 1);
        //     get_header('checkout');
        //     include_once( 'checkout/login.php' );
        // }
    } else {
        do_action( 'hc_handle_redirect_payment' );
        try {
            $user_id = get_current_user_id();
            WC()->session->set('checkoutstep', 2);
            
            // if (!get_field('customer_mobile_phone', 'user_'.$user_id)) {
            //     // include_once( 'checkout/lib/verify-phone-view.php' );
            //     wp_redirect( home_url() . '?t='.uniqid($user_id . '_'));
			//     exit;
            // } else {
                if ( WC()->session->__isset( 'checkoutstep' ) && ! isset( $wp->query_vars['order-received'] ) ) {
                    $checkout_step = WC()->session->get('checkoutstep');
                } else if ( isset( $wp->query_vars['order-received'] ) ) {
                    WC()->session->set('checkoutstep', 4);
                    $checkout_step = 4;
                }
                
                if ( (!$checkout_step || isset( $_GET['step']) ) && $_GET['step'] === 'shipping' ) {
                    WC()->session->set( 'checkoutstep', 2 );
                    $checkout_step = 2;
                }
                if ( $checkout_step >= 2 && ( empty( $_GET['step'] )  || $_GET['step'] !== 'shipping' ) && !isset( $wp->query_vars['order-received']) ) {
                    $otherAddr = [];
                    
                    $otherAddr = get_user_meta( $user_id, 'hangcu_multiple_shipping_addresses', true );
                    $address_key_selected = WC()->session->get('address_key_selected');
                    
                    if( empty($address_key_selected) ) {
                        foreach ($otherAddr as $_key => $value) {
                            if( $otherAddr[$_key]['address_is_default'] == 'on' ) {
                                $address_key_selected = $_key;
                                
                                break;
                            }
                        }
                    }
                    // var_dump( $otherAddr, $address_key_selected );
                    // die;
                    if( !empty($address_key_selected) ) {
                        WC()->session->set('address_key_selected', $address_key_selected);
                        WC()->session->set('checkoutstep', 3);
                        $checkout_step = 3;
                    }

                    // if( $otherAddr && count( $otherAddr ) > 0 ) {
                    //     foreach ($otherAddr as $key => $value) {
                    //         if( $otherAddr[$key]['address_is_default'] == 'on' ) {
                    //             WC()->session->set('address_key_selected', $key);
                    //             WC()->session->set('checkoutstep', 3);
                    //             $checkout_step = 3;
                    //         }
                    //     }
                    // }
                }
                
                $mb_type = wp_is_mobile() ? 'mb-' : '';
                
                   
                switch( $checkout_step ) {
                    case 2:
                        include_once( 'checkout/'. $mb_type .'addresses.php' );
                        break;
                    case 3:
                        // check stock nhanhvn
                        if( function_exists( 'hc_helper_check_nhanhvn' ) ) {
                            foreach( WC()->cart->get_cart() as $cart_item ){
                                $nhanhvn_id = get_field('product_nhanhvn_id', $cart_item['product_id'] );
                                $product = wc_get_product( $cart_item['product_id'] );
                                $backorder = $product->managing_stock() && $product->backorders_allowed();
                                hc_helper_check_nhanhvn( $nhanhvn_id, $backorder, true, $cart_item['product_id'] );
                            }
                        }
                        include_once( 'checkout/'. $mb_type .'payment.php' );
                        break;
                    case 4:
                        include_once( 'checkout/thankpage.php' );
                        break;
                }
            // }
        } catch (Exception $e) {
            var_dump($e);
        }
        
    }

get_footer();
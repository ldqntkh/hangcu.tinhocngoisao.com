<?php

if ( !function_exists('woocommerce_checkout_coupon_form_js') ) {
    function woocommerce_checkout_coupon_form_js() { ?>
        <div class='custom-coupon-code'>
            <p class="form-row form-row-first custom-coupon">
                <input type="text" id="val_coupon_ajax" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" value="" />
            </p>

            <p class="form-row form-row-last custom-coupon">
                <button type="button" id="apply_coupon_ajax" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
            </p>
        </div>

        <div class="clear"></div>
    <?php }
}

function hangcu_vat_form() {
    ?>
    <div class="vat-form">
        <div class="checkbox-require-vat">
            <p class="form-row validate-required">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="require-vat" id="require-vat">
                    <span class="woocommerce-terms-and-conditions-checkbox-text">Yêu cầu xuất hoá đơn</span>
                </label>
            </p>
        </div>
        <div class="group-info" style="display: none">
            <p class="form-row form-row-wide validate-required">
                <span class="woocommerce-input-wrapper">
                    <input type="text" class="input-text " name="vat-company" placeholder="Tên công ty">
                </span>
            </p>
            <p class="form-row form-row-wide validate-required">
                <span class="woocommerce-input-wrapper">
                    <input type="text" class="input-text " name="vat-address-company" placeholder="Địa chỉ">
                </span>
            </p>
            <p class="form-row form-row-wide validate-required">
                <span class="woocommerce-input-wrapper">
                    <input type="text" class="input-text " name="vat-tax-code" placeholder="Mã số thuế">
                </span>
            </p>
        </div>
    </div>
<?php
}

function hangcu_save_vat_info($order_id) {
    if ($_POST['require-vat'] && !empty($_POST['vat-company']) && !empty($_POST['vat-address-company']) && !empty($_POST['vat-tax-code'])) {
        $current_user = wp_get_current_user();

        update_post_meta($order_id, 'vat_company_name', $_POST['vat-company']);
        update_post_meta($order_id, 'vat_address', $_POST['vat-address-company']);
        update_post_meta($order_id, 'vat_tax_code', $_POST['vat-tax-code']);
        update_post_meta($order_id, 'vat_email', $current_user->user_email);
        // add_option('hangcu_order_vat_info_company_'.$order_id, $_POST['vat-company']);
        // add_option('hangcu_order_vat_info_address_'.$order_id,  $_POST['vat-address-company']);
        // add_option('hangcu_order_vat_info_tax_code_'.$order_id, $_POST['vat-tax-code']);
    }
}

function hangcu_save_tracking_at( $order_id ) {
    if( WC()->session->__isset( 'tracking_order_at' ) ) {
        update_post_meta($order_id, 'tracking_at', json_encode(WC()->session->get( 'tracking_order_at' )) );
        // WC()->session->__unset( 'tracking_order_at' );
    }
}

function hangcu_save_depot_info( $order_id ) {
    if ( !empty(WC()->session->get( 'depot_id' )) ) {
        $depot_id = WC()->session->get( 'depot_id' );
        update_post_meta($order_id, 'depot_id', $depot_id);
        WC()->session->__unset( 'depot_id' );
    }
   
}

function hangcu_validate_vat_info() {
    if (isset($_POST['require-vat'])) {
        if (empty($_POST['vat-company'])) {
            wc_add_notice( __( "<strong>Tên công ty (VAT)</strong> không để trống", 'hangcu' ), 'error');
        }

        if (empty($_POST['vat-address-company'])) {
            wc_add_notice( __( "<strong>Địa chỉ (VAT)</strong> không để trống", 'hangcu' ), 'error');
        }

        if (empty($_POST['vat-tax-code'])) {
            wc_add_notice( __( "<strong>Mã số thuế (VAT)</strong> không để trống", 'hangcu' ), 'error');
        } else if (strlen($_POST['vat-tax-code']) < 10 || strlen($_POST['vat-tax-code']) > 15) {
            wc_add_notice( __( "<strong>Mã số thuế (VAT)</strong> không hợp lệ", 'hangcu' ), 'error');
        }
    }
}

function hangcu_validate_checkout() {
    if (empty(preg_match('/(09|03|07|08|05)+([0-9]{8}$)/', $_POST['billing_phone']))) {
        wc_add_notice( __( "<strong>Số điện thoại</strong> không hợp lệ", 'hangcu' ), 'error');
    }
}

function hangcu_empty_cart() {
?>
    <div class="hangcu-cart-empty">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/empty-cart.png"/>
        <p class="text-center">
            <?php echo __( 'Chưa có sản phẩm nào trong giỏ hàng của bạn.', 'hangcu' ); ?>
        </p>
    </div>
<?php
}

function hangcu_show_order_notes() {
    $checkout = WC()->checkout();
    $fields = $checkout->get_checkout_fields( 'order' );

    woocommerce_form_field('order_comments', $fields['order_comments']);
}

function conditional_custom_shipping_cost( $rates, $package ) {

    $config_enable_api_fee = false;
    if ( isset(get_option( CUSTOM_PREFERECE_PRM )['config_enable_api_fee']) ) {
        $config_enable_api_fee = get_option( CUSTOM_PREFERECE_PRM )['config_enable_api_fee'];
    }
    $shipping_fee = 0;
    
    if ( $config_enable_api_fee && $config_enable_api_fee == 'true' ) {
        $config_api_get_shipping_fee = get_option( CUSTOM_PREFERECE_PRM )['config_api_get_shipping_fee'];
        $config_api_mapping_city = get_option( CUSTOM_PREFERECE_PRM )['config_api_mapping_city'];
        
        if ( isset( $config_api_mapping_city ) && isset( $config_api_get_shipping_fee ) ) {
            parse_str($_POST['post_data'], $get_array);
    
            if ( empty( $get_array['billing_state'] ) ) {
                $get_array['billing_state'] = WC()->session->get('billing_state');
                //WC()->session->__unset( 'billing_state' );
            }
            if ( empty( $get_array['billing_address_2'] ) ) {
                $get_array['billing_address_2'] = WC()->session->get('billing_address_2');
                //WC()->session->__unset( 'billing_address_2' );
            }
            
            if ( isset( $get_array['billing_state'] ) && isset( $get_array['billing_address_2'] ) ) {
                
                $config_api_mapping_city        = $config_api_mapping_city.$get_array['billing_state'];
                $config_api_get_shipping_fee    = $config_api_get_shipping_fee.$get_array['billing_address_2'];
                
                try {
                    $mapping_city = file_get_contents( $config_api_mapping_city  );
    
                    $shipping_fee_depot = file_get_contents( $config_api_get_shipping_fee  );
                    
                    if ( isset( $mapping_city ) && isset( $shipping_fee_depot ) ) {
                        $mapping_city = json_decode( $mapping_city );
                        $shipping_fee_depot = json_decode( $shipping_fee_depot );
    
                        $depot_id = $mapping_city->data->depot_id;
    
                        $shipping_fee = get_object_vars($shipping_fee_depot->data);

                        $shipping_fee = intval($shipping_fee['shipping_fee_depot_'.$depot_id]);
                        WC()->session->set( 'depot_id', $depot_id );
                    }
            
                } catch ( Exception $e ) {
                    $shipping_fee = 0;
                }
            } else {
                $shipping_fee = 0;
            }
        }
    }

    foreach ( $rates as $rate_key => $rate_values ) {
        // Not for "Free Shipping method" (all others only)
        if ( 'free_shipping' === $rate_values->method_id ) {
            // Set the rate cost
            $rates[$rate_key]->cost = $shipping_fee;
        }
    }
    return $rates;
}

function refresh_shipping_methods( $post_data ){
    // $bool = true;
    // if ( WC()->session->get('billing_ups' ) == '1' ) $bool = false;

    // Mandatory to make it work with shipping methods
    foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
        WC()->session->set( 'shipping_for_package_' . $package_key, true );
    }
    WC()->cart->calculate_shipping();
}

if( !function_exists('check_valid_payment_methods') ) {
    function check_valid_payment_methods( $available_gateways ) {
        if( isset(WC()->cart) ) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                
                if( $_product->managing_stock() && $_product->backorders_allowed() ) {
                    $quantity = $cart_item['quantity'];
                    $stock = $_product->get_stock_quantity();
                    if( $quantity > $stock ) {
                        // hide all payment method except cod
                        foreach( $available_gateways as $payment ) {
                            if( $payment->id != 'cod' ) {
                                $available_gateways[$payment->id]->disable_method = true;
                            }
                        }
                        break;
                    }
                }
            }
        }
    
        return $available_gateways;
    }
}

if( !function_exists( 'check_valid_payment_method_processing_order' ) ) {
    function check_valid_payment_method_processing_order( $order_id, $posted_data, $order ) {
        $payment_method = $order->get_payment_method();
    
        if( isset(WC()->cart) ) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                
                if( $_product->managing_stock() && $_product->backorders_allowed() ) {
                    $quantity = $cart_item['quantity'];
                    $stock = $_product->get_stock_quantity();
                    if( $quantity > $stock && $payment_method != 'cod' ) {
                        throw new Exception( 'Đơn hàng của bạn chỉ có thể thanh toán bằng phương thức "Trả tiền mặt khi nhận hàng".' );
                    }
                }
            }
        }
    
    }
}


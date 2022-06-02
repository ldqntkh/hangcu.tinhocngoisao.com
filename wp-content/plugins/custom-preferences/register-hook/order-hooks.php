<?php

if ( !empty( get_option( 'custom_preferences_webhook_options' )['enable_webhook_order_created'] ) 
        && get_option( 'custom_preferences_webhook_options' )['enable_webhook_order_created'] === "true" ) :
    if ( !empty( get_option( 'custom_preferences_webhook_options' )['order_created_delivery_url'] ) && get_option( 'custom_preferences_webhook_options' )['order_created_delivery_url'] !== "" ) {
        
        if( !function_exists('ct_hook_order_created') ) {
            function hook_search_in_array($array, $key, $value)
            {
                $results = array();

                if (is_array($array)) {
                    if (isset($array[$key]) && $array[$key] == $value) {
                        $results[] = $array;
                    }elseif(isset($array[$key]) && is_serialized($array[$key]) && in_array($value,maybe_unserialize($array[$key]))){
                        $results[] = $array;
                    }
                    foreach ($array as $subarray) {
                        $results = array_merge($results, hook_search_in_array($subarray, $key, $value));
                    }
                }

                return $results;
            }

            function formatAddress( $state_code, $city_code, $address_2 ) {
                include CUSTOM_PREFERECE_DIR . '/register-hook/tinh_thanhpho.php';
                include CUSTOM_PREFERECE_DIR . '/register-hook/quan_huyen.php';
                include CUSTOM_PREFERECE_DIR . '/register-hook/xa_phuong_thitran.php';
                
                $tinh_thanh =  $tinh_thanhpho[$state_code];
                $quan = hook_search_in_array($quan_huyen,'matp',$state_code);
                usort($quan, 'devvn_natorder' );
                $ten_quan_huyen = '';
                if($quan) {
                    foreach( $quan as $q ) {
                        if( $q['maqh'] == $city_code ) {
                            $ten_quan_huyen = $q['name'];
                            break;
                        }
                    }
                }

                
                $xa = hook_search_in_array($xa_phuong_thitran,'maqh',$city_code);
                usort($xa, 'devvn_natorder' );
                $ten_xa = '';
                if($xa) {
                    foreach( $xa as $x ) {
                        if( $x['xaid'] == $address_2 ) {
                            $ten_xa = $x['name'];
                            break;
                        }
                    }
                }
                return $ten_xa . ', ' . $ten_quan_huyen . ', ' . $tinh_thanh;
            }

            function ct_hook_order_created($order) {
                $log = new WC_Logger();
                $url_hook = get_option( 'custom_preferences_webhook_options' )['order_created_delivery_url'];
                
                $log_entry .= 'Begin Delivery order details';
                $log->log( 'delivery-woocommerce-log-name', $log_entry );
                try {
                    $line_items = [];
                    foreach ( $order->get_items() as  $item_key => $item_values ) {
                        $item_data = $item_values->get_data();
                        $product = wc_get_product( $item_data['product_id'] );
                        $line_items[] = array (
                                'id' => $item_data['id'],
                                'name' => $item_data['name'],
                                'product_id' => $item_data['product_id'],
                                'variation_id' => $item_data['variation_id'],
                                'quantity' => $item_data['quantity'],
                                'tax_class' => $item_data['tax_class'],
                                'subtotal' => $item_data['subtotal'],
                                'subtotal_tax' => $item_data['subtotal_tax'],
                                'total' => $item_data['total'],
                                'total_tax' => $item_data['total_tax'],
                                'sku' => $product->get_sku(),
                                'price' => $product->get_price(),
                                'parent_name' => NULL,
                        );
                    }

                    $line_items = json_encode($line_items);
                    $data_order = [
                        'id' => $order->get_id(),
                        'parent_id' => 0,
                        'number' => strval($order->get_id()),
                        'order_key' => $order->get_order_key(),
                        'created_via' => 'checkout',
                        'version' => $order->get_version(),
                        'status' => $order->get_status(),
                        'currency' => 'VND',
                        'date_created' => $order->get_date_created()->format ('Y-m-d g:i'),
                        'date_created_gmt' => $order->get_date_created()->format ('Y-m-d g:i'),
                        'date_modified' => $order->get_date_created()->format ('Y-m-d g:i'),
                        'date_modified_gmt' => $order->get_date_created()->format ('Y-m-d g:i'),
                        'discount_total' => $order->get_discount_total(),
                        'discount_tax' => '0',
                        'shipping_total' => '0',
                        'shipping_tax' => '0',
                        'cart_tax' => '0',
                        'total' => $order->get_total(),
                        'total_tax' => '0',
                        'prices_include_tax' => false,
                        "customer_note" => $order->get_customer_note(),
                        // 'customer_id' => 0,
                        // 'customer_ip_address' => '171.236.69.113',
                        // 'customer_user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-A715F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Mobile Safari/537.36',
                        // 'customer_note' => '',
                        'billing' => 
                            array (
                                "first_name" => $order->get_billing_first_name(),
                                "last_name" => $order->get_billing_last_name(),
                                "company" => "",
                                "address_1" => $order->get_billing_address_1(),
                                "address_2" => $order->get_billing_address_2(),
                                "city" => $order->get_billing_city(),
                                "state" => $order->get_billing_state(),
                                "postcode" => "",
                                "country" => "VN",
                                "email" => $order->get_billing_email(),
                                "phone" => $order->get_billing_phone(),
                                "full_address" => $order->get_billing_address_1() . ', ' . formatAddress( $order->get_billing_state(), $order->get_billing_city(), $order->get_billing_address_2() )
                            ),
                        'shipping' => 
                            array (
                                "first_name" => $order->get_billing_first_name(),
                                "last_name" => $order->get_billing_last_name(),
                                "company" => "",
                                "address_1" => $order->get_billing_address_1(),
                                "address_2" => $order->get_billing_address_2(),
                                "city" => $order->get_billing_city(),
                                "state" => $order->get_billing_state(),
                                "postcode" => "",
                                "country" => "VN",
                            ),
                            "payment_method" => $order->get_payment_method(),
                            "payment_method_title" => $order->get_payment_method_title(),
                            'transaction_id' => '',
                            'date_paid' => NULL,
                            'date_paid_gmt' => NULL,
                            'date_completed' => NULL,
                            'date_completed_gmt' => NULL,
                            'cart_hash' => '7846b16e6e4e497043684784b2bbf606',
                            'line_items' => $line_items
                        ];
        
                    $order_object_data = json_encode($data_order, JSON_FORCE_OBJECT);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url_hook,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $order_object_data,
                        CURLOPT_HTTPHEADER => array(
                          'Content-Type: application/json'
                        ),
                    ));
        
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    if( $err ) {
                        $log_entry .= 'Error call api hook: ' . $err;
                        $log->log( 'delivery-woocommerce-log-name', $log_entry );
                    }
                    curl_close($curl);
        
                } catch ( Exception $e) {
                    // var_dump( $e );die;
                    $log_entry = print_r( $e, true );
                    $log_entry .= 'Exception Trace: ' . print_r( $e->getTraceAsString(), true );
                    $log->log( 'delivery-woocommerce-log-name', $log_entry );
                    return array(
                        "status" => "ERROR",
                        "errMsg" => $e->getMessage(),
                        "data" => null
                    );
                }
            }

            add_action( 'woocommerce_checkout_order_created', 'ct_hook_order_created', 99 );
        }
    }
endif;

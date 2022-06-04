<?php

add_action( 'woocommerce_after_update_order_data', function( $order) {

    // setup data
    $data_post = [];
    $data_post['order_id'] = $order->get_id();
    $data_post['payment_method'] = $order->get_payment_method();
    $data_post['status'] = $order->get_status();
    $data_post['total'] = $order->get_total();
    $data_post['order_key'] = $order->get_order_key();
    $data_post['billing'] =  array (
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
                                    "phone" => $order->get_billing_phone()
                                );

    if( $data_post['payment_method'] == 'zlp' ) {
        $data_post['transaction_id'] = get_field( 'zalo_transaction_id' , $order->get_id());
        $data_post['payment_change_at'] = get_field( 'order_create_at' , $order->get_id());
    } elseif( $data_post['payment_method'] == 'hangcu_momo_payment' ) {
        $data_post['transaction_id'] = get_field( 'momo_transaction_id' , $order->get_id());
        $data_post['payment_change_at'] = get_field( 'order_create_at' , $order->get_id());
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://webhook.hangcu.xyz/v1/hook/payment/status',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data_post),
        CURLOPT_HTTPHEADER => array( 'Content-Type: application/json' ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
      

}, 10, 1 );
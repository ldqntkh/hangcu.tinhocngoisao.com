<?php

add_filter('woocommerce_webhook_payload', function($payload, $resource, $resource_id, $this_id) {
    if( $resource == 'order' ) {
        // get product intergrated code
        for( $i = 0; $i < count( $payload['line_items'] ); $i++ ) {
            $product_id = $payload['line_items'][$i]['product_id'];
            $product_integrated_id = get_field( 'product_integrated_id', $product_id );
            if( $product_integrated_id ) {
                $payload['line_items'][$i]['product_integrated_id'] = $product_integrated_id;
            }
        }
        // get state for billing
        $orderID = $payload['id'];
        $payload['billing']['state_name'] = $payload['billing']['state'];
        $payload['billing']['state'] = get_post_meta($orderID, '_billing_state', true);

        $payload['billing']['city_name'] = $payload['billing']['city'];
        $payload['billing']['city'] = get_post_meta($orderID, '_billing_city', true);

        $payload['billing']['address_2_name'] = $payload['billing']['address_2'];
        $payload['billing']['address_2'] = get_post_meta($orderID, '_billing_address_2', true);

        $payload['shipping']['state_name'] = $payload['shipping']['state'];
        $payload['shipping']['state'] = get_post_meta($orderID, '_shipping_state', true);

        $payload['shipping']['city_name'] = $payload['shipping']['city'];
        $payload['shipping']['city'] = get_post_meta($orderID, '_shipping_city', true);

        $payload['shipping']['address_2_name'] = $payload['shipping']['address_2'];
        $payload['shipping']['address_2'] = get_post_meta($orderID, '_shipping_address_2', true);

        // vat
        if ( !empty( get_field('vat_company_name', $orderID) ) ) {
            $payload['vat']['company'] = get_field('vat_company_name', $orderID);
            $payload['vat']['tax_code'] = get_field('vat_tax_code', $orderID);
            $payload['vat']['address'] = get_field('vat_address', $orderID);
            $payload['vat']['email'] = get_field('vat_email', $orderID);
        }

        // at
        if ( !empty( get_field('tracking_at', $orderID) ) ) {
            $payload['tracking_at'] = json_decode( get_field('tracking_at', $orderID) );
        }
    }

    return $payload; 
}, 10, 4);
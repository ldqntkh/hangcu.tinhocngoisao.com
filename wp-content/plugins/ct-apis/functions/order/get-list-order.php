<?php

function get_ct_order_status_text($key) {
    $status_codes = wc_get_order_statuses();
    foreach( $status_codes as $code => $item ) {
        if( $code == 'wc-'.$key ) return $item;
    }
    return '';
}

if( !function_exists( 'hc_get_list_orders' ) ) {
    function hc_get_list_orders( ) {
        $page = 1;
        $perpage = 10;

        if( isset( $_POST ) && isset($_POST['page']) ) {
            if( is_numeric( $_POST['page'] ) ) {
                $page = intval( $_POST['page'] );
            }
        }

        $customer = wp_get_current_user();

        $status_codes = wc_get_order_statuses();
        $arrCode = [];
        foreach( $status_codes as $code => $item ) {
            $arrCode[] = 'wc-'.$key;
        }
        
        $args = array(
            'customer_id' => $customer->ID,
            'status' => $arrCode,
            'limit' => $perpage,
            'paged' => $page,
        );
        $orders = wc_get_orders($args);

        $Order_Array = []; //
        
        foreach ($orders as $order) {
            $orderq = wc_get_order($order);
            $products = [];
            foreach ( $orderq->get_items() as $item_id => $item ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_product_id() ), 'single-post-thumbnail' );
                $products[] = [
                    "name"  => $item->get_name(),
                    "image" => $image[0],
                    "quantity"  => $item->get_quantity(),
                    "total" => $item->get_total(),
                    "sub_total" => $item->get_subtotal(),
                ];
            }

            $Order_Array[] = [
                "ID" => $orderq->get_id(),
                "total" => $orderq->get_total(),
                "created_at" => $orderq->get_date_created()->date_i18n('Y-m-d'),
                "total_product" => $orderq->get_item_count(),
                "products" => $products,
                "view_link" => $order->get_view_order_url(),
                "status_code"    => $order->get_status(),
                "status"    => get_ct_order_status_text( $order->get_status() ),
                "sub_total"    => $order->get_total(),
                "total"    => $order->get_subtotal(),
                "shipping_fee" => $order->get_shipping_total()
            ];

        }

        wp_send_json_success([
            "orders" => $Order_Array,
            "total_order"   => wc_get_customer_order_count( $customer->ID ),
            "msg"   => ""
        ]);
        die;
    }
}
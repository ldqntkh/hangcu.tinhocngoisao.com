<?php

if( !function_exists( 'hc_get_order_detail' ) ) {
    function hc_get_order_detail() {
        $order_id = $_POST['order_id'];

        if( !isset( $order_id ) ) {
            wp_send_json_error( [
                "msg" => "Tham số không phù hợp"
            ] );
            die;
        }

        $order = wc_get_order( $order_id );
        if( !$order ) {
            wp_send_json_error( [
                "msg" => "Đơn hàng không tồn tại"
            ] );
            die;
        }

        $user_id = $order->get_user_id();
        if( get_current_user_id() != $user_id ) {
            wp_send_json_error( [
                "msg" => "Đơn hàng không tồn tại"
            ] );
            die;
        }

        $products = [];
        foreach ( $order->get_items() as $item_id => $item ) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_product_id() ), 'single-post-thumbnail' );
            $products[] = [
                "name"  => $item->get_name(),
                "image" => $image[0],
                "quantity"  => $item->get_quantity(),
                "total" => $item->get_total(),
                "sub_total" => $item->get_subtotal(),
            ];
        }

       // vat
       $vat = [];
       if ( !empty( get_field('vat_company_name', $order_id) ) ) {
            $vat['company'] = get_field('vat_company_name', $order_id);
            $vat['tax_code'] = get_field('vat_tax_code', $order_id);
            $vat['address'] = get_field('vat_address', $order_id);
            $vat['email'] = get_field('vat_email', $order_id);
        }

        $order_cancel_value =  get_option( 'custom_preferences_order' )['config_label_cancel_order'];
        if( $order_cancel_value ) {
            $order_cancel_value = explode("\n", $order_cancel_value);
        }

        wp_send_json_success([
            "ID" => $order->get_id(),
            "total" => $order->get_total(),
            "created_at" => $order->get_date_created()->date_i18n('Y-m-d'),
            "total_product" => $order->get_item_count(),
            "products" => $products,
            "view_link" => $order->get_view_order_url(),
            "status_code"    => $order->get_status(),
            "status"    => get_ct_order_status_text( $order->get_status() ),
            "sub_total"    => $order->get_total(),
            "total"    => $order->get_subtotal(),
            "shipping_fee" => $order->get_shipping_total(),
            "order_address" => $order->get_address(),
            "payment_method" => $order->get_payment_method(),
            "payment_method_title" => $order->get_payment_method_title(),
            "vat"   => $vat,
            "cancel_order_values" => $order_cancel_value
        ]);
        die;
    }
}
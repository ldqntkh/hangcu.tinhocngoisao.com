<?php

if( !function_exists( 'hc_pending_cancel_order' ) ) {
    function hc_pending_cancel_order() {
        if ( isset( $_POST['order_id'] ) && isset( $_POST['order_cancel_value'] ) && isset( $_POST['order_cancel_note'] ) ) {
            $order = wc_get_order( intval($_POST['order_id']) );
            if ( !empty( $order ) ) {
                if ( $order->get_payment_method() == 'cod' && $order->get_status() == 'processing' ) {
                    
                    do_action( 'hangcu_send_pendingcancel_order', $order->get_id(), $_POST['order_cancel_value'] );
                    
                    $order->update_status( 'pendingcancel' );
                    // transfer to update post meta
                    update_post_meta( $order->get_id(), 'order_cancel_value', $_POST['order_cancel_value'] );
                    update_post_meta( $order->get_id(), 'order_cancel_note', $_POST['order_cancel_note'] );
                    wp_send_json_success();
                } else {
                    wp_send_json_error([
                        "msg" => "Đơn hàng này không được phép hủy!"
                    ]);
                }
            } else {
                wp_send_json_error([
                    "msg" => "Không tìm thấy đơn hàng"
                ]);
            }

        } else {
            wp_send_json_error([
                "msg" => "Data not found!"
            ]);
        }
        die;
    }
}

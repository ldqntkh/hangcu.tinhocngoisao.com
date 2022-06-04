<?php
add_action( 'init', 'register_awaiting_cancel_order_status' );
add_filter( 'wc_order_statuses', 'add_awaiting_cancel_to_order_statuses' );
// add_action( 'wp_ajax_pendingcancelorder' , 'request_pending_cancel_order' );
// add_action( 'woocommerce_after_account_orders', 'hangcu_order_data_cancel' );

add_filter( 'woocommerce_email_classes', 'hangcu_filter_woocommerce_email_classes', 10, 1 ); 
 
// add the filter 
add_filter( 'woocommerce_email_actions', 'filter_woocommerce_email_actions', 10, 1 ); 

// add_filter( 'woocommerce_email_recipient_cancelled_order', 'wc_cancelled_order_add_customer_email', 10, 2 );
// add_filter( 'woocommerce_email_recipient_failed_order', 'wc_cancelled_order_add_customer_email', 10, 2 );
// add_filter( 'woocommerce_email_recipient_pendingcancel_order', 'wc_cancelled_order_add_customer_email', 10, 2 );


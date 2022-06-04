<?php

if( !function_exists('hc_change_order_post_password') ) {
    function hc_change_order_post_password( $order_get_status_edit ) { 
        $order_get_status_edit['post_password'] = str_replace('wc_order_', '', $order_get_status_edit['post_password']);
        $order_get_status_edit['post_password'] = strtoupper($order_get_status_edit['post_password']);
        return $order_get_status_edit; 
    }
}
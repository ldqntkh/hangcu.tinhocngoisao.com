<?php

if( !function_exists( 'hc_get_list_address' ) ) {
    function hc_get_list_address() {
        $customer = wp_get_current_user();

        $otherAddr = get_user_meta( $customer ->ID, 'hangcu_multiple_shipping_addresses', true );

        // lấy danh sách tỉnh thành 
        $tinh_thanhpho_ = [];
        $plugin_path_file = ABSPATH . 'wp-content/plugins/devvn-woo-address-selectbox/cities/tinh_thanhpho.php';
        if( file_exists($plugin_path_file) ) {
            global $tinh_thanhpho;
            $tinh_thanhpho_ = $tinh_thanhpho;
        }

        wp_send_json_success( [
            "address" => $otherAddr,
            "cities"    => $tinh_thanhpho_
        ] );
        die;
    }
}
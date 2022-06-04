<?php

if( !function_exists( 'hc_get_account_info' ) ) {
    function hc_get_account_info() {
        $user = wp_get_current_user();
        if( !$user ) {
            wp_send_json_error([
                "msg" => __("Không tìm thấy thông tin người dùng", HC_API_PLUGIN)
            ]);
            die;
        }
        wp_send_json_success([
            "user"  => $user,
            "msg"   => ""
        ]);
        die;
    }
}
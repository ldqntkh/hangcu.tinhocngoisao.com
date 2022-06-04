<?php

if( !function_exists('hc_delete_address') ) {
    function hc_delete_address() {
        $currentUser = wp_get_current_user();
        $otherAddr = [];
        if( !isset($_POST['delete-address']) ) {
            wp_send_json_error([
                "errMsg" => "Không tìm thấy dữ liệu"
            ]);
            die;
        }
        if ($currentUser->ID !== 0) {
            $otherAddr = get_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', true );
            if( $otherAddr[$_POST['delete-address']]['address_is_default'] == 'on' ) {
                wp_send_json_error([
                    "errMsg" => "Không thể xóa địa chỉ mặc định"
                ]);
                die;
            }
            unset($otherAddr[$_POST['delete-address']]);

            update_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', $otherAddr );
            wp_send_json_success();
            die;
        }

        wp_send_json_error([
            "errMsg" => "Đã có lỗi xảy ra, vui lòng thử lại"
        ]);
        die;
    }
}
<?php
if( !function_exists('hc_update_account_info') ) {
    function hc_update_account_info() {
        $display_name = $_POST['display_name'];
        $current_pass = $_POST['current_pass'];
        $new_pass = $_POST['new_pass'];

        if( !$display_name || trim($display_name) == '' ) {
            wp_send_json_success([
                "msg_dname"=> "Họ tên không phù hợp"
            ]);
            die;
        }

        $isChangePass = false;

        if( $current_pass && trim($current_pass) != '' ) {
            if( !$new_pass || trim($new_pass) == '' ) {
                wp_send_json_success([
                    "msg_pass"=> "Mật khẩu mới không phù hợp"
                ]);
                die;
            };
            if( strlen(trim($new_pass)) < 6 ) {
                wp_send_json_success([
                    "msg_pass"=> "Mật khẩu mới phải có ít nhất 6 ký tự"
                ]);
                die;
            }
            $isChangePass= true;
        }
        $customer = wp_get_current_user();
        // update user display name
        $msg_dname = '';
        if( $customer->data->display_name!=$display_name ) {
            $update_dname = wp_update_user( array ('ID' => $customer->ID, 'display_name' => $display_name));
        
            if( is_wp_error($update_dname) ) {
                $msg_dname = 'Không thể cập nhật tên hiển thị';
            }
        }
        
        $msg_pass = '';
        if( $isChangePass ) {
            if( ! wp_check_password( $current_pass, $customer->user_pass, $customer->ID ) ) {
                $msg_pass = 'Mật khẩu không chính xác';
            } else {
                // update pass
                wp_set_password( $new_pass, $customer->ID );
            }
        }
       
        wp_send_json_success( [
            "msg_dname" => $msg_dname,
            "msg_pass" => $msg_pass
        ] );
        die;
    }
}
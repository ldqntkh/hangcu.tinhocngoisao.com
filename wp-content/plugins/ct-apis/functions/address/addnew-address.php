<?php
if( !function_exists( 'hc_add_new_address' ) ) {
    function hc_add_new_address() {
        $customer_id = get_current_user_id();
        $otherAddr = get_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', true );

        if ($otherAddr && count($otherAddr) >= 10) {
            wp_send_json_error(array('msg' => 'Bạn chỉ được tạo tối đa 10 địa chỉ'), 400);
            die;
        }

        if (empty($otherAddr)) $otherAddr  = array();

        if (isset($_POST['add_new_saved_address_field'])) {

            $billing_last_name = $_POST['billing_last_name'];
            $billing_phone = $_POST['billing_phone'];
            $billing_state = $_POST['billing_state'];
            $billing_city = $_POST['billing_city'];
            $billing_address_1 = $_POST['billing_address_1'];
            $billing_address_2 = $_POST['billing_address_2'];
            $billing_email = $_POST['billing_email'];
            $address_is_default = $_POST['address_is_default'];
            // create unit key for address
            $new_key = md5( $billing_last_name . ':' . $billing_phone . ':' . $billing_state . ':' . $billing_city . ':' . $billing_address_1 . ':' . $billing_address_2 );

            if( $address_is_default == 'on' ) {
                // disable hết tất cả các address khác
                foreach ($otherAddr as $key => $value) {
                    $otherAddr[$key]['address_is_default'] = 'off';
                }
                
            }

            $otherAddr[$new_key]['billing_last_name'] = $billing_last_name;
            $otherAddr[$new_key]['billing_phone'] = $billing_phone;
            $otherAddr[$new_key]['billing_state'] = $billing_state;
            $otherAddr[$new_key]['billing_city'] = $billing_city;
            $otherAddr[$new_key]['billing_address_1'] = $billing_address_1;
            $otherAddr[$new_key]['billing_address_2'] = $billing_address_2;
            $otherAddr[$new_key]['full_address'] = $_POST['full_address'];
            $otherAddr[$new_key]['billing_email'] = $billing_email;
            $otherAddr[$new_key]['address_is_default'] = $address_is_default;

            update_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', $otherAddr );
            
            wp_send_json_success();

            die;
        }

        wp_send_json(array('msg' => 'Đã có lỗi xảy ra. Vui lòng tải lại trang'), 400);

        die;
    }
}
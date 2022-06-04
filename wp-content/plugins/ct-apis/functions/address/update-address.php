<?php

if( !function_exists('hc_update_address') ) {
    function hc_update_address() {
        if (isset($_POST['add_new_saved_address_field'])) {
            $key = $_POST['key_edit_address'];
      
            $customer_id = get_current_user_id();
            $otherAddr = get_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', true );
      
            if (!$otherAddr[$key]) {
              wp_send_json_error(array('errMsg' => 'Đã có lỗi xảy ra'), 400);
              die;
            }
            // remove old key
            // unset($otherAddr[$key]);
            $newOtherAddress = [];
      
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
      
            // $otherAddr = array_replace_keys($otherAddr, [$key => $new_key]);
      
            if( $address_is_default == 'on' ) {
              // disable hết tất cả các address khác
              foreach ($otherAddr as $k => $value) {
                $otherAddr[$k]['address_is_default'] = 'off';
              }
            }
      
            foreach ($otherAddr as $skey => $value) {
              if( $skey == $key ) {
                $newOtherAddress[$new_key] = [];
                $newOtherAddress[$new_key]['billing_last_name'] = $billing_last_name;
                $newOtherAddress[$new_key]['billing_phone'] = $billing_phone;
                $newOtherAddress[$new_key]['billing_state'] = $billing_state;
                $newOtherAddress[$new_key]['billing_city'] = $billing_city;
                $newOtherAddress[$new_key]['billing_address_1'] = $billing_address_1;
                $newOtherAddress[$new_key]['billing_address_2'] = $billing_address_2;
                $newOtherAddress[$new_key]['full_address'] = $_POST['full_address'];
                $newOtherAddress[$new_key]['billing_email'] = $billing_email;
                $newOtherAddress[$new_key]['address_is_default'] = $address_is_default;
              } else {
                $newOtherAddress[$skey] = $otherAddr[$skey];
              }
            }
      
            update_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', $newOtherAddress );
      
            wp_send_json_success();
      
            die;
        }
        wp_send_json_error(array('errMsg' => 'Đã có lỗi xảy ra'), 400);
        die;
    }
}
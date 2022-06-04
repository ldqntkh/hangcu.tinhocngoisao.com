<?php

require get_stylesheet_directory().'/inc/helper/validate-address.php';

add_action( 'wp_ajax_addaddress', 'add_adress_func', 20 );
function add_adress_func() {
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    wp_send_json(null, 204);

    die;
  }

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_send_json(array('messages' => 'Invalid request'), 400);
    die;
  }

  $customer_id = get_current_user_id();
  $otherAddr = get_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', true );
  $errors = hangcu_validate_address_checkout();

  if ($otherAddr && count($otherAddr) >= 10) {
    wp_send_json(array('messages' => 'Bạn chỉ được tạo tối đa 10 địa chỉ'), 400);

    die;
  }

  if (empty($otherAddr)) $otherAddr  = array();

  if ($errors) {
    wp_send_json($errors, 400);

    die;
  }

  if (!wp_verify_nonce($_REQUEST['add_new_saved_address_field'], 'add_new_saved_address')) {
    wp_send_json(array('messages' => 'Thời gian của bạn đã hết. Vui lòng tải lại trang để thêm mới địa chỉ'), 400);

    die;
  }

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
      
      wp_send_json(array('messages' => 'Lưu thành công', 'key' => $new_key), 200);

      die;
  }

  wp_send_json(array('messages' => 'Đã có lỗi xảy ra. Vui lòng tải lại trang'), 400);

  die;
}

add_action( 'wp_ajax_update_address', 'hangcu_update_address', 20 );
function hangcu_update_address() {
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    wp_send_json(null, 204);

    die;
  }

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_send_json(array('messages' => 'Invalid request'), 400);
    die;
  }

  $errors = hangcu_validate_address_checkout();

  if ($errors) {
    wp_send_json($errors, 400);

    die;
  }

  if (!wp_verify_nonce($_REQUEST['add_new_saved_address_field'], 'add_new_saved_address')) {
    wp_send_json(array('messages' => 'Thời gian của bạn đã hết. Vui lòng tải lại trang để thêm mới địa chỉ'), 400);

    die;
  }

  if (isset($_POST['add_new_saved_address_field'])) {
      $key = $_POST['key_edit_address'];

      $customer_id = get_current_user_id();
      $otherAddr = get_user_meta( $customer_id, 'hangcu_multiple_shipping_addresses', true );

      if (!$otherAddr[$key]) {
        wp_send_json(array('messages' => 'Đã có lỗi xảy ra'), 400);
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

      wp_send_json(array('messages' => 'Lưu thành công'), 200);

      die;
  }

  wp_send_json(array('messages' => 'Đã có lỗi xảy ra. Vui lòng tải lại trang'), 400);
  die;
}

<?php
  function gearvn_validate_address_checkout() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $keyMap = array(
        'billing_last_name' => 'Họ tên',
        'billing_phone' => 'Số điện thoại',
        'billing_state' => 'Tỉnh/TP',
        'billing_city' => 'Quận/Huyện',
        'billing_address_2' => 'Xã/Phường',
        'billing_address_1' => 'Địa chỉ'
    );

    $errors = array();

    foreach($keyMap as $key => $value) {
        if (empty($_POST[$key])) {
            $errors[$key] = __( '<strong>'.$value.'</strong> không được để trống', 'gearvn' );
        } else if (!is_string($_POST[$key])) {
            $errors[$key] = __( '<strong>'.$value.'</strong> không hợp lệ', 'gearvn' );
        }
    }

    if (!isset($errors['billing_phone']) && empty(preg_match('/(09|03|07|08|05)+([0-9]{8}$)/', $_POST['billing_phone']))) {
        $errors['billing_phone'] = __( '<strong>Số điện thoại</strong> không hợp lệ', 'gearvn' );
    }

    if (empty($errors)) {
        return null;
    }

    return $errors;
  }
?>
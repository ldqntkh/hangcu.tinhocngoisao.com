<?php
  defined( 'ABSPATH' ) || exit;

  $user_id = get_current_user_id();

  $config_otp_max_send = get_option( CONFIG_OTP_VALUES )['config_otp_max_send'];
  if ( !$config_otp_max_send ) $config_otp_max_send = 3;


  $errors = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['otp_code'] = is_string($_POST['otp_code']) ? trim($_POST['otp_code']) : $_POST['otp_code'];
    $_POST['customer_mobile_phone'] = is_string($_POST['customer_mobile_phone']) ? trim($_POST['customer_mobile_phone']) : $_POST['customer_mobile_phone'];

    if ($_POST['otp_code'] && $_POST['customer_mobile_phone'] && wp_verify_nonce($_POST['verify-phone-form-view-nonce'], 'verify_phone_form_view')) {
      $params = array(
        'meta_key'     => 'customer_mobile_phone',
        'meta_value'   => $_POST['customer_mobile_phone']
      );

      $user = get_users($params);

      if ($user && !empty($user)) {
        $errors = '<strong>Số điện thoại</strong> đã tồn tại';
      } else {
        if (validPhoneNumberWithOtpCode($_POST['customer_mobile_phone'], $_POST['otp_code'])) {
          update_field( 'customer_mobile_phone', $_POST['customer_mobile_phone'], 'user_'.$user_id );
    
          header('Location: '.wc_get_checkout_url());
          die;
        } else {
          $errors = __('<strong>Mã xác thực</strong> không hợp lệ', 'hangcu');
        }
      }
    } else {
      $errors = __('<strong>Mã xác thực</strong> không hợp lệ', 'hangcu');
    }
  }
?>

<div class="verify-phone-form">
  <h4><?php echo __('Tài khoản của bạn chưa được cập nhật số điện thoại. Vui lòng xác thực số điện thoại trước khi đặt hàng.', 'hangcu'); ?></h4>
  <div class="woocommerce-notices-wrapper">
    <?php
      if ($errors) {
        ?>
        <ul class="woocommerce-error" role="alert">
          <li><?php echo $errors; ?></li>
        </ul>
        <?php
      }
    ?>
  </div>
  <form class="form-verify-phone" method="post">
    <div class="woocommerce-form-row woocommerce-form-row--first form-row">
      <label for="verify_customer_mobile_phone"><?php esc_html_e( 'Số điện thoại', 'hangcu' ); ?>&nbsp;<span class="required">*</span></label>
      <div class="phone-wrapper">
        <input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="customer_mobile_phone" id="verify_customer_mobile_phone" value="<?php echo isset($_POST['customer_mobile_phone']) ? $_POST['customer_mobile_phone'] : ''; ?>"/>
        <button class="btn-send-opt-verify" data-text-retry="<?php echo __('Gửi lại', 'hangcu'); ?>" data-text-retry-time="<?php echo __('Tối đa '.  $config_otp_max_send .' lần/ngày', 'hangcu'); ?>" data-text="<?php echo __('Gửi xác thực', 'hangcu'); ?>"><?php echo __('Gửi xác thực', 'hangcu'); ?></button>
      </div>
    </div>
    <p class="woocommerce-form-row woocommerce-form-row--last form-row">
      <label for="otp_code"><?php esc_html_e( 'Mã xác thực', 'hangcu' ); ?>&nbsp;<span class="required">*</span></label>
      <input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="otp_code" id="otp_code" value="" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--last form-row">
      <?php wp_nonce_field( 'verify_phone_form_view', 'verify-phone-form-view-nonce' ); ?>
      <input type="submit" class="woocommerce-Button button" name="verify_phone" value="<?php esc_html_e( 'Xác nhận', 'hangcu' ); ?>"/>
    </p>
  </form>
</div>
<script>
  history.replaceState(null, null, location.href);
</script>
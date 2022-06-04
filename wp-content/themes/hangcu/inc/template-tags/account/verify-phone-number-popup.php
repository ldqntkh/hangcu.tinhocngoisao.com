<?php
    defined( 'ABSPATH' ) || exit;
    $config_otp_max_send = get_option( CONFIG_OTP_VALUES )['config_otp_max_send'];
    if ( !$config_otp_max_send ) $config_otp_max_send = 3;
?>

<div class="popup-verify-account-phone-number">
    <div class="verify-phone-form">
        <a href="#" id="close-popup-phone"><span id="close-login-popup" class="electro-close-icon"></span></a>
        <h4><?php echo __('Xác thực số điện thoại', 'hangcu'); ?></h4>
        <form class="form-verify-phone" method="post">
            <div class="woocommerce-form-row woocommerce-form-row--first form-row">
                <label for="verify_customer_mobile_phone"><?php esc_html_e( 'Số điện thoại', 'hangcu' ); ?></label>
                <div class="phone-wrapper">
                    <input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="customer_mobile_phone" id="verify_customer_mobile_phone" value="<?php echo isset($_POST['customer_mobile_phone']) ? $_POST['customer_mobile_phone'] : ''; ?>"/>
                    <button class="btn-send-opt-verify" data-text-retry="<?php echo __('Gửi lại', 'hangcu'); ?>" data-text-retry-time="<?php echo __('Tối đa ' .$config_otp_max_send. ' lần/ngày', 'hangcu'); ?>" data-text="<?php echo __('Gửi mã xác thực', 'hangcu'); ?>"><?php echo __('Gửi mã xác thực', 'hangcu'); ?></button>
                </div>
            </div>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-otp">
                <label for="otp_code"><?php esc_html_e( 'Mã xác thực', 'hangcu' ); ?></label>
                <input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="otp_code" id="otp_code" value="" />
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row">
                <?php wp_nonce_field( 'verify_phone_form_view', 'verify-phone-form-view-nonce' ); ?>
                <input type="submit" class="woocommerce-Button button" name="verify_phone" value="<?php esc_html_e( 'Xác nhận', 'hangcu' ); ?>"/>
            </p>
        </form>
    </div>
</div>
<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); 
$type = '';
if( isset($_GET['type']) && $_GET['type']=='register' ) {
    $type = 'active';
}
?>

<div id="form-customer-account">
    <div class="mb-nav-cart">
        <i class="icon-back"></i>
        <h3><?php if( $type == '' ) echo 'Đăng nhập'; else echo "Tạo tài khoản"; ?></h3>
    </div>
    <div class="form-account">
        <div class="header <?= $type ?>">
            <a href="#" id="form-account-login" >Đăng nhập</a>
            <a href="#" id="form-account-register" >Tạo tài khoản</a>
        </div>
        <div class="body-content <?= $type ?>">
            <div class="form-content">
                <div class="form-login">
                    <div class="input-group">
                        <label for="lg-username">Email/SĐT</label>
                        <input placeholder="Nhập Email hoặc số điện thoại" type="text" name="lg-username" id="lg-username" />
                    </div>
                    <div class="input-group">
                        <label for="lg-password">Mật khẩu</label>
                        <input placeholder="Nhập mật khẩu" type="password" name="lg-password" id="lg-password" />
                    </div>
                    <div >
                        <p class="forgot-pass">
                            <a href="#">Quên mật khẩu?</a>
                        </p>
                    </div>
                    <div>
                        <p id="error-msg-lg"></p>
                    </div>
                    <div class="input-group">
                        <button id="btn-login-account" type="button">ĐĂNG NHẬP</button>
                    </div>
                    <div class="or-social">
                        <p>Hoặc</p>
                        <div>
                        <?php echo do_shortcode('[nextend_social_login provider="facebook"]');?>
                        <?php echo do_shortcode('[nextend_social_login provider="google"]');?>
                        </div>
                    </div>
                </div>
                <div class="form-register">
                    <div class="input-group">
                        <label for="rg-fullname">Họ tên</label>
                        <input placeholder="Nhập họ tên" type="text" name="rg-fullname" id="rg-fullname" />
                    </div>
                    <div class="input-group phone-wrapper">
                        <label for="rg-phonenumber">Số điện thoại</label>
                        <input placeholder="Nhập số điện thoại" type="text" name="rg-phonenumber" id="rg-phonenumber" class=".woocommerce-Input"/>
                        <button class="btn-send-opt-verify" data-text-retry="<?php esc_html_e( 'Gửi lại', 'hangcu' ); ?>" data-text="<?php esc_html_e( 'Gửi xác thực', 'hangcu' ); ?>"><?php esc_html_e( 'Gửi xác thực', 'hangcu' ); ?></button>
                    </div>
                    <div class="input-group">
                        <label for="rg-verify_phone">Mã xác thực</label>
                        <input placeholder="Nhập mã xác thực gửi từ điện thoại" type="text" name="rg-verify_phone" id="rg-verify_phone" />
                    </div>
                    <div class="input-group">
                        <label for="rg-email">Email</label>
                        <input placeholder="Nhập Email" type="text" name="rg-email" id="rg-email" />
                    </div>
                    <div class="input-group">
                        <label for="rg-password">Mật khẩu</label>
                        <input placeholder="Nhập password" type="password" name="rg-password" id="rg-password" />
                    </div>
                    <div>
                        <p id="error-msg-rg"></p>
                    </div>
                    <div class="input-group">
                        <button id="btn-register-account" type="button">Tạo tài khoản</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    do_action( 'woocommerce_after_customer_login_form' ); 
?>

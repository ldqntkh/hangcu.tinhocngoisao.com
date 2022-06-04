<?php

/**
 * Template Name: Custom my account page
 */

// check lost password page

if ( !is_user_logged_in() && ! empty( $_GET['show-reset-form'] ) ) {
    if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine
        list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ); // @codingStandardsIgnoreLine
        $userdata               = get_userdata( absint( $rp_id ) );
        $rp_login               = $userdata ? $userdata->user_login : '';
        $user                   = WC_Shortcode_My_Account::check_password_reset_key( $rp_key, $rp_login );

        // Reset key / login is correct, display reset password form with hidden key / login values.
        if ( is_object( $user ) ) {
            add_filter('password_hint', function() {
                return __( 'Gợi ý: Mật khẩu phải có ít nhất 7 ký tự. Để nâng cao độ bảo mật, sử dụng chữ in hoa, in thường, chữ số và các ký tự đặc biệt như ! " ? $ % ^ & ).', 'hangcu' );
            });
            add_filter( 'woocommerce_min_password_strength', function() {
                return 2;
            } );
            
            get_header();
            wc_get_template(
                'myaccount/form-reset-password.php',
                array(
                    'key'   => $rp_key,
                    'login' => $rp_login,
                )
            );
            get_footer();
            die;
        }
    }
} else {
    // clear cookie
    setcookie(  
        'wp-postpass_' . COOKIEHASH, 
        '', 
        time() - YEAR_IN_SECONDS, 
        COOKIEPATH, 
        COOKIE_DOMAIN 
    );
}

if( !is_user_logged_in() ) {
    if( !wp_is_mobile() ) {
        // desktop sẽ chỉ dùng popup
        wp_redirect(home_url(), 301);
        die;
    } else {
        // include form login
        include_once('account/mb-not-login.php');
    }
} else {
    if( !wp_is_mobile() ) {
        get_header();

        // echo do_shortcode('[woocommerce_my_account]');
        $user = wp_get_current_user();
        if( isset( $user->user_pass ) ) {
            unset($user->user_pass);
        }
    
        // get phone number
    
    
        // echo do_shortcode('[woocommerce_my_account]');
        $phone = get_field('customer_mobile_phone', 'user_'. $user->ID);
        $user->data->phone_number = $phone;

        ?>
            <script>
                const userLogin = <?php echo json_encode( $user ) ?>;
                const shopUrl = '<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>';
            </script>
            <div id="account-page"></div>
        <?php    
            get_footer();
    } else {
        include_once('account/mb-account.php');
    }
}
    


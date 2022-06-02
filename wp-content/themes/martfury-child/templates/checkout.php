<?php 
/**
 * Template Name: Thanh toán
 */

    $checkout_step = 2; // with 1 is login
    require_once( 'checkout/lib/checkout-address-form.php' );

    if (isset($_GET['delete-address']) && !empty($_GET['delete-address'])) {
        thns_delete_address();
    }

    if ( isset( $_POST['shipping_account_address_action'] ) ) {
        // die;
        save_shipping_addresses();
    }

    $checkout_step = WC()->session->get('checkoutstep');

    if ( ! is_user_logged_in() ) {
        if ( isset( $wp->query_vars['order-received'] ) ) {
            wp_redirect(home_url());
        } else {
            include_once( 'checkout/header.php' );
            include_once( 'checkout/login.php' );
        }
    } else {
        $user_id = get_current_user_id();

        // if (!get_field('customer_mobile_phone', 'user_'.$user_id)) {
        //     include_once( 'checkout/lib/verify-phone-view.php' );
        // } else {
            if ( WC()->session->__isset( 'checkoutstep' ) && ! isset( $wp->query_vars['order-received'] ) ) {
                $checkout_step = WC()->session->get('checkoutstep');
            } else if ( isset( $wp->query_vars['order-received'] ) ) {
                WC()->session->__unset( 'checkoutstep' );
                $checkout_step = 4;
            }
            if ( !$checkout_step ) $checkout_step = 2;
    
            if ( isset( $_GET['step'] ) && $_GET['step'] === 'shipping' ) {
                WC()->session->set( 'checkoutstep', 2 );
                $checkout_step = 2;
            }
            include_once( 'checkout/header.php' );
            switch( $checkout_step ) {
                case 2:
                    include_once( 'checkout/addresses.php' );
                    break;
                case 3:
                    include_once( 'checkout/payment.php' );
                    break;
                case 4:
                    include_once( 'checkout/thankpage.php' );
                    break;
            }
        // }
    }
?>
</div>
<?php get_footer();
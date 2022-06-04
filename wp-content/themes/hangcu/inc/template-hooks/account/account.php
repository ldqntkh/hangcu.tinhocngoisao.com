<?php
add_action('wp_ajax_nopriv_login_account', 'customer_login_account' );
add_action('wp_ajax_nopriv_login_account_byid', 'customer_login_account_byid' );
add_action('wp_ajax_login_account_byid', 'customer_login_account_byid' );
add_action('wp_ajax_nopriv_register_social_account', 'customer_register_social_account' );
// add_action('wp_ajax_register_social_account', 'customer_register_social_account' );

add_action('wp_ajax_nopriv_register_account', 'customer_register_account' );
add_action('wp_ajax_nopriv_forgot_password', 'customer_forgot_password' );

// check user logged
add_action('wp_ajax_check_user_logged', 'customer_check_user_logged' );
add_action('wp_ajax_nopriv_check_user_logged', '__return_false' );

// add_action('get_header', 'check_user_login_has_phone_number');
// add_action('wp_login', 'check_user_login_has_phone_number');


add_filter( 'login_redirect', function( $redirect_to, $requested_redirect_to, $user ) {
  if ( in_array( 'subscriber', (array) $user->roles ) || in_array( 'customer', (array) $user->roles ) ) {
    if ( !get_field( 'customer_mobile_phone', 'user_'.$user->ID ) ) {
      return home_url(). '?t='.uniqid($user->ID . '_');
    }
  } else {
    return $redirect_to;
  }
  
}, 1, 3);

add_action( 'wp_footer', function () {
  // $user_id = get_current_user_id();
  ?>
  <script>
    const home_page = '<?= home_url() ?>';
    const login_account_url = '<?= wp_login_url() ?>';
  </script>
  <?php
    // if( $user_id <= 0 ) {
      
    // }
    if( wp_is_mobile() ) {
      $script_account = get_stylesheet_directory_uri(). '/assets/javascript/react-mb-account.js';
    } else {
      $script_account = get_stylesheet_directory_uri(). '/assets/javascript/react-account.js';
    }
    
    wp_enqueue_script('script_account', $script_account, array('jquery'), STYLE_VERSION, true);
} );
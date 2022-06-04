<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package electro
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); do_action('get_header');?>
</head>

<body <?php body_class(); ?>>
<?php
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
    <div id="account-page-mb"></div>

<?php get_footer(); do_action('wp_footer');?>

</body>
</html>
<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$args = array(
	'name'        => 'dat-lai-mat-khau',
	'post_type'   => 'post',
	'post_status' => 'private',
	'numberposts' => 1
);
$posts = get_posts($args);

$content = '';

if( $posts ) :
	$content = $posts[0]->post_content;
endif;

$content .= '<style>table.parent{margin: 0; padding: 0;background-color: #f2f2f2;width: 100%!important;font-family: Arial,Helvetica,sans-serif;font-size: 12px;color: #444;line-height: 18px;} a {text-decoration: none;color: #0275d8;} table.container{table-layout: fixed ;width: 100%; max-width: 600px; margin: 20px auto; color: #2d2727;background: white;} tr{width: 100%;} td{width: 25%;} ins{text-decoration: none; color: red;} a.product-title{font-weight: bold;}</style>';

$content = str_replace( '[user_login]', esc_html( $user_login ), $content );

if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) :
	$content = str_replace( '[account_action]', 'Mật khẩu của bạn đã được tạo tự động: <strong>' . esc_html( $user_pass ) . '</strong>', $content );
endif;

$content = str_replace( '[new_passwork_link]', esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ), $content );
$content = str_replace( '[reset_time]', esc_html( $reset_time ), $content );

echo $content;

<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-on-hold-order.php.
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

$address    = $order->get_formatted_billing_address();

$args = array(
    'name'        => 'don-hang-tam-giu',
    'post_type'   => 'post',
    'post_status' => 'private',
    'numberposts' => 1
  );
  $posts = get_posts($args);
  
  $content = '';
  
  if( $posts ) :
    $content = $posts[0]->post_content;
  endif;
  
  $content .= '<style>table.parent{margin: 0; padding: 0;background-color: #f2f2f2;width: 100%!important;font-family: Arial,Helvetica,sans-serif;font-size: 12px;color: #444;line-height: 18px;} a {text-decoration: none;color: #0275d8;} table.container{table-layout: fixed ;width: 100%; max-width: 600px; margin: 20px auto; color: #2d2727;background: white;} tr{width: 100%;} td{width: 25%;} ins{text-decoration: none; color: red;} a.product-title{font-weight: bold;} .order-line-items tr:nth-child(odd){background-color:#f7f7f7;}.order-line-items tr:nth-child(even){background-color:#d5d5d563;}</style>';

function get_bacs_account_details_html( $echo = true ) {

    ob_start();

    $gateway    = new WC_Gateway_BACS();
    $country    = WC()->countries->get_base_country();
    $locale     = $gateway->get_country_locale();
    $bacs_info  = get_option( 'woocommerce_bacs_accounts');

    // Get sortcode label in the $locale array and use appropriate one
    $sort_code_label = isset( $locale[ $country ]['sortcode']['label'] ) ? $locale[ $country ]['sortcode']['label'] : __( 'Sort code', 'woocommerce' );

    ?>
    <table style="width:100%" cellspacing="0">
        <tbody class="accounts">
            <?php
            $i = -1;
            if ( $bacs_info ) {
                foreach ( $bacs_info as $account ) {
                    $i++;
                    if ( empty($account['bank_name']) ) continue;
                    echo '<tr>
                            <td colspan="4" style="padding: 10px 0">
                                <strong>' .esc_attr( wp_unslash( $account['bank_name'] ) ). '</strong><br/>
                                <span>&nbsp;&nbsp;- Chủ tài khoản: ' .esc_attr( wp_unslash( $account['account_name'] ) ). '</span><br/>
                                <span>&nbsp;&nbsp;- ' .esc_attr( wp_unslash( $account['sort_code'] ) ). '</span><br/>
                                <span>&nbsp;&nbsp;- Số tài khoản: ' .esc_attr( $account['account_number'] ). '</span>
                            </td>
                        </tr>';
                }
            }
            ?>
        </tbody>
    </table>

    <?php

    $output = ob_get_clean();

    if ( $echo )
        echo $output;
    else
        return $output;
}


if ( isset( $content ) ) {

    $content = str_replace( '[order_id]', $order->get_id(), $content );

    $fullname = $order->get_billing_last_name();
    $content = str_replace( '[customer_name]', $fullname, $content );

    $bankdetails = get_bacs_account_details_html(false);
    $content = str_replace( '[order_bank]', $bankdetails, $content );

    $email = $order->get_billing_email();
    $content = str_replace( '[customer_email]', $email, $content );

    $phone = $order->get_billing_phone();
    $content = str_replace( '[customer_phone]', $phone, $content );

    $address = $order->get_address();
    $shipping = $address['address_1'] . ', ' . $address['address_2'] . ', ' . $address['city'] . ', ' . $address['state']; 
    // $shipping   = $order->get_formatted_shipping_address();
    $content = str_replace( '[order_address]', $shipping, $content );

    $payment_title = $order->get_payment_method_title();
    $content = str_replace( '[payment_method]', $payment_title, $content );

    $html_product = '';

    foreach( $order->get_items() as $item_id => $item ){
        $product = $item->get_product();
        // The quantity
        $quantity = $item->get_quantity();
    
        // The product name
        $product_name = $product->get_name();

        $price = $product->get_price_html();

        // if( $product->is_on_sale() ) {
        //     $price =    '<ins>'. wc_price($product->get_sale_price()) .'</ins><br>
        //                 <del>'. wc_price($product->get_regular_price()) .'</del>';
        // } else {
        //     $price =    '<ins>'. wc_price($product->get_regular_price()) .'</ins>';
        // }

        $total = $quantity * $product->get_price();
        $order_item_total = round( floatval($item->get_total()) );

        $html_price_order_item = '';
        if ( $total > $order_item_total ) {
            $html_price_order_item = '<ins>'. wc_price( $order_item_total ) .'</ins><br>
                        <del>'. wc_price( $total ) .'</del>';
        } else {
            $html_price_order_item = '<ins>'. wc_price( $total ) .'</ins>';
        }

        $html_product .= '<tr><td style="width: 30%; text-align: left; padding: 5px 0 5px 20px;">
                            <a class="product-title" href="'. $product->get_permalink() .'">'.$product_name.'</a>
                        </td>
                        <td style="width: 25%; text-align: center; padding: 5px;">
                            ' . $price . '
                        </td>
                        <td style="width: 20%; text-align: center; padding: 5px;">
                            '.$quantity.'
                        </td>
                        <td style="width: 20%; text-align: right; padding: 5px 20px 5px 0px;">
                            '. $html_price_order_item  .'
                        </td></tr>';
    }
    $html_product = '<table style="width:100%" cellspacing="0" cellpadding="0" class="order-line-items">' .$html_product. '</table>';

    $content = str_replace( '[product_line_items]', $html_product, $content );

    $sub_total = $order->get_subtotal();

    $order_total = $order->get_total();

    $ship_cost = $order->get_shipping_total();

    $content = str_replace( '[order_sub_total]', wc_price($sub_total), $content );

    $content = str_replace( '[ship_cost]', wc_price($ship_cost), $content );

    $content = str_replace( '[order_total]', wc_price($order_total), $content );

    $view_order_url = $order->get_view_order_url();

    $content = str_replace( '[order_link]', $view_order_url, $content );

    $date_created = $order->get_date_created()->date("d-m-Y");

    $time_created = $order->get_date_created()->date("H:i:s");

    $content = str_replace( '[date_created]', $date_created, $content );

    $content = str_replace( '[time_created]', $time_created, $content );
}

echo $content;
<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php 
if ( $order ) : ?>
    <div class="woocommerce-order">
        <div class="d-none">
            <?php
                do_action( 'woocommerce_before_thankyou', $order->get_id() );
                do_action( 'woocommerce_thankyou', $order->get_id() ); 

                $order = wc_get_order($order->get_id());
            ?>
        </div>

        <?php if ( $order->has_status( 'failed' ) ) : ?>
            <?php
                $content =  get_page_by_path('content-order-fail');
                $content = $content->post_content;
                $content = str_replace('[EMAIL_CUSTOMER]', $order->get_billing_email(), $content);
                echo str_replace('[ID_ORDER]', $order->get_id(), $content);
            ?>
        <?php else :
            $content =  get_page_by_path('content-thank-page');
            $content = $content->post_content;
            $content = str_replace('[EMAIL_CUSTOMER]', $order->get_billing_email(), $content);
            echo str_replace('[ID_ORDER]', $order->get_id(), $content);
        endif; ?>
    </div> 
    

<?php else : ?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>

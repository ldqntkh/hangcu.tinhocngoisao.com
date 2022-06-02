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

<div class="woocommerce-order">

    <?php if ( $order ) : ?>
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
    <?php else : ?>
        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <?php endif; ?>
</div>

<?php 

    function check_user_has_comment($user_email, $product_id) {
        global $wpdb;
        // Count the number of products
        $count = $wpdb->get_var( "
            SELECT COUNT(comment_ID) FROM {$wpdb->prefix}comments
            WHERE comment_post_ID = $product_id
            AND comment_author_email = '{$user_email}'
        " );
        return $count > 0 ? true : false;
    }

    function misha_populate_products_page() {
 
        global $wpdb;
        $purchased_products_ids = $wpdb->get_col( $wpdb->prepare(
            "
            SELECT      itemmeta.meta_value
            FROM        " . $wpdb->prefix . "woocommerce_order_itemmeta itemmeta
            INNER JOIN  " . $wpdb->prefix . "woocommerce_order_items items
                        ON itemmeta.order_item_id = items.order_item_id
            INNER JOIN  $wpdb->posts orders
                        ON orders.ID = items.order_id
            INNER JOIN  $wpdb->postmeta ordermeta
                        ON orders.ID = ordermeta.post_id
            WHERE       itemmeta.meta_key = '_product_id'
                        AND ordermeta.meta_key = '_customer_user'
                        AND ordermeta.meta_value = %s
            ORDER BY    orders.post_date DESC
            ",
            get_current_user_id()
        ) );
     
        $purchased_products_ids = array_unique( $purchased_products_ids );
        
        if( !empty( $purchased_products_ids ) ) :
            $purchased_products = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'post__in' => $purchased_products_ids,
                'orderby' => 'post__in'
            ) );
     
            woocommerce_product_loop_start();
            $current_user = wp_get_current_user();
            $customer_email = $current_user->user_email;
            while ( $purchased_products->have_posts() ) : $purchased_products->the_post();
                if ( !check_user_has_comment( $customer_email, get_the_ID() ) ) {
                    $_product_cmt = wc_get_product(get_the_ID());
                    break;
                }
            endwhile;
     
            woocommerce_product_loop_end();
     
            woocommerce_reset_loop();
            wp_reset_postdata();
            return $_product_cmt;
        else:
            return null;
        endif;
     
    }
    $_product_cmt = misha_populate_products_page();
?>

<div class="content-right">
    <?php if ( !empty($_product_cmt) ) : ?>
    <div class="product-cmt">
        <h4> <?php echo __('Bạn có hài lòng với sản phẩm đã mua?', 'gearvn') ?> </h4>
        <div class="product-content">
            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $_product_cmt->get_id() ), 'single-post-thumbnail' )[0] ?>" alt="" />
            <p>
                <strong><?php echo $_product_cmt->get_name() ?></strong>
                <a href="<?php echo get_permalink( $_product_cmt->get_id() ) . '#comments' ?>"><?php echo __('Viết nhận xét', 'gearvn') ?></a>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <?php 
        $fieldValuePromotion = get_option( CUSTOM_PREFERECE_ORDER )['section_1'];
        if ( !empty( $fieldValuePromotion ) ) : ?>
            <div class="promotions">
                <a href="<?php echo $fieldValuePromotion['url'] ?>">
                    <img src="<?php echo $fieldValuePromotion['image'] ?>" alt="" />
                </a>
            </div>
        <?php endif;
    ?>
</div>

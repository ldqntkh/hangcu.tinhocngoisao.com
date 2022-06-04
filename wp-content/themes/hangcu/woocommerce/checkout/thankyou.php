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
if ( $order ) :
    if( !wp_is_mobile() ) : ?>     
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
        <div class="mb-woocommerce-order">
            <div class="d-none">
                <?php
                    do_action( 'woocommerce_before_thankyou', $order->get_id() );
                    do_action( 'woocommerce_thankyou', $order->get_id() ); 
                    $order = wc_get_order($order->get_id());
                ?>
            </div>
            <div class="header-thanks">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 48C141.1 48 48 141.1 48 256s93.1 208 208 208 208-93.1 208-208S370.9 48 256 48zm106.5 150.5L228.8 332.8h-.1c-1.7 1.7-6.3 5.5-11.6 5.5-3.8 0-8.1-2.1-11.7-5.7l-56-56c-1.6-1.6-1.6-4.1 0-5.7l17.8-17.8c.8-.8 1.8-1.2 2.8-1.2 1 0 2 .4 2.8 1.2l44.4 44.4 122-122.9c.8-.8 1.8-1.2 2.8-1.2 1.1 0 2.1.4 2.8 1.2l17.5 18.1c1.8 1.7 1.8 4.2.2 5.8z"></path></svg>
                <h3><?= __("Cảm ơn ", 'hangcu') . $order->billing_last_name . '!' ?></h3>
                <p><strong><?php 
                    if( $order->get_status() != "failed") {
                        echo __("Đặt hàng thành công", 'hangcu');
                    } else {
                        echo __("Đặt hàng thất bại", 'hangcu');
                    }
                ?> </strong></p>
            </div>
            <?php 
                $payment_method = $order->get_payment_method(); 
                if( $payment_method == 'cod' ) : ?>
                    <div class="order-total-tk">
                        <p><?= __('Vui lòng chuẩn bị số tiền cần thanh toán', 'hangcu') ?></p>
                        <strong><?= wc_price($order->get_total()) ?></strong>
                    </div>
                <?php endif;
            ?>
            <div class="order-items">
                <div class="order-id">
                    <p><strong><?= __('Mã đơn hàng: ', 'hangcu') . $order->get_id() ?></strong></p>
                    <p><a href="<?= $order->get_view_order_url() ?>"><?= __('Xem đơn hàng', 'hangcu') ?></a></p>
                </div>
                <div class="order-products">
                    <?php 
                        foreach ($order->get_items() as $item_key => $item ):
                            $product      = $item->get_product(); 
                        
                            $product_id   = $item->get_product_id(); 
                            
                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
                            $name = $product->get_name();
                        ?>
                            <div class="product-item">
                                <img src="<?php  echo $image[0]; ?>" />
                                <p><?= $name ?></p>
                            </div>
                        <?php endforeach;
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php else : ?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>

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

    $_product_cmt = '';
    if( !wp_is_mobile() ) {
        $_product_cmt = misha_populate_products_page();
    }
?>

<div class="content-right">
    <?php if ( !empty($_product_cmt) ) : ?>
    <div class="product-cmt">
        <h4> <?php echo __('Bạn có hài lòng với sản phẩm đã mua?', 'hangcu') ?> </h4>
        <div class="product-content">
            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $_product_cmt->get_id() ), 'single-post-thumbnail' )[0] ?>" alt="" />
            <p>
                <strong><?php echo $_product_cmt->get_name() ?></strong>
                <a href="<?php echo get_permalink( $_product_cmt->get_id() ) . '#comments' ?>"><?php echo __('Viết nhận xét', 'hangcu') ?></a>
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

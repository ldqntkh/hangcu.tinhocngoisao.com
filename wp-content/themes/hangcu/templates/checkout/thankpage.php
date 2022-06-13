<?php
get_header();
$order = wc_get_order( $wp->query_vars['order-received'] );?>

<div class="checkout-thank w-100">
    <?php if ( $order ) :
            echo '<div class="d-none">';
            do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
            echo '</div>';
            wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) ); 
            do_action( 'woocommerce_after_update_order_data', $order );
        else :
            ?>
            <div class="text-center w-100">
                <h4><?php echo __('Đơn hàng không tồn tại', 'hangcu'); ?></h4>
                <?php if( !electro_detect_is_mobile() ) : ?>
                    <a href="/"><?php echo __('Quay về trang chủ', 'hangcu'); ?></a>
                <?php endif; ?>
            </div>
            <?php
    endif;?>
   
</div>
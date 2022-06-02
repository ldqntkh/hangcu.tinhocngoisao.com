<?php $order = wc_get_order( $wp->query_vars['order-received'] );?>

<div class="checkout-thank w-100">
    <?php
        if (!$order) {
            if ( !isset($wp->query_vars['order-received']) || $wp->query_vars['order-received'] == '' ) {
                if ( isset($_REQUEST['appid']) && isset($_REQUEST['checksum']) && isset($_REQUEST['apptransid']) && isset($_REQUEST['status']) ) {
                    $order_id = explode('-', $_REQUEST['apptransid'])[1];
                    if ($order_id) {
                        $order = wc_get_order($order_id);
                        if (!$order || $order->get_payment_method() != 'thns_zalo_payment') {
                            $order = null;
                        } else {
                            //TODO: Sẽ fix sau này. Lỗi create_at, trand_id lỗi sync order khi thanh toán bằng zalo
                            $date_time = new DateTime();
                            $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
                            $date_time->setTimezone($timezone);

                            update_post_meta($order_id, 'zalo_transaction_id', $_REQUEST['apptransid']);
                            update_post_meta($order_id, 'order_create_at', $date_time->format('Y-m-d H:i:s'));
                        }
                    }
                } else if (  isset($_REQUEST['partnerCode']) && isset($_REQUEST['accessKey']) 
                                && isset($_REQUEST['orderId']) && isset($_REQUEST['signature']) && isset($_REQUEST['message']) ) {
                    // momo payment
                    $order = wc_get_order($_REQUEST['orderId']);

                    if ($order->get_payment_method() != 'thns_momo_payment') {
                        $order = null;
                    }
                }
            } 
        }
    ?>
    <?php if ( $order ) :
        echo '<div class="d-none">';
        do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
        echo '</div>';
        wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) ); 
        else :
            ?>
            <div class="text-center w-100">
                <h4><?php echo __('Đơn hàng không tồn tại', 'thns'); ?></h4>
                <a href="/"><?php echo __('Quay về trang chủ', 'thns'); ?></a>
            </div>
            <?php
    endif;?>
</div>
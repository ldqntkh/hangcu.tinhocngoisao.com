<?php




?>
    <h2 class="title">Nhập thông tin <strong>email</strong> hoặc <strong>số điện thoại</strong> hoặc <strong>mã đơn hàng</strong> để xem chi tiết đơn hàng.</h2>
    <i class="title-note"><strong>Lưu ý:</strong> Email hoặc số điện thoại phải là thông tin được sử dụng để đặt hàng.</i>

    <?php // render form search ?>
    <form class="search-order" method="post">
        <div class="form-group">
            <label for="order_email">Email</label>
            <input type="email" class="form-control" name="order_email" 
                value="<?php if (isset($order_email)) echo $order_email; ?>"
                id="order_email" aria-describedby="emailHelp" placeholder="Email đặt hàng">
        </div>
        <div class="form-group">
            <label for="order_phone">Số điện thoại</label>
            <input type="text" class="form-control" 
                value="<?php if (isset($order_phone)) echo $order_phone; ?>"
                id="order_phone" name="order_phone" placeholder="Số điện thoại đặt hàng">
        </div>
        <div class="form-group">
            <label for="order_id">Mã đơn hàng</label>
            <input type="text" class="form-control" 
                value="<?php if (isset($order_id)) echo $order_id; ?>"
                id="order_id" name="order_id" placeholder="Mã đơn hàng">
        </div>
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>

    <div class="lst-orders">
        <?php if ( $orders ) : ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                            <th><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $orders as $order ) :
                        $item_count = $order->get_item_count();
                        ?>
                        <tr>
                            <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                                <td data-title="<?php echo esc_attr( $column_name ); ?>">
                                    <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                        <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                                    <?php elseif ( 'order-number' === $column_id ) : ?>
                                        <a href="<?php echo get_permalink( get_page_by_path( 'order-details' ) ) . '?order_id=' . $order->get_order_number() ?>"> <?php echo $order->get_order_number(); ?></a>
                                    <?php elseif ( 'order-date' === $column_id ) : ?>
                                        <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                                    <?php elseif ( 'order-status' === $column_id ) : ?>
                                        <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

                                    <?php elseif ( 'order-total' === $column_id ) : ?>
                                        <?php
                                            printf( _n( '%1$s', '%1$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total());
                                        ?>

                                    <?php elseif ( 'order-actions' === $column_id ) : ?>
                                        <a href="<?php echo get_permalink( get_page_by_path( 'order-details' ) ) . '?order_id=' . $order->get_order_number() ?>"> Chi tiết</a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>
        <?php endif; ?>
    </div>
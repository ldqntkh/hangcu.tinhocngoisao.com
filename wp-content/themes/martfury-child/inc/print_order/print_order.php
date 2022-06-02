<?php
add_action( 'submitpost_box', 'show_print_order', 15 );
function show_print_order() {
    $screen = get_current_screen();
    if ( $screen && $screen->id == 'shop_order' ) {
        $order = new WC_Order($_REQUEST['post'] );
        renderTemplateOrder($order);
        echo '<button id="print-order-details" type="button" style="padding: 10px 20px;
                                            margin: 0 10px 10px 0;
                                            background: #0085ba;
                                            color: white;
                                            border-radius: 4px;
                                            font-weight: 500;">In đơn hàng</button>';
    }
}

// function enqueue_admin_script_order() {
//     $screen = get_current_screen();
//     if ( $screen && $screen->id == 'shop_order' ) {
//         wp_enqueue_script( 'custom_script_print_order', get_stylesheet_directory_uri() . '/assets/js/admin/printorder.js', array(), '1.0' );
//     }
// }
// add_action( 'admin_enqueue_scripts', 'enqueue_admin_script_order' );

function renderTemplateOrder($order) {
    $page_slug ='print-order-content';
    $page_data = get_page_by_path($page_slug);
    $page_content = $page_data->post_content;
    $page_content_json = json_decode($page_content);

    $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );

    $shipping_method = @array_shift($order->get_shipping_methods());
    $shipping_method_id = $shipping_method['method_id'];
?>
    <section style="display:none">
    <div id="modal-priter-order" style="display: block;width: 1200px;left: 0;background: white;padding: 40px;">
    <?php
        $billing_detail = $order->get_formatted_billing_address();
        $billing_details = explode('<br/>', $billing_detail);
        $bAddress = '';
        $bAddress .= isset($billing_details[1]) ? $billing_details[1] : '';
        $bAddress .= ', ';
        $bAddress .= isset($billing_details[2]) ? $billing_details[2] : '';
        $bAddress .= ', ';
        $bAddress .= isset($billing_details[3]) ? $billing_details[3] : '';
        $bAddress .= ', ';
        $bAddress .= isset($billing_details[4]) ? $billing_details[4] : '';
    ?>
        <section class="order-header" style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
            <div class="content" style="width: 100%;">
                <p style="display: flex;flex-direction: column;justify-content: flex-start;align-items: flex-start;width:300px;float: right;">
                    <?php
                        foreach($page_content_json->header as $item) {
                            echo '<span>' . $item . '</span>';
                        }
                    ?>
                </p>
            </div>
            <div class="title" style="width:100%; margin: 0 20px;">
                <p class="p1" style="font-size:30px;text-align: center;text-transform: uppercase;font-weight: 900;margin: 10px;">Phiếu bán hàng</p>
                <p class="p2" style="text-align: center;font-size: 18px;margin: 0;">
                    <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'd-m-Y' ) ); ?></time>
                </p>
                <p class="p3" style='text-align: right;font-size: 15px;margin: 0;'>
                    Giờ in: {print_date}
                </p>
                <p class="p4" style="text-align: center;font-size: 20px;margin: 0;font-weight: 600;">
                    Mã đơn hàng: <?php echo $order->get_order_number(); ?>
                </p>
            </div>
        </section>

        <section class="woocommerce-order-details">
            <div class="order-customer">
                <p>
                    <span>Tên khách hàng: <strong><?php echo isset($billing_details[0]) ? $billing_details[0] : ''; ?></strong></span>
                </p>
                <p>
                    <span>Địa chỉ: <strong><?php echo $bAddress ?></strong></span>
                </p>
                <?php 
                    if ($shipping_method_id != 'local_pickup') {?>
                        <p>
                            <span>Địa chỉ giao hàng: <strong><?php echo $bAddress; ?></strong></span>
                        </p>
                    <?php }
                ?>
                
                <p>
                    <span>Số điện thoại: <strong><?php echo $order->get_billing_phone() ?></strong></span>
                </p>
                <p>
                    <span>Email: <strong><?php echo $order->get_billing_email() ?></strong></span>
                </p>
                <p>
                    <span>Ghi chú: <i>............................................................................................................................</i></span>
                </p>
                <p>
                    <span>Nhân viên bán hàng: <i>........................................................................................................</i></span>
                </p>
                <p>
                    <span>Người giao hàng: <i>.............................................................................................................</i></span>
                </p>
            </div>
            <table class="order_details" cellspacing="0" cellpadding="0" style="width: 100%; border: .5px solid;font-size:12px;">
                <thead>
                    <tr>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'STT', 'woocommerce' ); ?></th>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'Mã SP', 'woocommerce' ); ?></th>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'Product', 'woocommerce' ); ?></th>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'Price', 'woocommerce' ); ?></th>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'Số lượng', 'woocommerce' ); ?></th>
                        <th style="border: .5px solid; padding: 5px;"><?php _e( 'Total', 'woocommerce' ); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $index = 0;
                    foreach ( $order_items as $item_id => $item ) {
                        $product = $item->get_product();
                        $index++;
                        wc_get_template( 'order/order-details-item-printer.php', array(
                            'order'			     => $order,
                            'item_id'		     => $item_id,
                            'item'			     => $item,
                            'show_purchase_note' => $show_purchase_note,
                            'purchase_note'	     => $product ? $product->get_purchase_note() : '',
                            'product'	         => $product,
                            'index'              => $index
                        ) );
                    }
                    ?>
                </tbody>
            </table>
            <div class="table-footer" style="justify-content: flex-end;display: flex;flex-direction: column;align-items: flex-end;">
                <?php
                    foreach ( $order->get_order_item_totals() as $key => $total ) { ?>
                        <p style="width:400px; display:flex; padding: 5px;margin:0;">
                            <span style="min-width:150px;"><?php echo $total['label']; ?></span>
                            <strong style='width: 100%;text-align: right;'><?php echo $total['value']; ?></strong>
                        </p>
                    <?php }
                ?>
                <?php if ( $order->get_customer_note() ) : ?>
                    <tr>
                        <th><?php _e( 'Note:', 'woocommerce' ); ?></th>
                        <td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
                    </tr>
                <?php endif; ?>
            </div>
        </section>

        <section class="order-footer" style="display: flex;
                                            flex-direction: column;
                                            justify-content: center;
                                            align-items: center;">
            <div class="content" style="width: 100%;
                                        text-align: right;">
                <p style="display: flex;
                            flex-direction: column;
                            justify-content: flex-start;
                            align-items: flex-start;
                            width: 600px;
                            float: left;">
                    <?php
                        foreach($page_content_json->footer as $item) {
                            echo '<span>' . $item . '</span>';
                        }
                    ?>
                </p>
            </div>
        </section>
    </div>
    </section>
    <script type="text/javascript">
        jQuery(function() {
            jQuery('#print-order-details').on('click', function() {
                var date =  new Date();
                var custom_date = `${date.getDate()}/${date.getMonth()+1}/${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;

                var divToPrint=document.getElementById('modal-priter-order');

                var newWin=window.open('','Print-Window');
            
                newWin.document.open();
            
                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML.replace('{print_date}', custom_date)+'</body></html>');
            
                newWin.document.close();
            
                setTimeout(function(){newWin.close();},10);
            });
        });
    </script>
<?php }
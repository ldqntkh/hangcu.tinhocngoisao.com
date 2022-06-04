<?php 
function register_awaiting_cancel_order_status() {
    register_post_status( 'wc-pendingcancel', array(
        'label'                     => 'Yêu cầu hủy đơn hàng',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Yêu cầu hủy đơn hàng <span class="count">(%s)</span>', 'Yêu cầu hủy đơn hàng <span class="count">(%s)</span>' )
    ) );
}

function add_awaiting_cancel_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-pendingcancel'] = 'Yêu cầu hủy';
        }
    }
    return $new_order_statuses;
}


function hangcu_order_data_cancel() {
    $order_cancel_value =  get_option( 'custom_preferences_order' )['config_label_cancel_order'];
    if ( !empty( $order_cancel_value ) ) {
        $order_cancel_value = explode("\n", $order_cancel_value);
    }
    $admin_ajax_url = admin_url('admin-ajax.php');
    ?>
    <div class="modal fade modal-delete-address" id="modalCancelOrder" tabindex="-1" role="dialog" aria-labelledby="modalCancelOrder" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-body">
                <span class="icon-delete"></span>
                <h3 id="cancel-order-title" class="text-center d-block"><?php echo __('Hủy đơn hàng số 123?', 'hangcu'); ?></h3>
                <p class="error">
                    <span id="cancel-order-error"></span>
                </p>
                <div class="cancel-order-contents">
                    <div class="select-cancel-values">
                        <label for="select-cancel-value"><?php echo __("Chọn lý do hủy đơn *", 'hangcu') ?></label><br/>
                        <select id="select-cancel-value" required>
                            <option value=""><?php echo __("Lý do hủy đơn *", 'hangcu') ?></option>
                            <?php 
                                foreach( $order_cancel_value as $item ) : ?>
                                    <option value="<?php echo $item ?>"><?php echo $item ?></option>
                                <?php endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="input-cancel-notes">
                        <label for="input-cancel-note"><?php echo __("Nội dung hủy đơn hàng *", 'hangcu') ?></label>
                        <textarea id="input-cancel-note" required rows="4" cols="50" placeholder="<?php echo __("Nội dung hủy đơn hàng...", 'hangcu') ?>"></textarea>
                    </div>
                    <input type="hidden" id="order-cancel-id"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-sign-out-alt"></i>
                    <span><?php echo __('Thoát', 'hangcu'); ?></span>
                </button>
                <button type="button" class="btn btn-primary btn-delete-address" id="btn-cancel-order">
                    <i class="far fa-trash-alt"></i>
                    <span><?php echo __('Xác nhận', 'hangcu'); ?></span>
                </button>
            </div>
            </div>
        </div>
    </div>
    <script>
        const order_request_cancel_url = "<?php echo $admin_ajax_url ?>";
    </script>

<?php }

function wc_cancelled_order_add_customer_email( $recipient, $order ){
    return $recipient; // . ',' . $order->billing_email;
}

function hangcu_filter_woocommerce_email_classes( $emails ) {
    $emails['WC_Email_Customer_PendingCancel_Order']        = include get_stylesheet_directory() . '/inc/emails/order/class-wc-email-customer-pendingcancel-order.php';
    $emails['WC_Email_Admin_PendingCancel_Order']           = include get_stylesheet_directory() . '/inc/emails/order/class-wc-email-admin-pendingcancel-order.php';
    $emails['WC_Email_Cancelled_Order']                     = include get_stylesheet_directory() . '/inc/emails/order/class-wc-email-admin-cancelled-order.php';
    $emails['WC_Email_Customer_Cancelled_Order']            = include get_stylesheet_directory() . '/inc/emails/order/class-wc-email-customer-cancelled-order.php';
    $emails['WC_Email_Customer_Failed_Order']               = include get_stylesheet_directory() . '/inc/emails/order/class-wc-email-customer-failed-order.php';
    return $emails;
}

// define the woocommerce_email_actions callback 
function filter_woocommerce_email_actions( $array ) { 
    $array[] = 'woocommerce_order_status_processing_to_pendingcancel';
    $array[] = 'woocommerce_order_status_pendingcancel_to_cancelled';
    
    return $array; 
};


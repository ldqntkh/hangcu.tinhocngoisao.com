<?php
function custom_filter_order($post_type) {
    if ($post_type === "shop_order") {
?>
    <div style="float:left">
        <input type="hidden" name="range" value="custom" />
        <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['start_date'] ) ) ? esc_attr( wp_unslash( $_GET['start_date'] ) ) : ''; ?>" name="start_date" class="range_datepicker from" autocomplete="off" /><?php //@codingStandardsIgnoreLine ?>
        <span>&ndash;</span>
        <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['end_date'] ) ) ? esc_attr( wp_unslash( $_GET['end_date'] ) ) : ''; ?>" name="end_date" class="range_datepicker to" autocomplete="off" /><?php //@codingStandardsIgnoreLine ?>
        
    </div>
    <select id="order_status" name="order_status" class="wc-enhanced-select">
        <?php
            $statuses = wc_get_order_statuses();
            echo '<option value="">Trạng thái đơn hàng</option>';
            foreach ( $statuses as $status => $status_name ) {
                echo '<option value="' . esc_attr( $status ) . '" ' . selected( $status, $_GET['order_status'], false ) .'">' . esc_html( $status_name ) . '</option>';
            }
        ?>
    </select>
    <script>
        var dates = jQuery( '.range_datepicker' ).datepicker({
            changeMonth: true,
            changeYear: true,
            defaultDate: '',
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            minDate: '-20Y',
            maxDate: '+1D',
            showButtonPanel: true,
            showOn: 'focus',
            buttonImageOnly: true,
            onSelect: function() {
                var option = $( this ).is( '.from' ) ? 'minDate' : 'maxDate',
                    date   = $( this ).datepicker( 'getDate' );

                dates.not( this ).datepicker( 'option', option, date );
            }
        });
        
        jQuery( document ).ready(function() {
            var itemEle = jQuery('#posts-filter').find('table.wp-list-table');
            if( itemEle && itemEle.length > 0 ) {
                jQuery( itemEle ).prepend("<a href='#' id='print-list-orders' class='button'>In danh sách đơn hàng đã chọn</a>");
            }

            jQuery( 'body' ).on('click', '#print-list-orders', function() {
                var print_url = '<?php echo home_url(); ?>' + '/print-list-orders?order_ids=';
                var arrIds = [];
                jQuery('input[name="post[]"]').each(function(){
                    if( jQuery(this).is(":checked")) {
                        var ids = jQuery(this).attr('id').split('-');
                        arrIds.push( ids[ids.length-1] );
                    } 
                });
                if( arrIds.length > 0 ) {
                    print_url += arrIds.join(',');

                    var newWin=window.open(print_url,'Print-Window');
                }
            });
        });
    </script>
<?php
    }
}
add_action('restrict_manage_posts', 'custom_filter_order');


function wpa54142_feed_filter( $query ) {
    global $pagenow;
    if ( $query->is_admin && $pagenow == 'edit.php'  && $_GET['post_type'] == 'shop_order' && ( (isset( $_GET['start_date'] ) && $_GET['start_date'] != '' ) || isset($_GET['order_status'])  )) {
        add_filter( 'posts_where', 'wpa54142_filter_where' );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'wpa54142_feed_filter' );

function wpa54142_filter_where( $where = '' ) {
    if (isset($_GET['start_date']) && $_GET['start_date']) {
        $time_start = new DateTime($_GET['start_date']);
        $time_start->add(new DateInterval('PT00H00M00S'));
        $newformat_start = $time_start->format('Y-m-d H:i:s');

        $time_end = new DateTime();

        if ($_GET['end_date']) {
            $time_end = new DateTime($_GET['end_date']);
        }

        $time_end->add(new DateInterval('PT23H59M59S'));
        $newformat_end = $time_end->format('Y-m-d H:i:s');

        $where .= " AND post_date >= '$newformat_start' AND post_date <= '$newformat_end'";
    }

    if (isset($_GET['order_status']) && $_GET['order_status'] !== '') {
        $status = $_GET['order_status'];
        $where .= " AND post_status = '$status'";
    }

    return $where;
}
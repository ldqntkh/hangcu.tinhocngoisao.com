<?php 

/*
    Template Name: In danh sách đơn hàng
*/

$user = wp_get_current_user();

if ( $user->exists() && !empty($_REQUEST['order_ids']) ) {
    if ( current_user_can('read_shop_order')) { 
        $order_ids = explode( ',', $_REQUEST['order_ids'] );
        if ( count($order_ids) < 1 ) {
            wp_redirect(home_url());
            exit;
        } else { ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <style>
                body>section{display:block!important}
                body>section>div{width:initial!important}
                @media print {
                    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
                }
            </style>
            <script>

                $( document ).ready(function() {
                    var date =  new Date();
                    var custom_date = `${date.getDate()}/${date.getMonth()+1}/${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;

                    var content = $('body').html();
                    content = content.replaceAll('{print_date}', custom_date);
                    $('body').html(content);
                    window.print();
                });

            </script>
        <?php
            $index = 0;
            foreach( $order_ids as $order_id ) {
                $order = wc_get_order( $order_id );
                if( $order ) {
                    renderTemplateOrder($order);
                    $index++;
                    if ($index < count($order_ids)) {
                        echo '<div class="pagebreak"> </div>';
                    }
                }
            }
        }
    } else {
        wp_redirect(home_url());
        exit;
    }
} else {
    wp_redirect(home_url());
    exit;
}
<?php 

/*
    Template Name: Tracking Order
*/

    get_header();
    echo '<div class="tracking-order">';
    
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        include_once( 'tracking_order/search_order.php' );
    } else {
        $order_email = $_POST['order_email'];
        $order_phone = $_POST['order_phone'];
        $order_id = $_POST['order_id'];

        if ($order_email === "" && $order_phone === "" && $order_id === "") {
            header('Location: '.$_SERVER['REQUEST_URI']);
        } else {
            $arrQuery = array(
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $orders =  array();
            if ($order_id !== "") {
                $order = wc_get_order( $order_id );
                if ($order) {
                    array_push($orders, $order);
                }
            } else {
                if ($order_email !== "") {
                    $arrQuery['billing_email'] = $order_email;
                }
                if ($order_phone !== "") {
                    $arrQuery['billing_phone'] = $order_phone;
                }
                $query = new WC_Order_Query( $arrQuery );
                $orders = $query->get_orders();
            }
            include_once( 'tracking_order/search_order.php' );
        }
    }

    echo '</div>';

    get_footer();
<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminCalculateSaleAccessories {

    public static function init() {
        add_action( 'woocommerce_order_before_calculate_totals', array( 'AdminCalculateSaleAccessories', 'add_custom_price'), 10, 2 );
    }

    public static function add_custom_price( $and_taxes, $order ) {
        $order_id = $order->get_id();
        if ( empty( $order_id ) || $order_id == 0 ) return;
        // get date of order
        $original_order_id = get_field('original_order_id', $order_id );
        if ( empty( $original_order_id ) ) $original_order_id = $order_id;
        $_order = wc_get_order( $original_order_id );
        $date_create_order = null;
        if ( !empty( $_order ) ) {
            $date_create_order = $_order->get_date_created();
        }
        //if ( empty( $date_create_order ) ) $date_create_order = date("Y-m-d H:m:s");
        $date_time = new DateTime();
        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $date_time->setTimezone($timezone);

        $_date_create = empty( $date_create_order ) ? $date_time->format('Y-m-d') : wc_format_datetime( $date_create_order, 'Y-m-d' );
        $_date_time_create = empty( $date_create_order ) ? $date_time->format('Y-m-d H:i:s') : wc_format_datetime( $date_create_order, 'Y-m-d H:m:s' );

        $campaign_class = new GEARVNSaleAccessoriesCampaign();
        foreach( $order->get_items() as $item_id => $item ){

            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();

            $_se_group_values = $campaign_class->getInfoCampaignByProductIdAndDate( $product_id, $_date_create, $_date_time_create );

            if ( !empty($_se_group_values) ) {
                $list_count_discount = [];

                foreach( $_se_group_values as $_se_group_value ) {
                    $list_count_discount[$product_id][$_se_group_value['se_name']] = $quantity;

                    // sort list cart item by product price
                    $_order_items = AdminCalculateSaleAccessories::sort_item_in_order( $order->get_items() );
                    
                    foreach( $_order_items as $sub_item_id => $sub_item ){
                        // -----------------------------------
                        if( $list_count_discount[$product_id][$_se_group_value['se_name']] < 1 ) {
                            break;
                        }
                        $total_sub_product_discount_in_group = 0;
                        $total_sub_product_discount_in_group = isset( $data_discounted[$product_id][$_se_group_value['se_name']] ) ?
                                                    $data_discounted[$product_id][$_se_group_value['se_name']] : 0;
                        // -----------------------------------

                        $sub_product_id = $sub_item->get_product_id();
                        $group_products = $_se_group_value['products'];
                        if ( in_array( $sub_product_id, $group_products )  ) {
                            $sub_quantity = $sub_item->get_quantity();
                            if ( $list_count_discount[$product_id][$_se_group_value['se_name']] >= $sub_quantity ) {
                                // tính giá
                                $sub_product = wc_get_product(  $sub_product_id );
                                if ( $_se_group_value['se_type'] == 'price' ) {
                                    $discount = ( intval( $_se_group_value['se_down'] ) * $sub_quantity );
                                } else {
                                    $discount = ( ( $sub_product->get_price() / 100 * intval( $_se_group_value['se_down'] ) ) * $sub_quantity );
                                }
                                $data_discount[$sub_product_id]['total_product_discounted'] = $sub_quantity;
                                $data_discount[$sub_product_id][$product_id] = [
                                    'quantity'              => $quantity,
                                    'type'                  => $_se_group_value['se_type'],
                                    'down'                  => $_se_group_value['se_down'],
                                    'quantity_discount'     => $sub_quantity
                                ];
                                $list_count_discount[$product_id][$_se_group_value['se_name']] = $list_count_discount[$product_id][$_se_group_value['se_name']] - $sub_quantity;

                                $sale_accessories_grouped_product[$product_id][$sub_product_id] = $sub_quantity;

                            } else {
                                $total_has_disount = $list_count_discount[$product_id][$_se_group_value['se_name']];
                                
                                // tính giá
                                $sub_product = wc_get_product(  $sub_product_id );
                                if ( $_se_group_value['se_type'] == 'price' ) {
                                    $discount = ( intval( $_se_group_value['se_down'] ) * $total_has_disount );
                                } else {
                                    $discount = ( ( $sub_product->get_price() / 100 * intval( $_se_group_value['se_down'] ) ) * $total_has_disount );
                                }
                                
                                $data_discount[$sub_product_id]['total_product_discounted'] = $total_has_disount;
                                $list_count_discount[$product_id][$_se_group_value['se_name']] = 0;
                                $data_discount[$sub_product_id][$product_id] = [
                                    'quantity'              => $quantity,
                                    'type'                  => $_se_group_value['se_type'],
                                    'down'                  => $_se_group_value['se_down'],
                                    'quantity_discount'     => $total_has_disount
                                ];

                                $sale_accessories_grouped_product[$product_id][$sub_product_id] = $total_has_disount;
                            }
                        }
                    }
                }
            }
        }

        // re calculate to get high discount price
        foreach( $data_discount as $key => $data ) {
            $_product = wc_get_product( $key );
            if ( $_product ) {
                $sort_result = AdminCalculateSaleAccessories::calculator_high_discount_price( $_product->get_price(), $data );

                $data_discount[$key] = $sort_result;
            }
        }

        foreach( $order->get_items() as $item_id => $line_item ){
            $product_id = $line_item->get_product_id();
            $quantity = $line_item->get_quantity();
            $total_price = $line_item->get_product()->get_price() * $quantity;
            if ( !empty($data_discount[$product_id]) ) {
                $items = $data_discount[$product_id];
                $total_product_discount = $line_item->get_quantity();
                $total_product_discounted = 0;
                $total = 0;

                foreach( $items as $key => $item ) {
                    if ( $key == 'total_product_discounted' ) continue;
                        
                    if ( $total_product_discounted >= $total_product_discount ) break;

                    $count = $total_product_discount - $total_product_discounted;
                    if ( $item['quantity_discount'] >= $count ) {
                        $price_discount = $count * $item['discount_price'];

                        if ( $total_price * $total_product_discount -  $price_discount <= 0 ) {
                            $line_item->set_total( 0 );
                        } else {
                            $line_item->set_total( ( $total_price * $total_product_discount -  $price_discount * $total_product_discount ) /$total_product_discount );
                        }

                        $data_discount[$product_id][$key]['quantity_discount'] = $count;
                        $total_product_discounted += $count;

                    } else {
                        $price_discount = $item['quantity_discount'] * $item['discount_price'];
                        if (  $total_price * $total_product_discount -  $price_discount <= 0 ) {
                            $line_item->set_total( 0 );
                        } else {
                            $line_item->set_total( (  $total_price * $total_product_discount -  $price_discount * $total_product_discount ) /$total_product_discount );
                        }

                        $data_discount[$product_id][$key]['quantity_discount'] = $item['quantity_discount'];
                        $total_product_discounted += $item['quantity_discount'];
                    }
                }
            } else {
                $line_item->set_total( $total_price );
            }
        }

    }

    private static function calculator_high_discount_price( $price, $discount_data ) {
        
        $result = [];
        foreach( $discount_data as $key => $item ) {
            if ( $key == 'total_product_discounted' ) {
                // $result[$key] = $item;
                continue;
            } else {
                if ( $item['type'] == 'price' ) {
                    $discount = intval( $item['down'] );
                } else {
                    $discount = $price / 100 * intval( $item['down'] ) ;
                }
                $discount_data[$key]['discount_price'] = $discount;

                $result[$key] = $discount;
            }
        }
       
        arsort( $result );
        
        $_result = [];
        foreach( $result as $key => $item ) {
            $_result[$key] = $discount_data[$key];
        }

        $_result['total_product_discounted'] = $discount_data['total_product_discounted'];
        
        return $_result;
    }

    private function sort_item_in_order ( $order_items ) {

        $products_in_order = array();
        // Assign each product's price to its cart item key (to be used again later)
        foreach ( $order_items as $key => $item ) {
            $products_in_order[ $key ] = $item->get_product( )->get_price();
        }
    
        // SORTING - use one or the other two following lines:
        // asort( $products_in_order ); // sort low to high
        arsort( $products_in_order ); // sort high to low
    
        // Put sorted items back in cart
        $order_contents = array();
        foreach ( $products_in_order as $_key => $price ) {
           $order_contents[ $_key ] = $order_items[ $_key ];
        }
    
        return $order_contents;
    }

}

AdminCalculateSaleAccessories::init();

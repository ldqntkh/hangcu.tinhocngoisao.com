<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CalculateSaleAccessories {

    public static function init() {
        add_action( 'woocommerce_before_calculate_totals', array( 'CalculateSaleAccessories', 'add_custom_price') );

        // define the woocommerce_cart_item_removed callback 
        add_action( 'woocommerce_remove_cart_item', array( 'CalculateSaleAccessories', 'action_woocommerce_cart_item_removed'), 10, 2 ); 

        // display message name in cart item
        add_filter( 'woocommerce_cart_item_name', array( 'CalculateSaleAccessories', 'filter_woocommerce_cart_item_name'), 10, 3 ); 

        // display message name in cart item of payment
        add_action( 'woocommerce_render_sale_accessories', array( 'CalculateSaleAccessories', 'render_payment_sale_accessories'), 10, 1 );

        add_filter( 'woocommerce_cart_item_price', array( 'CalculateSaleAccessories', 'filter_woocommerce_cart_item_price'), 10, 3 ); 

        // save data sale accessories to order
        add_action( 'woocommerce_checkout_order_processed', array( 'CalculateSaleAccessories', 'save_data_sale_accessories'), 10, 3 );

        // display sale item data in admin order item
        add_action( 'woocommerce_after_order_itemmeta',array( 'CalculateSaleAccessories', 'display_sale_accessories_item_admin_order'), 10, 3 );

        // display product regular in admin order item
        add_filter( 'woocommerce_order_amount_item_total', array( 'CalculateSaleAccessories', 'filter_woocommerce_order_amount_item_total'), 10, 5 ); 
    }

    // set new price accessories before calculate cart
    public static function add_custom_price( $cart_object ) {
        //check if product already in cart
        $cart_items = WC()->cart->get_cart();
        if ( sizeof( $cart_items ) > 0 ) {
            // get session in discount cart
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            if ( empty( $_SESSION[ 'sale_accessories_data' ] ) ) $data_discount = [];
            else $data_discount = (array)json_decode( $_SESSION[ 'sale_accessories_data' ] , true);

            $sale_accessories_grouped_product = [];
            
            $data_discount = [];
            $data_discounted = [];
            // error here
            foreach ( $cart_items as $cart_item_key => $values ) {
                $_product = $values['data'];
                $product_id = $_product->get_id();
                // $_se_group_values = get_post_meta($product_id , '_se_group_values', true);
                $campaign_class = new GEARVNSaleAccessoriesCampaign();
                $_se_group_values = $campaign_class->getInfoCampaignByProductId( $product_id );
                $quantity = $values['quantity'];
                
                if ( !empty($_se_group_values) ) {
                    // $_se_group_values = json_decode(base64_decode( $_se_group_values ), true);
                    $list_count_discount = [];

                    if ( !empty($_se_group_values) && count($_se_group_values) > 0 ) {
                        foreach( $_se_group_values as $_se_group_value ) {
                            $list_count_discount[$product_id][$_se_group_value['se_name']] = $quantity;
                            // sort list cart item by product price
                            $_cart_items = CalculateSaleAccessories::sort_item_in_cart( $cart_items );
                            foreach ( $_cart_items as $key => $sub_values ) {
                                // -----------------------------------
                                if( $list_count_discount[$product_id][$_se_group_value['se_name']] < 1 ) {
                                    break;
                                }

                                $total_sub_product_discount_in_group = 0;
                                $total_sub_product_discount_in_group = isset( $data_discounted[$product_id][$_se_group_value['se_name']] ) ?
                                                            $data_discounted[$product_id][$_se_group_value['se_name']] : 0;
                                // -----------------------------------

                                $sub_product_id = $sub_values['data']->get_id();
                                $group_products = $_se_group_value['products'];
                                if ( in_array( $sub_product_id, $group_products )  ) {
                                    $sub_quantity = $sub_values['quantity'];
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
            }
            
            // re calculate to get high discount price
            foreach( $data_discount as $key => $data ) {
                $_product = wc_get_product( $key );
                if ( $_product ) {
                    $sort_result = CalculateSaleAccessories::calculator_high_discount_price( $_product->get_price(), $data );

                    $data_discount[$key] = $sort_result;
                }
            }
            
            foreach ( $cart_items as $cart_item_key => $values ) { 
                $_product = $values['data'];
                $product_id = $_product->get_id();
                if ( !empty($data_discount[$product_id]) ) {
                    $items = $data_discount[$product_id];
                    $total_product_discount = $values['quantity'];
                    $total_product_discounted = 0;
                    $total = 0;
                    foreach( $items as $key => $item ) {
                        if ( $key == 'total_product_discounted' ) continue;
                        
                        if ( $total_product_discounted >= $total_product_discount ) break;

                        $count = $total_product_discount - $total_product_discounted;
                        if ( $item['quantity_discount'] >= $count ) {
                            
                            $price_discount = $count * $item['discount_price'];
                            if ( $values['data']->get_price() * $total_product_discount -  $price_discount <= 0 ) {
                                $values['data']->set_price( 0 );
                            } else {
                                $values['data']->set_price( ( $values['data']->get_price() *$total_product_discount -  $price_discount) /$total_product_discount );
                            }

                            $data_discount[$product_id][$key]['quantity_discount'] = $count;
                            $total_product_discounted += $count;
                        } else {

                            $price_discount = $item['quantity_discount'] * $item['discount_price'];
                            if ( $values['data']->get_price() * $total_product_discount -  $price_discount <= 0 ) {
                                $values['data']->set_price( 0 );
                            } else {
                                $values['data']->set_price( ( $values['data']->get_price() *$total_product_discount -  $price_discount) /$total_product_discount );
                            }

                            $data_discount[$product_id][$key]['quantity_discount'] = $item['quantity_discount'];
                            $total_product_discounted += $item['quantity_discount'];
                        }
                    }
                }
            }
            
            unset( $_SESSION[ 'sale_accessories_data' ] );
            unset( $_SESSION[ 'sale_accessories_grouped_product' ] );
            $_SESSION['sale_accessories_grouped_product'] = json_encode( $sale_accessories_grouped_product );
            $_SESSION[ 'sale_accessories_data'] = json_encode( $data_discount );
        } 
    }

    private function sort_item_in_cart ( $cartObject ) {

        $products_in_cart = array();
        // Assign each product's price to its cart item key (to be used again later)
        foreach ( $cartObject as $key => $item ) {
            $product = wc_get_product( $item['product_id'] );
            $products_in_cart[ $key ] = $product->get_price();
        }
    
        // SORTING - use one or the other two following lines:
        // asort( $products_in_cart ); // sort low to high
        arsort( $products_in_cart ); // sort high to low
    
        // Put sorted items back in cart
        $cart_contents = array();
        foreach ( $products_in_cart as $cart_key => $price ) {
           $cart_contents[ $cart_key ] = WC()->cart->cart_contents[ $cart_key ];
        }
    
        return $cart_contents;
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

    // delete sale data after remove item in cart
    public static function action_woocommerce_cart_item_removed( $cart_item_key, $instance ) { 
        $cart_items = WC()->cart->get_cart();
        if ( sizeof( $cart_items ) > 0 ) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $product_id = $cart_items[ $cart_item_key ]['data']->get_id(); 
    
            if ( empty( $_SESSION[ 'sale_accessories_data' ] ) ) $data_discount = [];
            else $data_discount = (array)json_decode( $_SESSION[ 'sale_accessories_data' ] , true);
            
            if ( !empty( $data_discount[ $product_id ] ) ) {
                unset( $data_discount[ $product_id ] );
                unset( $_SESSION[ 'sale_accessories_data' ] );
                $_SESSION[ 'sale_accessories_data'] = json_encode( $data_discount );
            } else {
                // check sản phẩm giảm giá để xóa sản phẩm đó đi
                // $_se_group_values = get_post_meta($product_id , '_se_group_values', true);
                $campaign_class = new GEARVNSaleAccessoriesCampaign();
                $_se_group_values = $campaign_class->getInfoCampaignByProductId( $product_id );
                if ( !empty($_se_group_values) ) {
                    // $_se_group_values = json_decode(base64_decode( $_se_group_values ), true);
                    
                    if ( !empty($_se_group_values) && count($_se_group_values) > 0 ) {
                        foreach( $_se_group_values as $_se_group_value ) {
                            $product_ids = $_se_group_value['products'];
                            foreach( $product_ids as $pr_id ) {
                                if ( !empty( $data_discount[ $pr_id ] ) ) {
                                    if ( !empty( $data_discount[$pr_id][$product_id] ) ) {
                                        $discounted = $data_discount[$pr_id][$product_id]['quantity_discount'];
                                        $data_discount[ $pr_id ]['total_product_discounted'] =  $data_discount[ $pr_id ]['total_product_discounted'] - $discounted;
                                        unset( $data_discount[$pr_id][$product_id] );
                                    }
                                }
                            }
                        }
                        unset( $_SESSION[ 'sale_accessories_data' ] );
                        $_SESSION[ 'sale_accessories_data'] = json_encode( $data_discount );
                    }
                }
            }
        }
    }

    // display message name in cart item
    public static function filter_woocommerce_cart_item_name( $array, $cart_item, $cart_item_key ) { 
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $product_id = $cart_item['product_id'];

        if ( empty( $_SESSION[ 'sale_accessories_data' ] ) ) $data_discount = [];
        else $data_discount = (array)json_decode( $_SESSION[ 'sale_accessories_data' ] , true);

        if ( !empty( $data_discount[$product_id] ) ) {
            $product = wc_get_product( $product_id );
            $array .= '<ul class="discount-sale-accessories" style="font-size: 12px; padding-left: 20px;margin-top: 10px;">';
            foreach( $data_discount[$product_id] as $key => $item ) {
                if ( $item['quantity_discount'] > 0 ) {
                    $parent_product = wc_get_product( $key );
                    $discount_value = 0;
                    if ( $item['type'] == 'price' ) {
                        $discount_value = $item['quantity_discount'] * $item['down'];
                    } else {
                        $product = wc_get_product( $product_id );
                        $discount_value = $item['quantity_discount'] * ( $product->get_price() / 100 * $item['down'] );
                    }

                    $discount_value = wc_price( $discount_value );

                    $array .= sprintf( '<li><span style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                                    Giảm %s trên tổng tiền khi mua kèm với <strong>%s</strong> x %s</span></li>', $discount_value, $item['quantity_discount'], $parent_product->get_name() );
                }
            }
            $array .= '</ul>';
        }

        return $array; 
    }

    // display message name in cart item of payment
    public static function render_payment_sale_accessories($product_id) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $array = '';
        if ( empty( $_SESSION[ 'sale_accessories_data' ] ) ) $data_discount = [];
        else $data_discount = (array)json_decode( $_SESSION[ 'sale_accessories_data' ] , true);

        if ( !empty( $data_discount[$product_id] ) ) {
            $product = wc_get_product( $product_id );
            $array .= '<ul class="discount-sale-accessories" style="font-size: 12px; padding-left: 20px;margin-top: 10px;">';
            foreach( $data_discount[$product_id] as $key => $item ) {
                if ( $item['quantity_discount'] > 0 ) {
                    $parent_product = wc_get_product( $key );
                    $discount_value = 0;
                    if ( $item['type'] == 'price' ) {
                        $discount_value = $item['quantity_discount'] * $item['down'];
                    } else {
                        $product = wc_get_product( $product_id );
                        $discount_value = $item['quantity_discount'] * ( $product->get_price() / 100 * $item['down'] );
                    }

                    $discount_value = wc_price( $discount_value );

                    $array .= sprintf( '<li><span style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">Giảm %s trên tổng tiền khi mua kèm với <strong>%s</strong> x %s</span></li>', $discount_value, $item['quantity_discount'], $parent_product->get_name() );
                }
            }
            $array .= '</ul>';
        }
        
        echo $array; 
    }

    public static function filter_woocommerce_cart_item_price( $wc, $cart_item, $cart_item_key ) { 
        $product_id = $cart_item['product_id'];
        $product = wc_get_product( $product_id );
        return wc_price( $product->get_price() );
    }

    public static function save_data_sale_accessories( $order_id, $posted_data, $order ) {
        if ( empty( $_SESSION[ 'sale_accessories_data' ] ) ) $data_discount = [];
        else $data_discount = (array)json_decode( $_SESSION[ 'sale_accessories_data' ] , true);
        if ( count( $data_discount ) > 0 ) {
            foreach( $data_discount as $key => $item ) {
                foreach( $item as $_key => $_item ) {
                    if ( $_key == 'total_product_discounted' ) continue;

                    $product = wc_get_product( $_key );
                    if ( $product ) {
                        $data_discount[$key][$_key]['product_name'] = $product->get_name();
                        $data_discount[$key][$_key]['product_price'] = $product->get_price();
                    }
                }
            }
        }

        update_post_meta( $order_id, 'sale_accessories_data', json_encode($data_discount) );
        unset( $_SESSION[ 'sale_accessories_data' ] );
    }

    public static function display_sale_accessories_item_admin_order( $item_id, $item, $product ) {
        if ( $product && $item && $item->get_order_id() ) {
            $order_id = $item->get_order_id();
            $sale_accessories_data = get_post_meta( $order_id, 'sale_accessories_data', true );
            if ( $sale_accessories_data ) {
                $sale_accessories_data = json_decode( $sale_accessories_data , true);
                $item_data = $sale_accessories_data[ $product->get_id() ];
                if ( $item_data ) {
                    $html = '<ul class="admin-order-names" style="margin:0">';
                    foreach( $item_data as $key => $item ) {
                        $discount = $item['discount_price'] * $item['quantity_discount'];
                        if ( $discount == 0 ) continue;
                        $discount = wc_price( $discount );
                        $html .= '<li>' . sprintf( 'Giảm <strong>%s</strong> trên tổng tiền khi mua kèm với <strong>%s</strong> x %s', $discount, $item['quantity_discount'], $item['product_name'] ) . '</li>';
                    }
                    $html .= '</ul>';

                    echo $html;
                }
            }
        }
    }

    public static function filter_woocommerce_order_amount_item_total( $total, $instance, $item, $inc_tax, $round ) {
        if ( $item->get_order_id() && $item->get_product_id() ) {
            $order_id = $item->get_order_id();
            $sale_accessories_data = get_post_meta( $order_id, 'sale_accessories_data', true );
            if ( $sale_accessories_data ) {
                $sale_accessories_data = json_decode( $sale_accessories_data , true);
                $item_data = $sale_accessories_data[ $item->get_product_id() ];
                if ( $item_data ) {
                    $product = wc_get_product( $item->get_product_id() );

                    if ( $product ) return $product->get_price();
                }
            }
        }

        return $total;
        
    }
}

CalculateSaleAccessories::init();
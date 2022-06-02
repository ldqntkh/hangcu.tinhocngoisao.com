<?php
include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/helpers/class-calculate-accessories-price.php';
class CheckSaleProductAccessories {

    public static function checkProductAccessoriesInCart( $check_product_id, $quantity_added ) {
        $result = false;
        $cart_items = WC()->cart->get_cart();
        if ( sizeof( $cart_items ) > 0 ) {
            // get session in discount cart
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

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
                            $group_products = $_se_group_value['products'];

                            if ( !in_array( $check_product_id, $group_products ) ) continue;

                            foreach ( $cart_items as $sub_cart_item_key => $sub_values ) {
                                $sub_product = $sub_values['data'];
                                $sub_product_id = $sub_product->get_id();
                                $sub_quantity = $sub_values['quantity'];
                                if ( $sub_product_id != $check_product_id ) continue;
                                if ( $sub_quantity >= $quantity ) break;

                                if ( $sub_quantity + $quantity_added <= $quantity ) return true;
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
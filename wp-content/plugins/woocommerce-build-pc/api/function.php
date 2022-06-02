<?php 

/**
 * request param
 * custom_type : string
 * ex: wp-json/rest_api/v1/get_products_by_custom_type?custom_type=type
 */
/**
 * product return 
 *  - id
 *  - link
 *  - name
 *  - sku
 *  - regular-price
 *  - sale-price
 *  - image
 *  - attributes (n)
 *      - name
 *      - value
 *          - id
 *          - name
 *          - slug
 */
function get_products_by_custom_type(WP_REST_Request $request) {

    $custom_type = isset($_GET['custom_type']) ? $_GET['custom_type'] : false;

    if ($custom_type == false) {
        return array(
            "success" => false,
            "errMsg" => "Can not find param",
            "data" => null 
        );
    } else {
        $transient_key = 'get_products_by_custom_type'. 'build-pc-'. $custom_type;
        $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
        $current_time = $current_time->format('Y-m-d');
        // $cache_result = get_cache_by_key('get_products_by_custom_type', 'build-pc-'. $custom_type .'.txt');
        $cache_result = get_transient ( $transient_key );
        if ($cache_result) {
            // $cache_time = $cache_result['time'];
            // if ($cache_time) {
            //     $date_1 = strtotime($cache_time);
            //     $date_2 = strtotime($current_time);
            //     $datediff = $date_2 - $date_1;
            //     $day = round($datediff / (60 * 60 * 24));
            //     if ($day < 1) {
                    
            //         return array(
            //             "success" => true,
            //             "errMsg" => "",
            //             "data" => $cache_result['data'] 
            //         );
            //     }
            // }
            return array(
                "success" => true,
                "errMsg" => "",
                "data" => $cache_result['data'] 
            );
        }

        $query_args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'posts_per_page'        => -1,
            'meta_key'              => '_buildpc-type',
            'meta_value'            => $custom_type,
        );
        $loop = new WP_Query( $query_args );
        $arrProducts = [];

        $valid_cdn = false;
        if ( function_exists( 'check_valid_cdn_buildpc' ) ) {
            $valid_cdn = check_valid_cdn_buildpc();
        }

        while ( $loop->have_posts() ) : $loop->the_post(); 
            global $product;
            if ( $product->get_manage_stock() && $product->get_stock_quantity() != null && $product->get_stock_status() ) {
                if ($product->get_type() === 'variable') {
                    $regular_price = $product->get_variation_regular_price();
                    $sale_price = $product->get_variation_sale_price();
                } else {
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                }

                if ( !$product->is_on_sale() ) {
                    $sale_price = "0";
                }
                $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium', true )[0];
                if ( $valid_cdn ) {
                    $image_url = str_replace( get_home_url(), $valid_cdn, $image_url );
                }
                $arrPt = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'link' => get_permalink( $product->get_id() ),
                    'regular_price' => $regular_price,
                    'sale_price' => $sale_price,
                    'image' => $image_url,
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                    'selected_product_value' => get_post_meta($product->get_id(), '_selected_product_value', true)
                );

                $arrPt['attributes'] = get_product_attributes( $product );
                $arrPt['manage_stock'] = true;
                $arrPt['stock_quantity'] = $product->get_stock_quantity();
                if ($product->get_type() === 'variable') {
                    $productsChildId = $product->get_visible_children();
                    if ( count( $productsChildId ) > 0 ) {
                        $arrPt['product_childs'] = [];
                        
                        foreach( $productsChildId as $productChildId ) {
                            $productChild = wc_get_product( $productChildId );
                            $child_image_url = wp_get_attachment_image_src( $productChild->get_image_id(), 'thumbnail', true )[0];
                            if ( $valid_cdn ) {
                                $child_image_url = str_replace( get_home_url(), $valid_cdn, $child_image_url );
                            }
                            $arrPtChild = array(
                                'id' => $productChild->get_id(),
                                'name' => $productChild->get_name(),
                                'link' => get_permalink( $productChild->get_id()),
                                'regular_price' => $productChild->get_regular_price(),
                                'sale_price' => $productChild->get_sale_price(),
                                'image' => $child_image_url,
                                'average_rating' => $productChild->get_average_rating(),
                                'review_count' => $productChild->get_review_count(),
                                'stock_quantity' =>  $productChild->get_stock_quantity(),
                                'attributes' => get_product_child_attribute_name( $productChildId, array_keys( $productChild->get_attributes() )[0] )
                            );
                            array_push( $arrPt['product_childs'], $arrPtChild );
                        }
                    }
                }
                array_push($arrProducts, $arrPt);
            }
        endwhile;

        // set_cache_by_key('get_products_by_custom_type', array("time" => $current_time, "data" => $arrProducts), 'build-pc' .$custom_type. '.txt');
        wp_reset_query();
        $output = array(
            'data' => $arrProducts 
        );
        set_transient( $transient_key, $output, 60*60 );
        return array(
            "success" => true,
            "errMsg" => "",
            "data" => $arrProducts 
        );
    }
}

function get_product_child_attribute_name( $productId, $attributeName ) {
    $meta = get_post_meta($productId, 'attribute_'. $attributeName, true);
    $term = get_term_by('slug', $meta, $attributeName);
    return $term;
}

function get_product_attributes( $product ) {
    $product_attributes = $product->get_attributes();
    $attributes = [];

    if (count($product_attributes)) {
        foreach($product_attributes as $product_attribute) {
            $get_terms_args = array( 'hide_empty' => '1' );
            $terms = get_terms( $product_attribute['name'], $get_terms_args );
            $index = 0;

            foreach($terms as $term) {
                $options = $product_attribute->get_options();
                $options = ! empty( $options ) ? $options : array();
                if (wc_selected( $term->term_id, $options ) === "") {
                    unset($terms[$index]);
                }
                $index++;
            }
            if (count($terms)) {
                $arrAttr = array(
                    "name" => $product_attribute['name'],
                    "full_name" => wc_attribute_label($product_attribute['name'], $product),
                    "values" => $terms
                );
                array_push( $attributes, $arrAttr);
            }
        }
    }
    return $attributes;
}
// insert multiple products to cart
/**
 * ex: wp-json/rest_api/v1/insert_multiple_products_to_cart?product_data_add_to_cart=<product_id>_<quantity>,<product_id>_<quantity>....
 */
// có lỗi khi nó là product variation
function insert_multiple_products_to_cart(WP_REST_Request $request) {
    try {
        $product_data_add_to_cart = explode( ',', $_REQUEST['product_data_add_to_cart'] );
        defined( 'WC_ABSPATH' ) || exit;

        // Load cart functions which are loaded only on the front-end.
        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/class-wc-cart.php';

        if ( is_null( WC()->cart ) ) {
            wc_load_cart();
        }
        
        foreach ( $product_data_add_to_cart as $product_data ) {

            // control product quantity
            $data = explode('_', $product_data);
            $product_id = $data[0];
            $_quantity = count($data) === 2 ? $data[1] : 1;
            $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
            $was_added_to_cart = false;
            $adding_to_cart    = wc_get_product( $product_id );
        
            if ( ! $adding_to_cart ) {
                continue;
            }
        
            $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );
        
            /*
            * Sorry.. if you want non-simple products, you're on your own.
            *
            * Related: WooCommerce has set the following methods as private:
            * WC_Form_Handler::add_to_cart_handler_variable(),
            * WC_Form_Handler::add_to_cart_handler_grouped(),
            * WC_Form_Handler::add_to_cart_handler_simple()
            *
            * Why you gotta be like that WooCommerce?
            */

            // For now, quantity applies to all products.. This could be changed easily enough, but I didn't need this feature.
            $quantity          = apply_filters( 'woocommerce_stock_amount', $_quantity );
            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

            if ( $passed_validation ) {
                $was_added_to_cart = false;
                if ( 'variable' === $add_to_cart_handler || 'variation' === $add_to_cart_handler ) {
                    if ( $adding_to_cart->is_type( 'variation' ) ) {
                        $variation_id   = $product_id;
                        $product_id     = $adding_to_cart->get_parent_id();
                    } else {
                        $adding_to_cart = wc_get_product( $adding_to_cart->get_visible_children()[0] );
                        $variation_id = $adding_to_cart->get_id();
                    }
                    $was_added_to_cart = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $adding_to_cart->get_variation_attributes() );
                } else {
                   $was_added_to_cart =  WC()->cart->add_to_cart( $product_id, $quantity );
                }
            }
        }
        return array(
            "success" => true,
            "erMsg" => ""
        );
    } catch(Exception $e) {
        return array(
            "success" => false,
            "erMsg" => $e
        );
    }
}

// register api get_products_primetime_price
add_action( 'rest_api_init', function () {
    register_rest_route( 'rest_api/v1', '/get_products_by_custom_type', array(
        'methods' => 'GET',
        'callback' => 'get_products_by_custom_type',
    ) );

    register_rest_route( 'rest_api/v1', '/insert_multiple_products_to_cart', array(
        'methods' => 'GET',
        'callback' => 'insert_multiple_products_to_cart',
    ) );
} );

?>
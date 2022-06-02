<?php
/**
 * Plugin name: HTSOFT Đồng bộ
 * Author: Quang Le
 */

// register api route
if( !function_exists('hts_get_all_products') ) {
    add_action( 'rest_api_init', function () {
        register_rest_route( 'rest_api/v1', '/hts_get_all_products', array(
            'methods' => 'GET',
            'callback' => 'hts_get_all_products',
        ) );
    } );
    
    function hts_get_all_products( WP_REST_Request $request ) {
        
        $query_args = array(
            'posts_per_page' => 200,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => 1
        );
        $arrResult = [];
        $query_products = new WP_Query( $query_args );
        if ($query_products->have_posts()) :
            while ( $query_products->have_posts() ) : $query_products->the_post();
                // Do Stuff
                global $product;
                // $regular_price = $product->get_regular_price();
                // $sale_price = $product->get_sale_price();
                $arrPt = array(
                    'id' => $product->get_id(),
                    // 'regular_price' => $regular_price,
                    // 'sale_price' => $sale_price,
                    'price' => $product->get_price(),
                    'hts_alias'  => get_field('alias_htsoft', $product->get_id())
                    // 'hts_alias' => $product->get_id() == '70162' ? "187994" : ''
                );

                array_push($arrResult, $arrPt);
            endwhile;
        endif;
        wp_reset_postdata();
        wp_send_json_success([
            "data_product"=> $arrResult
        ]);
    }
}

if( !function_exists('hts_update_product_info') ) {

    add_action( 'rest_api_init', function () {
        register_rest_route( 'rest_api/v1', '/product/update/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => 'hts_update_product_info',
        ) );
    } );
    

    function hts_update_product_info( WP_REST_Request $request ) {
        $product_id = $request->get_param( 'id' );
        
        $parameters = $request->get_json_params();
        $price = $parameters['price'];
        // $qty = $parameters['qty'];
        
        // $sku = $parameters['sku'];
        // $is_active = $parameters['is_active'];
        // $name = $parameters['name'];
        // $existed_product = get_post_meta( $product_id );
        
        
        $qty_key = '_stock';
        $price_key = '_price';
        $regular_price_key = '_regular_price';
        $sale_price_key = '_sale_price';
        $sku_key = '_sku';
        $status_key = '_status';

        $response = array(
            'status' => true,
            'message'=> 'Update success',
            'id'    => $product_id,
            '$parameters'   => $parameters
        );

        // if ( empty( $existed_product ) ) {
        //     return wp_send_json(
        //         array(
        //             'status' => false,
        //             'message' => 'Can not find any product with productID: ' . $product_id
        //         )
        //     );
        //     die;
        // }

        $product = wc_get_product( $product_id );
        if( empty($product) ) {
            return wp_send_json(
                array(
                    'status' => false,
                    'message' => 'Can not find any product with productID: ' . $product_id
                )
            );
            die;
        }
        // check variant here
        /*
        if( !empty($product) && $product->is_type('variable') ) {
            foreach( $product->get_available_variations() as $variation_values ){
                $variation_sku = $variation_values['sku']; // variation id
                
                if( $variation_sku != $sku ) {
                    return wp_send_json(array(
                        'status' => false,
                        'message' => 'Can not find variant product with alias: ' . $alias . ' and SKU: ' . $sku
                    ));
                    die;
                }
                $variation_id = $variation_values['variation_id']; // variation id

                if ( isset($qty) && $qty >= 0 ) {
                    $updated_qty = update_post_meta( $variation_id, $qty_key, $qty );
                    wc_update_product_stock( $variation_id,  $qty , 'set' );
                    // if( $qty > 0 ) {
                    //     update_post_meta( $variation_id, $status_key, 'instock' );
                    // }
                    if (!$updated_qty ) {
                        $response[] = array(
                            'status' => false,
                            'message'=> 'Can not update qty in web api: '. $qty 
                        );
                    } else {
                        $response[] = array(
                            'status' => true,
                            'message'=> 'Update qty success'
                        );
                    }
                }
                if( isset($qty) )
                    wc_update_product_stock( $variation_id,  $qty , 'set' );
                
                if ( !empty( $price ) ) {
                    // get giá sale và giá gốc
                    $sale_price = $variation_values['display_price'];

                    if ( $sale_price && $sale_price > 0 ) {
                        if( $price <= $sale_price ) {
                            $updated_sale_price = update_post_meta( $variation_id, $sale_price_key, $price );
                            
                            $updated_price = update_post_meta( $variation_id, $price_key, $price );
                        } else {
                            delete_post_meta( $variation_id, $sale_price_key );
                            $updated_price = update_post_meta( $variation_id, $regular_price_key, $price );
                            $updated_price = update_post_meta( $variation_id, $price_key, $price );
                        }
                    } else {
                        $updated_regular_price = update_post_meta( $variation_id, $regular_price_key, $price );
                        $updated_price = update_post_meta( $variation_id, $price_key, $price );
                    }
                    // hiện tại chỉ update giá bán gốc
                    // $updated_price = update_post_meta( $alias, $regular_price_key, $price );
                    if ( !$updated_price ) {
                        $response[] = array(
                            'status' => false,
                            'message'=> 'Can not update price in web api: ' . $price
                        );
                    }else {
                        $response[] = array(
                            'status' => true,
                            'message'=> 'Update price success'
                        );
                    }
                }
                // wc_delete_product_transients( $variation_id ); // Clear/refresh the variation cache
                // wc_delete_product_transients( $alias );
                

                // Updating active price and regular price
                // update_post_meta( $variation_id, '_regular_price', $regular_price );
                // update_post_meta( $variation_id, '_price', $regular_price );
                // 
            }
            return wp_send_json($response);
            die;
        }
        */

        // if ( isset($qty) && $qty >= 0 ) {
        //     $updated_qty = update_post_meta( $product_id, $qty_key, $qty );
        //     if (!$updated_qty ) {
        //         $response[] = array(
        //             'status' => false,
        //             'message'=> 'Can not update qty in web api: '. $qty 
        //         );
        //     } else {
        //         $response[] = array(
        //             'status' => true,
        //             'message'=> 'Update qty success'
        //         );
        //     }
        // }
        // if( isset($qty) ) {
        //     $product->set_stock_quantity($qty);
        //     if( $qty == 0 ) {
        //         $product->set_stock_status('outofstock');
        //     } else {
        //         $product->set_stock_status();
        //     }
        // }
        
        if ( !empty( $price ) ) {
            // get giá sale và giá gốc
            $sale_price = get_post_meta( $product_id, $sale_price_key, true );
            if ( $sale_price && $sale_price > 0 ) {
                if( $price <= $sale_price ) {
                    $updated_sale_price = update_post_meta( $product_id, $sale_price_key, $price );
                    // $updated_regular_price = update_post_meta( $product_id, $regular_price_key, $price );
                    $updated_price = update_post_meta( $product_id, $price_key, $price );
                } else {
                    $updated_price = update_post_meta( $product_id, $regular_price_key, $price );
                    $updated_price = update_post_meta( $product_id, $price_key, $sale_price );
                }
            } else {
                $updated_regular_price = update_post_meta( $product_id, $regular_price_key, $price );
                $updated_price = update_post_meta( $product_id, $price_key, $price );
            }
            // hiện tại chỉ update giá bán gốc
            // $updated_price = update_post_meta( $alias, $regular_price_key, $price );
            if ( !$updated_price ) {
                $response[] = array(
                    'status' => false,
                    'message'=> 'Can not update price in web api: ' . $price
                );
            }else {
                $response[] = array(
                    'status' => true,
                    'message'=> 'Update price success'
                );
            }
        }

        // if ( isset($sku) && $sku >= 0 ) {
        //     $updated_sku = update_post_meta( $alias, $sku_key, $sku );
        //     if (!$updated_sku ) {
        //         $response[] = array(
        //             'status' => false,
        //             'message'=> 'Can not update sku in web api: '. $sku 
        //         );
        //     } else {
        //         $response[] = array(
        //             'status' => true,
        //             'message'=> 'Update sku success'
        //         );
        //     }
        // }

        
        // if ( !empty($product) && isset($is_active) && is_bool($is_active) ) {
        //     if( $product->get_status() == 'draft' || $product->get_status() == 'publish' ) {
        //         $status = 'publish';
            
        //         if($is_active == false) $status = 'draft';
                
        //         $product->set_status($status); 
                
        //         $response[] = array(
        //             'status' => true,
        //             'message'=> 'Update status success'
        //         );
        //     } else {
        //         $response[] = array(
        //             'status' => false,
        //             'message'=> 'Update status false'
        //         );
        //     }
            
        // }

        // if ( isset($name) ) {
        //     $product->set_name($name);
            
        //     $response[] = array(
        //         'status' => true,
        //         'message'=> 'Update title success'
        //     );
            
        // }
        $product->save();
        wc_delete_product_transients( $product_id );
        return wp_send_json($response);
        die;
    }
}

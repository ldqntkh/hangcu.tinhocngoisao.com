<?php
/**
 * get_products_by_categoryid?advanced_option=recent&product_cat=15&product_tag=1652&post_number=10&start_number=0&orderby=date&order=DESC&get_slug=1
 */
if (!function_exists('get_products_by_categoryid')) :
    function get_products_by_categoryid(WP_REST_Request $request) {
        $wc_advanced_option = esc_attr( $_GET[ 'advanced_option' ] );
        $online_shop_wc_product_cat = esc_attr( $_GET['product_cat'] );
        $online_shop_wc_product_tag = esc_attr( $_GET['product_tag'] );
        
        $post_number = absint( $_GET[ 'post_number' ] );
        $start_page = $_GET[ 'start_page' ] ? absint( $_GET[ 'start_page' ] ) : 0;
        $post_number = absint( $_GET[ 'post_number' ] );
        $orderby = esc_attr( $_GET[ 'orderby' ] );
        $order = esc_attr( $_GET[ 'order' ] );
        $get_slug = $_GET[ 'get_slug' ] ? absint( $_GET[ 'get_slug' ] ) : 0;

        $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
        $current_time = $current_time->format('Y-m-d');
        $filenamecache = 'get_products_by_categoryid-'.$wc_advanced_option.$online_shop_wc_product_cat.$online_shop_wc_product_tag.$post_number.$start_page.$orderby.$order.$get_slug.'.txt';
        // $cache_result = get_cache_by_key('get_products_by_categoryid', $filenamecache);
        $cache_result = null;
        if ($cache_result) {
            $cache_time = $cache_result['time'];
            if ($cache_time) {
                $date_1 = strtotime($cache_time);
                $date_2 = strtotime($current_time);
                $datediff = $date_2 - $date_1;
                $day = round($datediff / (60 * 60 * 24));
                if ($day < 1) {
                    $result = array(
                        "status" => "OK",
                        "errMsg" => "",
                        "data" => $cache_result['data']
                    );
                    return $result;
                }
            }
        }

        $product_visibility_term_ids = wc_get_product_visibility_term_ids();

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 1.0.0
         *
         * @see WP_Query
         *
         */
        $query_args = array(
            'posts_per_page' => $post_number,
            'offset'         => ($start_page - 1) * $post_number,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => 1,
            'order'          => $order,
            'meta_query'     => array(),
            'tax_query'      => array(
                'relation' => 'AND',
            ),
        );

        switch ( $wc_advanced_option ) {

            case 'featured' :
                if( !empty( $product_visibility_term_ids['featured'] )){
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['featured'],
                    );
                }

                break;

            case 'onsale' :
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                if( !empty( $product_ids_on_sale ) ){
                    $query_args['post__in'] = $product_ids_on_sale;
                }
                break;

            case 'cat' :
                if( !empty( $online_shop_wc_product_cat )){
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $online_shop_wc_product_cat,
                    );
                }

                break;

            case 'tag' :
                print_r( $online_shop_wc_product_tag );
                if( !empty( $online_shop_wc_product_tag )){
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_tag',
                        'field'    => 'term_id',
                        'terms'    => $online_shop_wc_product_tag,
                    );
                }

                break;
        }

        switch ( $orderby ) {

            case 'price' :
                $query_args['meta_key'] = '_price';
                $query_args['orderby']  = 'meta_value_num';
                break;

            case 'sales' :
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby']  = 'meta_value_num';
                break;

            case 'ID' :
            case 'author' :
            case 'title' :
            case 'date' :
            case 'modified' :
            case 'rand' :
            case 'comment_count' :
            case 'menu_order' :
                $query_args['orderby']  = $orderby;
                break;

            default :
                $query_args['orderby']  = 'date';
        }

        $online_shop_featured_query = new WP_Query( $query_args );
        if ($online_shop_featured_query->have_posts()) :
            $products = array();
            while ( $online_shop_featured_query->have_posts() ) : $online_shop_featured_query->the_post();
                // Do Stuff
                global $product;
                array_push($products, getProductInfo($product, $get_slug));
            endwhile;
            $result = array(
                "status" => "OK",
                "errMsg" => "",
                "data" => $products
            );
            wp_reset_postdata();
            // set_cache_by_key('get_products_by_categoryid'
            //                     , array("time" => $current_time, "data" => $products),
            //                     $filenamecache);
            return $result;
        endif;
        
        return array(
            "status" => "FAIL",
            "errMsg" => "Products not found",
            "data" => null
        );
    }
endif;

/**
 * get_products_by_productids?productids=1,2,3,4
 */
if (!function_exists('get_products_by_productids')) :
    function get_products_by_productids(WP_REST_Request $request) {
        $productIds = esc_attr( $_GET[ 'productids' ] );
        if (!$productIds || strlen($productIds) == 0) {
            return array(
                "status" => "FAIL",
                "errMsg" => "Products not found",
                "data" => null
            );
        } else {
            $products = array();
            $productIds = explode(',', $productIds);

            $key = implode("-",$productIds);

            $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
            $current_time = $current_time->format('Y-m-d');
            
            //$cache_result = get_cache_by_key('get_products_by_productids', 'get_products_by_productids-'.$key.'.txt');
            $cache_result = null;
            if ($cache_result) {
                $cache_time = $cache_result['time'];
                if ($cache_time) {
                    $date_1 = strtotime($cache_time);
                    $date_2 = strtotime($current_time);
                    $datediff = $date_2 - $date_1;
                    $day = round($datediff / (60 * 60 * 24));
                    if ($day < 1) {
                        $result = array(
                            "status" => "OK",
                            "errMsg" => "",
                            "data" => $cache_result['data']
                        );
                        return $result;
                    }
                }
            }

            foreach ($productIds as $productId) {
                if (!empty($productId)){
                    $product = wc_get_product( $productId );
                    if (!$product) {
                        continue;
                    }
                    array_push($products, getProductInfo($product, 0));
                }
            }
            $result = array(
                "status" => "OK",
                "errMsg" => "",
                "data" => $products
            );
            wp_reset_postdata();
            // set_cache_by_key('get_products_by_productids'
            //                     , array("time" => $current_time, "data" => $products),
            //                     'get_products_by_productids-'.$key.'.txt');
            return $result;
        }
    }
endif;

if (!function_exists('getProductInfo')) :
    function getProductInfo($product, $get_slug, $image_type = 'medium') {
        if ($product->get_type() === 'variable') {
            $regular_price = $product->get_variation_regular_price();
            $sale_price = $product->get_variation_sale_price();
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
        }

        $image_link = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), $image_type, true )[0];
        if ( function_exists( 'check_valid_cdn' ) ) {
            
            $valid_cdn =  check_valid_cdn();

            if ( $valid_cdn ) {
                $image_link = str_replace( get_home_url(), $valid_cdn, $image_link );
            }
        }
        
        $arrPt = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'link' => get_permalink( $product->get_id()),
            'regular_price' => number_format((float)$regular_price, 0, '.', ','),
            'sale_price' => number_format((float)$sale_price, 0, '.', ','),
            'image' => $image_link,
            'average_rating' => $product->get_average_rating(),
            'review_count' => $product->get_review_count()
        );

        $arrPt['manage_stock'] = $product->get_manage_stock();
        $arrPt['stock_quantity'] = $product->get_stock_quantity();
        $arrPt['stock_status'] = $product->get_stock_status();
        // $arrPt['sale_end_time'] = $productMgr->getDiscountTimeRemaining($product->get_id());
        $period = get_post_meta( $product->get_id(), 'warranty_period', true );
        // if (empty($period)) {
        //     $period = 36;
        // }
        $arrPt['period'] = $period;
        if ($get_slug == 1) {
            $terms = get_the_terms( $product->get_id(), 'product_cat' );
            $slugs = [];
            if (count($terms) > 0) {
                foreach($terms as $item) {
                    array_push($slugs, $item->slug);
                }
            }
            $arrPt['slugs'] = $slugs;
        }
        
        return $arrPt;
    }
endif;

if ( !function_exists( 'get_products_sales' ) ) {
    // function get_products_sales( WP_REST_Request $request ) {
        
    //     $post_number = absint( $_GET[ 'post_number' ] );
    //     $post_number = 100;
    //     $start_page = $_GET[ 'start_page' ] ? absint( $_GET[ 'start_page' ] ) : 0;

    //     $orderby = esc_attr( $_GET[ 'orderby' ] );
    //     $order = esc_attr( $_GET[ 'order' ] );
    //     $get_slug = $_GET[ 'get_slug' ] ? absint( $_GET[ 'get_slug' ] ) : 0;

    //     $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
    //     $current_time = $current_time->format('Y-m-d');
    //     $filenamecache = 'get_products_sales-'.$post_number.$start_page.$orderby.$order.$get_slug.'.txt';
    //     $cache_result = get_cache_by_key('get_products_sales', $filenamecache);
        
    //     if ($cache_result) {
    //         $cache_time = $cache_result['time'];
    //         if ($cache_time) {
    //             // $date_1 = strtotime($cache_time);
    //             // $date_2 = strtotime($current_time);
    //             // $datediff = $date_2 - $date_1;
    //             // $day = round($datediff / (60 * 60 * 24));
    //             // if ($day < 1) {
    //             //     $result = array(
    //             //         "status" => "OK",
    //             //         "errMsg" => "",
    //             //         "data" => $cache_result['data']
    //             //     );
    //             //     return $result;
    //             // }

    //             $result = array(
    //                 "status" => "OK",
    //                 "errMsg" => "",
    //                 "data" => $cache_result['data']
    //             );
    //             return $result;
    //         }
    //     }

    //     return array(
    //         "status" => "FAIL",
    //         "errMsg" => "Products not found",
    //         "data" => null
    //     );
    // }

    function get_products_sales( WP_REST_Request $request ) {
        $start_page = $_GET[ 'start_page' ] ? absint( $_GET[ 'start_page' ] ) : 1;

        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://provider.tinhocngoisao.com:8080/public/v1/products/get-product-cache?start_page='.$start_page,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return array(
                "status" => "OK",
                "errMsg" => "",
                "data" => $response
            );
        } catch ( Exception $e ) {
            return array(
                "status" => "FAIL",
                "errMsg" => "Products not found",
                "data" => null
            );
        }
    }
}


function wc_get_product_id_by_variation_sku($sku) {
    $args = array(
        'post_type'  => 'product_variation',
        'meta_query' => array(
            array(
                'key'   => '_sku',
                'value' => $sku,
            )
        )
    );
    // Get the posts for the sku
    $posts = get_posts( $args);
    if ($posts) {
        return $posts[0]->post_parent;
    } else {
        return false;
    }
}

if ( !function_exists( 'update_product_info' ) ) {
    function update_product_info( WP_REST_Request $request ) {
        $alias = $request->get_param( 'id' );
        $parameters = $request->get_json_params();
        $qty = $parameters['qty'];
        $price = $parameters['price'];
        $sku = $parameters['sku'];
        $is_active = $parameters['is_active'];
        $name = $parameters['name'];
        $existed_product = get_post_meta( $alias );
        
        
        $qty_key = '_stock';
        $price_key = '_price';
        $regular_price_key = '_regular_price';
        $sale_price_key = '_sale_price';
        $sku_key = '_sku';
        $status_key = '_status';

        $response = array(
            'status' => true,
            'message'=> 'Update success'
        );

        if ( empty( $existed_product ) ) {
            return wp_send_json(
                array(
                    'status' => false,
                    'message' => 'Can not find any product with alias: ' . $alias
                )
            );
            die;
        }

        $product = wc_get_product( $alias );
        if( empty($product) ) {
            return wp_send_json(
                array(
                    'status' => false,
                    'message' => 'Can not find any product with alias: ' . $alias
                )
            );
            die;
        }
        // check variant here
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


        if ( isset($qty) && $qty >= 0 ) {
            $updated_qty = update_post_meta( $alias, $qty_key, $qty );
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
        if( isset($qty) ) {
            $product->set_stock_quantity($qty);
            if( $qty == 0 ) {
                $product->set_stock_status('outofstock');
            } else {
                $product->set_stock_status();
            }
        }
        
        if ( !empty( $price ) ) {
            // get giá sale và giá gốc
            $sale_price = get_post_meta( $alias, $sale_price_key, true );
            if ( $sale_price && $sale_price > 0 ) {
                if( $price <= $sale_price ) {
                    $updated_sale_price = update_post_meta( $alias, $sale_price_key, $price );
                    // $updated_regular_price = update_post_meta( $alias, $regular_price_key, $price );
                    $updated_price = update_post_meta( $alias, $price_key, $price );
                } else {
                    $updated_price = update_post_meta( $alias, $regular_price_key, $price );
                    $updated_price = update_post_meta( $alias, $price_key, $sale_price );
                }
            } else {
                $updated_regular_price = update_post_meta( $alias, $regular_price_key, $price );
                $updated_price = update_post_meta( $alias, $price_key, $price );
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

        if ( isset($sku) && $sku >= 0 ) {
            $updated_sku = update_post_meta( $alias, $sku_key, $sku );
            if (!$updated_sku ) {
                $response[] = array(
                    'status' => false,
                    'message'=> 'Can not update sku in web api: '. $sku 
                );
            } else {
                $response[] = array(
                    'status' => true,
                    'message'=> 'Update sku success'
                );
            }
        }

        
        if ( !empty($product) && isset($is_active) && is_bool($is_active) ) {
            if( $product->get_status() == 'draft' || $product->get_status() == 'publish' ) {
                $status = 'publish';
            
                if($is_active == false) $status = 'draft';
                
                $product->set_status($status); 
                
                $response[] = array(
                    'status' => true,
                    'message'=> 'Update status success'
                );
            } else {
                $response[] = array(
                    'status' => false,
                    'message'=> 'Update status false'
                );
            }
            
        }

        // if ( isset($name) ) {
        //     $product->set_name($name);
            
        //     $response[] = array(
        //         'status' => true,
        //         'message'=> 'Update title success'
        //     );
            
        // }
        $product->save();
        wc_delete_product_transients( $alias );
        return wp_send_json($response);
        die;
    }
}



if ( !function_exists( 'insert_product_info' ) ) {
    function insert_product_info( WP_REST_Request $request ) {
        // $alias = $request->get_param( 'id' );
        $parameters = $request->get_json_params();
        $qty = $parameters['qty'];
        $price = $parameters['price'];
        $sku = $parameters['sku'];
        $is_active = $parameters['is_active'];
        $name = $parameters['name'];
        
        $qty_key = '_stock';
        $price_key = '_price';
        $regular_price_key = '_regular_price';
        $sale_price_key = '_sale_price';
        $sku_key = '_sku';

        // check sku
        global $wpdb;

        $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

        if ( $product_id ) {
            return wp_send_json(array(
                'status' => false,
                'message'=> 'Product already exists'
            ));
            die;
        }

        $status = 'pending';
        // if($is_active) $status = 'public';
        // insert post

        // $insert = $wpdb->insert($wpdb->prefix.'posts', array(
        //     'post_author' => '1',
        //     'post_date' => date(),
        //     'post_title' => $name,
        //     'post_status' => 'private',
        //     'post_type' => "product",
        //     'comment_status' => "open"
        // ));
        $term = get_category_by_slug('uncategorized');
        // wp_set_object_terms( $alias, $term->term_id, 'product_cat' );

        $id = wp_insert_post( [
            'post_title'            => $name,
            'post_status'           => $status,
            'post_type'             => 'product',
            'tax_input'     => array(
                'custom_tax_category' => array( $term->term_id )
            )
        ] );
        
        // wp_set_post_categories( $id );

        if ( $insert->last_error ) {
            return wp_send_json(array(
                'status' => false,
                'message'=> 'Can not update status in web api: '. json_encode([
                    'post_title'            => $name,
                    'post_status'           => $status,
                    'post_type'             => 'product'
                ])
            ));
            die;
        } else {
            // $id = $insert = $wpdb->insert_id;
            $response = array(
                'status' => true,
                'message'=> 'Update success'
            );
            $alias = $id;
            wp_set_object_terms( $alias, 'simple', 'product_type');
            
            $product = wc_get_product( $alias );
            // $product->set_sku( $sku );
            $product->set_manage_stock( true );
            // $product->set_stock_quantity($qty);
            // $product->set_price( $price )
            // $product->set_catalog_visibility(true);
            update_post_meta( $alias, '_visibility', 'private' );
            // update_post_meta( $alias, '_stock_status', 'instock');
            // update_post_meta( $post_id, '_manage_stock', "no" );
            // update meta
            if ( $qty >= 0 ) {
                $updated_qty = update_post_meta( $alias, $qty_key, $qty );
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
            if( isset($qty) ) {
                $product->set_stock_quantity($qty);
                if( $qty == 0 ) {
                    $product->set_stock_status('outofstock');
                } else {
                    $product->set_stock_status();
                }
            }
            
            if ( !empty( $price ) ) {
                // get giá sale và giá gốc
                $sale_price = get_post_meta( $alias, $sale_price_key, true );
                if ( $sale_price && $sale_price > 0 ) {
                    if( $price <= $sale_price ) {
                        $updated_sale_price = update_post_meta( $alias, $sale_price_key, $price );
                        // $updated_regular_price = update_post_meta( $alias, $regular_price_key, $price );
                        $updated_price = update_post_meta( $alias, $price_key, $price );
                    } else {
                        $updated_price = update_post_meta( $alias, $regular_price_key, $price );
                        $updated_price = update_post_meta( $alias, $price_key, $sale_price );
                    }
                } else {
                    $updated_regular_price = update_post_meta( $alias, $regular_price_key, $price );
                    $updated_price = update_post_meta( $alias, $price_key, $price );
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
    
            if ( isset($sku) && $sku >= 0 ) {
                $updated_sku = update_post_meta( $alias, $sku_key, $sku );
                if (!$updated_sku ) {
                    $response[] = array(
                        'status' => false,
                        'message'=> 'Can not update sku in web api: '. $sku 
                    );
                } else {
                    $response[] = array(
                        'status' => true,
                        'message'=> 'Update sku success'
                    );
                }
            }
            $product->save();
            return wp_send_json(array(
                'status' => true,
                'product_id'=> $alias
            ));
            die;
        }

        return wp_send_json($response);
        die;
    }
}

if( !function_exists( 'delete_product_from_hts' ) ) {
    function delete_product_from_hts($request) {
        $alias = $request->get_param( 'id' );
        return wp_send_json_success(wh_deleteProduct( $alias, true ));
        die;
        // return wp_send_json_success(wp_delete_post( $alias ));
    }
}

function wh_deleteProduct($id, $force = FALSE)
{
    $product = wc_get_product($id);

    if(empty($product)) return true;


    // check info hts

    $curl = curl_init();
    $sku = $product->get_sku();
    $sku = 'V.1080.8G.GL.EXOC.2F';
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ngoisaolon.htsoft.vn:9044/ActionService.svc/GetProductInfoByItemID',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "SKU": "'. $sku .'"
        }',
        CURLOPT_HTTPHEADER => array(
            'clienttag: FFA7BB0E-82F9-4168-B46D-1AD3B526E00D',
            'content-type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if( !$response ) return false;
    $response = json_decode( $response );
    
    if( $response->data->Origin != 'Xóa bán' ) {
        return false;
    }

    // If we're forcing, then delete permanently.
    if ($force && !empty($product))
    {
        if ($product->is_type('variable'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        }
        elseif ($product->is_type('grouped'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }

        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    }
    else
    {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }

    if (!$result)
    {
        // return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
        return false;
    }

    // Delete parent product transients.
    if ($parent_id = wp_get_post_parent_id($id))
    {
        wc_delete_product_transients($parent_id);
    }
    return true;
}
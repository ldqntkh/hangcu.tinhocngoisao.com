<?php

if ( !function_exists( 'getproductprice' ) ) {
    function getproductprice(WP_REST_Request $request) {
        try {
            if ( empty($request['product_ids']) ) wp_send_json_success([]);
            global $wpdb;
            $products = [];
            $product_ids = implode(",", $request['product_ids']);
            
            $key = $product_ids;
            $transChatData = get_transient ( $key );
    
            if ( empty($transChatData) ) {
    
                $query = "SELECT post_id, meta_key, meta_value FROM hc_postmeta WHERE post_id in ($product_ids) and meta_key IN ('_price','_regular_price','_sale_price' ) order by post_id";
    
                $results = $wpdb->get_results( $query );
    
                foreach( $results as $key => $row) {
                    if ( empty( $products[$row->post_id] ) ) {
                        $products[$row->post_id] = [];
                    }
    
                    $rs = $products[$row->post_id];
                    $rs[$row->meta_key] = $row->meta_value;
                    $products[$row->post_id] = $rs;
                }
    
                set_transient( $key, json_encode($products), 60*60*2 ); // 2 hour
                wp_send_json_success( $products );
            } else {
                wp_send_json_success( json_decode($transChatData) );
            }
        } catch ( Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
}

if( !function_exists( 'getproductpricebyids' ) ) {
    function getproductpricebyids( $request ) {
        $productids = isset( $_GET['productids'] ) ? $_GET['productids'] : '';
        if( $productids == '' ) {
            wp_send_json_error('not found');
            die;
        }

        $keycache = 'ladi_products_' . $productids;
        $cache_data = wp_cache_get( $keycache, 'ladi_products' );
        if( $cache_data ) {
            wp_send_json_success($cache_data);
            die;
        }

        $ids = explode(',', $productids);
        
        // $params = $request->get_params();
        // $ids = isset( $params['ids'] ) ? $params['ids'] : [];

        // if( !is_array($ids) || count($ids) == 0 ) {
        //     wp_send_json_error('not found');
        //     die;
        // }

        $results = [];
        foreach( $ids as $id ) {
            $product = wc_get_product( $id );
            if( is_object( $product ) ) {
                $price = $product->get_price();
                $results[$id] = $price;
            }
        }
        wp_cache_set( $keycache, $results, 'ladi_products', 3600 );
        wp_send_json_success($results);
        die;
    }
}
<?php 

class CatalogManager {

    public function getProductsSaleTimeByCategory ($cat_slug, $post_per_page, $custom_query = []) {

        $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
        $current_time = $current_time->format('Y-m-d');
        
        $cache_result = get_cache_by_key('getProductsSaleTimeByCategory'.$cat_slug.$post_per_page, 'hotdeal.txt');
        if ($cache_result) {
            $cache_time = $cache_result['time'];
            if ($cache_time) {
                $date_1 = strtotime($cache_time);
                $date_2 = strtotime($current_time);
                $datediff = $date_2 - $date_1;
                $day = round($datediff / (60 * 60 * 24));
                if ($day < 1) {
                    return $cache_result['data'];
                }
            }
        }

        $query_args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'meta_query'            => array(
                array(
                    'value'         => '0',
                    'compare'       => '>',
                    'type'          => 'NUMERIC'
                )
            )
        );
        
        if ($cat_slug != '') {
            $category = get_term_by( 'slug', $cat_slug, 'product_cat' );
            $cat_id = $category->term_id;
            $query_args["tax_query"] = array( array(
                'taxonomy'   => 'product_cat',
                'field'      => 'term_id',
                'terms'      => array( $cat_id )
            ) );
        }
        
        $query = wp_parse_args( $query_args, $custom_query );

        $loop = new WP_Query( $query );
        $arrProducts = array();
        $productMgr = new ProductManager();
        
        $regular_price = 0;
        $sale_price = 0;

        $valid_cdn = false;
        if ( function_exists( 'check_valid_cdn_hotdeal' ) ) {
            $valid_cdn =  check_valid_cdn_hotdeal();
        }
        while ( $loop->have_posts() ) : $loop->the_post(); 
            global $product;
            if($product->is_on_sale()) {
                // xử lý vụ sale cho ngày hôm sau
                if ( $product->get_manage_stock() && $product->get_stock_quantity() != null ) {
                    if ($product->get_type() === 'variable') {
                        $regular_price = $product->get_variation_regular_price();
                        $sale_price = $product->get_variation_sale_price();
                    } else {
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                    }

                    $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium', true )[0];
                    if ( $valid_cdn ) {
                        $image_url = str_replace( get_home_url(), $valid_cdn, $image_url );
                    }
                    
                    $arrPt = array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name(),
                        'link' => get_permalink( $product->get_id() ),
                        'regular_price' => number_format((float)$regular_price, 0, '.', ','),
                        'sale_price' => number_format((float)$sale_price, 0, '.', ','),
                        'image' => $image_url,
                        'average_rating' => $product->get_average_rating(),
                        'review_count' => $product->get_review_count()
                    );
                
                    $arrPt['manage_stock'] = true;
                    $arrPt['stock_quantity'] = $product->get_stock_quantity();
                    $arrPt['stock_status'] = $product->get_stock_status();
                    $arrPt['sale_end_time'] = $productMgr->getDiscountTimeRemaining($product->get_id());
                    $period = get_post_meta( $product->get_id(), 'warranty_period', true );
                    // if (empty($period)) {
                    //     $period = 36;
                    // }
                    $arrPt['period'] = $period;
                    array_push($arrProducts, $arrPt);
                }
            }
            if (count($arrProducts) >= $post_per_page) break;
        endwhile;
        wp_reset_query();
        set_cache_by_key('getProductsSaleTimeByCategory'.$cat_slug.$post_per_page, array("time" => $current_time, "data" => $arrProducts), 'hotdeal.txt');
        return $arrProducts;
    }
}

?>
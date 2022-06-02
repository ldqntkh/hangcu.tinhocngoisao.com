<?php
    add_action( 'admin_init', 'custom_preferences_cache_init' );

    function custom_preferences_cache_init() {
        register_setting( 'custom_preferences_cache_options', 'custom_preferences_cache_options' );
        add_settings_section( 'custom_preferences_cache', 'Cache config', 'configuration_section_cache', 'custom_preferences_cache' );
        add_settings_field( 'clearcache', 'Clear custom cache', 'clear_custom_cache', 'custom_preferences_cache', 'custom_preferences_cache' );

        add_settings_field( 'create_product_cache_file', 'Tạo file cache bảng giá bán', 'create_product_cache_file', 'custom_preferences_cache', 'custom_preferences_cache' );
    }

    function get_transient_keys_with_prefix( $prefix ) {
        global $wpdb;
    
        $prefix = $wpdb->esc_like( '_transient_' . $prefix );
        $sql    = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
        $keys   = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );
    
        if ( is_wp_error( $keys ) ) {
            return [];
        }
    
        return array_map( function( $key ) {
            // Remove '_transient_' from the option name.
            return ltrim( $key['option_name'], '_transient_' );
        }, $keys );
    }

    function delete_transients_with_prefix( $prefix ) {
        foreach ( get_transient_keys_with_prefix( $prefix ) as $key ) {
            delete_transient( $key );
        }
    }

    function clear_custom_cache() { 
        if (isset($_POST['clearcache'])) {
            if ( function_exists('clear_custom_cache') ) {
                // $files = glob( get_stylesheet_directory() .'/custom-cache/*'); // get all file names
                // foreach($files as $file){ // iterate files
                // if(is_file($file))
                //     unlink($file); // delete file
                // }
                delete_transients_with_prefix( 'get_products_sales-' );
                $msg = 'Xóa thành công!';
            }
        }
    ?>
        <form action="" method="post">
            <input type="hidden" value="clear-cache" name='clearcache' id='clearcache' />
            <?php wp_nonce_field(); ?>
            <button type="submit">Clear</button>
            <?php 
                if (isset($_POST['clearcache']) && isset($msg)) { 
                    echo '<strong>'.$msg.'</strong>';
                }
            ?>
        </form>
    <?php }

    function create_product_cache_file() {
        if (isset($_POST['create_product_cache_file'])) {

            $post_number = 100;
            $start_page = 1;
    
            $orderby = 'date';
            $order = 'DESC';
            $get_slug = 1;
    
            global $wpdb;
            $flag = true;
            $valid_cdn = false;
            $current_time = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
            $current_time = $current_time->format('Y-m-d');

            while( $flag ) {
                $perpage = ($start_page - 1) * $post_number;

                $query_args = array(
                    'posts_per_page' => $post_number,
                    'offset'         => $perpage,
                    'post_status'    => 'publish',
                    'post_type'      => 'product',
                    'no_found_rows'  => 1,
                    'order'          => $order
                );
                $arrResult = [];
                $online_shop_featured_query = new WP_Query( $query_args );
                if ($online_shop_featured_query->have_posts()) :
                    $products = array();
                    while ( $online_shop_featured_query->have_posts() ) : $online_shop_featured_query->the_post();
                        // Do Stuff
                        global $product;
                        if ($product->get_type() === 'variable') {
                            $regular_price = $product->get_variation_regular_price();
                            $sale_price = $product->get_variation_sale_price();
                        } else {
                            $regular_price = $product->get_regular_price();
                            $sale_price = $product->get_sale_price();
                        }
                        $arrPt = array(
                            'id' => $product->get_id(),
                            'name' => $product->get_name(),
                            'link' => get_permalink( $product->get_id()),
                            'regular_price' => number_format((float)$regular_price, 0, '.', ','),
                            'sale_price' => number_format((float)$sale_price, 0, '.', ','),
                        );
                        
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

                        array_push($arrResult, $arrPt);
                    endwhile;
                    
                    if ( count( $arrResult ) > 0 ) {
                        $result = array(
                            "status" => "OK",
                            "errMsg" => "",
                            "data" => $arrResult
                        );
                        $filenamecache = 'get_products_sales-'.$post_number.$start_page.$orderby.$order.$get_slug.'.txt';
                        create_file_sale_cache('get_products_sales'
                                        , array("time" => $current_time, "data" => $arrResult),
                                        $filenamecache);
                        $start_page = $start_page + 1;
                        // if ($start_page == 10) $flag = false;
                    } else $flag = false;
                else :
                    $flag = false;
                endif;
                wp_reset_postdata();
            }
            $msg = 'Tạo cache thành công!';
        }
    ?>
        <form action="" method="post">
            <input type="hidden" value="create-cache" name='create_product_cache_file' id='create_product_cache_file' />
            <?php wp_nonce_field(); ?>
            <button type="submit">Tạo</button>
            <?php 
                if (isset($_POST['create_product_cache_file']) && isset($msg)) { 
                    echo '<strong>'.$msg.'</strong>';
                }
            ?>
        </form>
    <?php }

    function configuration_section_cache() {
        echo '<p>These configuration of caches.</p>';
    }

    function create_file_sale_cache($key, $content, $filename = 'json-cache.txt') {
        // $path = get_stylesheet_directory() ? get_stylesheet_directory() : get_stylesheet_directory();
        // $cache_file_path = $path . '/custom-cache/' .$filename;
        $json[$key] = $content;
        // file_put_contents($cache_file_path, json_encode( $json ));
        set_transient( $filename, json_encode( $json ) );
    }
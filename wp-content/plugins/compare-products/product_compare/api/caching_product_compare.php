<?php
class CachingCompareData {
    public function __construct() {

    }

    public function setupApi() {
        add_action( 'wp_ajax_getcomparetype', array( $this, 'getTypesCompare'));
        add_action( 'wp_ajax_createcachingdata', array( $this, 'createCachingCompareData'));
    }

    public function getTypesCompare() {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $wp_group_table = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $wp_posts = $wpdb->prefix .'posts';

        $productTypes = $wpdb->get_results( "select * from $table_name where  product_type_name <> 'default' ORDER BY id" );
        
        // foreach( $productTypes as $type ) {
        //     $type_id = $type->id;
        //     $group = $wpdb->get_results( "SELECT product_id from $table_mapping_product_attribute_value
        //                                 where group_id IN (SELECT group_id from $wp_group_table WHERE product_type = '$type_id')
        //                                 group by product_id" );
        //     $type->total = count( $group );
        // }
        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $caching_file = $basedir . '/compare-datas.js';

        $this->write_caching_file( $caching_file, '' );
        wp_send_json_success(['types'=> $productTypes, 'upload_dir' => wp_upload_dir()]);
        die;
    }

    public function createCachingCompareData() {
        $product_type_id = isset( $_POST['product_type_id'] ) ? $_POST['product_type_id'] : -1;
        $page = isset( $_POST['page'] ) ? intval($_POST['page']) : -1;
        $end_group = isset( $_POST['end_group'] ) ? $_POST['end_group'] : "false";
        if( $end_group == "true" ) $end_group = true;
        else $end_group = false;

        $perpage = 30;

        if( $type_id == -1 || $page == -1 ) {
            wp_send_json_error(['msg'=> 'Not Found!']);
            die;
        }

        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $caching_file = $basedir . '/compare-datas.js';
        
        $ofset = ( $page - 1 ) * $perpage;

        $product_type_id = strval($product_type_id);

        // global $_wp_using_ext_object_cache;

        // $_wp_using_ext_object_cache_previous = $_wp_using_ext_object_cache;
        // $_wp_using_ext_object_cache = false;

        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $wp_group_table = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;

        $group = $wpdb->get_results( "SELECT product_id from $table_mapping_product_attribute_value
                                        where group_id IN (SELECT group_id from $wp_group_table WHERE product_type = '$product_type_id')
                                        group by product_id limit $ofset,$perpage" );
        // $sql = $wpdb->last_query;
        // $transient_key = 'product_compare_caching_data';
        // $data = get_transient( $transient_key );
        $data = $this->read_caching_file( $caching_file );
        if( !$data ) {
            $data = false;
        } 
        
        $arrResult = array();
        if( $data === false ) {
            $data = [$product_type_id => []];
        } else {
            $data = (array)json_decode( $data );
            if( !isset( $data[$product_type_id] ) ) {
                $data[$product_type_id] = [];
            }
        }
        $arrResult = $data[$product_type_id];
        
        if (count($group) > 0) {
            
            foreach ($group as $item) {
                $product = wc_get_product($item->product_id);
                if( empty($product) || $product->get_status() != 'publish' ) {
                    continue;
                }
                $pd['id'] = $product->get_id();
                $pd['name'] = $product->get_name();
                $pd['slug'] = $product->get_slug();
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
                $pd['image'] = $image[0];
                $pd['link'] = get_permalink( $product->get_id() );
                $pd['price'] = $product->get_price();
                $pd['html_price'] = $product->get_price_html();
                array_push($arrResult, $pd);
            }
            $data[$product_type_id] = $arrResult;

            // sử dụng ghi file
            $this->write_caching_file( $caching_file, json_encode($data) );

            // set_transient( $transient_key, json_encode($data) );
            // $_wp_using_ext_object_cache = $_wp_using_ext_object_cache_previous;

            wp_send_json_success(['end'=> false ]);
            die;
        } 
        if( count($group) == 0 && $end_group === true ) {
            // $caching_file = COMPARE_PRODUCT_PLUGIN_DIR . '/assets/js/compare-data/compare-datas.js';
            // if(!is_file($caching_file)){
            //     touch( $caching_file );
            // }
            $content = "const product_caching_compare = " . json_encode( $data );
            // file_put_contents($caching_file, '');
            // file_put_contents($caching_file, $content);
            // delete_transient( $transient_key );
            $this->write_caching_file( $caching_file, $content );
            // set transient version
            // $transient_key_ver = 'product_compare_caching_data_ver';
            // $version = get_transient( $transient_key_ver );
            // if( !$version ) $version = 1.0;
            // else {
            //     $version += 0.1;
            // }
            // set_transient( $transient_key_ver, $version );
            // $_wp_using_ext_object_cache = $_wp_using_ext_object_cache_previous;
            wp_send_json_success(['end'=> true, "end_group"=> true]);
            die;
        }
        wp_send_json_success(['end'=> true ]);
        die;
    }

    // đọc file
    private function read_caching_file( $filepath ) {
        $file_data = file_get_contents( $filepath , true );
        return $file_data;
    }

    // ghi file
    private function write_caching_file( $filepath, $data ) {
        if(!is_file($filepath)){
            touch( $filepath );
            chmod($filepath, 0666);
        }
        
        file_put_contents($filepath, '');
        file_put_contents($filepath, $data);
    }
}
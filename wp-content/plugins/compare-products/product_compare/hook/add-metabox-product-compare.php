<?php
global $pagenow;
//if ( isset($_GET['post_type']) && $_GET['post_type'] == 'product') {
    include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/api/productType.php';
    include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/api/groupAttributes.php';
    include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/api/mappingProductAttribute.php';

    add_action( 'wp_ajax_displayproductcomparedata', 'displayProductCompareData');
    function displayProductCompareData() {
        $productCompareData = isset($_POST['product_compare']) ? $_POST['product_compare'] : null;
        if ($productCompareData == null) die;
        
        try {
            $groupAttributes = GroupAttributesApi::getGroupAttributes($productCompareData['product_type'], $productCompareData['product_id'], $productCompareData['render_type']);

            $groupAttributes .= '<script>';
            $groupAttributes .= 'var product_compare_data = '. json_encode(GroupAttributesApi::getGroupAttributes($productCompareData['product_type'], $productCompareData['product_id'], 'json')) .';';
            $groupAttributes .= '</script>';

            wp_send_json_success(array(
                "success"=>  true,
                "data" => $groupAttributes,
                "errMsg"=> ""
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                "success"=>  false,
                "data" => null,
                "errMsg"=> $e->getMessage()
            ));
        }
        die();
    }

    add_action( 'add_meta_boxes', 'product_compare_meta_box' );
    if ( ! function_exists( 'product_compare_meta_box' ) )
    {
        function product_compare_meta_box()
        {
            add_meta_box(
                'product_compare_meta_box',
                __( 'Product compare data', TEXT_COMPARE_PRODUCT ),
                'add_product_compare_content_meta_box',
                'product',
                'normal',
                'default'
            );
        }
    }

    if ( ! function_exists( 'add_product_compare_content_meta_box' ) ){
        function add_product_compare_content_meta_box( $post ){
            $group_id = ProductTypeApi::getGroupProductMappingByProductId($post->ID);
            $producttypes = ProductTypeApi::getListProductType();
            $display_thongso_kythuat = get_option( 'display_thongso_kythuat_' . $post->ID );
            ?>
            <div class="product-compare-session">
                <div class="form-group">
                    <label for="product-type-compare"><?php echo __('Select a product type', TEXT_COMPARE_PRODUCT) ?></label>
                    <select id="product-type-compare" name="product-type-compare">
                        <option value=""><?php echo __('Select a product type', TEXT_COMPARE_PRODUCT) ?></option>
                        <?php
                            foreach ($producttypes as $producttype) { ?>
                                <option value="<?php echo $producttype->id ?>" <?php if ($producttype->id == $group_id) echo 'selected' ?>><?php echo $producttype->product_type_name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="product-type-compare"><?php echo __('Select attributes to display on specifications', TEXT_COMPARE_PRODUCT) ?></label>
                    <button class="button" type="button" id="select-attributes-specifications"><?php echo __('Select attributes', TEXT_COMPARE_PRODUCT) ?></button>
                    <input type="hidden" value="<?php echo $display_thongso_kythuat ?>" name="selected-attributes-specifications" id="selected-attributes-specifications" />
                </div>
                <div id="product-compare-datas">
                    <?php // now I will display all option by js ?>
                    <?php if ($group_id) echo GroupAttributesApi::getGroupAttributes($group_id, $post->ID)  ?>
                    <?php if ($group_id) {
                        echo '<script>';
                        echo 'var product_compare_data = '. json_encode(GroupAttributesApi::getGroupAttributes($group_id, $post->ID, 'json')) .';';
                        echo '</script>';
                    } ?>
                </div>
            </div>
            <?php  
                echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';
                echo '<input type="hidden" name="post_id" id="post_id" value="' . $post->ID . '">';
        }
    }

    add_action( 'save_post', 'save_product_compare_content_meta_box', 10, 1 );
    if ( ! function_exists( 'save_product_compare_content_meta_box' ) )
    {
        function save_product_compare_content_meta_box( $post_id ) {
            if (!empty($_POST['product-type-compare'])) {
                $product_type = $_POST['product-type-compare'];
                if ($product_type) {
                    $attribute_data = isset($_POST['attribute']) ? $_POST['attribute'] : null;
                    if ($attribute_data) {
                        $mappingProductAttribute = new MappingProductAttributeRestApi;
                        $mappingProductAttribute->insertMappingDataForm($post_id, $attribute_data);
                    }
                }
            } else {
                $mappingProductAttribute = new MappingProductAttributeRestApi;
                $mappingProductAttribute->deleteMappingDataByProductId($post_id);
            }
            // print_r ($_POST['selected-attributes-specifications']) ;die;
            if ( !empty($_POST['selected-attributes-specifications']) ) {
                if ( get_option( 'display_thongso_kythuat_' . $post_id ) || get_option( 'display_thongso_kythuat_' . $post_id ) == "" ) {
                    update_option( 'display_thongso_kythuat_' . $post_id, $_POST['selected-attributes-specifications'] );
                } else {
                    add_option( 'display_thongso_kythuat_' . $post_id, $_POST['selected-attributes-specifications'] );
                }
            } else {
                delete_option( 'display_thongso_kythuat_' . $post_id );
            }

            if (empty($_POST['product-type-compare'])) {
                delete_option( 'display_thongso_kythuat_' . $post_id );
            }
        }
    }

    add_action( 'before_delete_post', 'remove_product_compare_data' );
    function remove_product_compare_data($post_id) {
        $mappingProductAttribute = new MappingProductAttributeRestApi;
        $mappingProductAttribute->deleteMappingDataByProductId($post_id);
    }

    add_action('wp_ajax_load_product_compare', 'load_product_compare' );
    add_action('wp_ajax_nopriv_load_product_compare', 'load_product_compare' );
    function load_product_compare() {
        $search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : -1;
        $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : -1;
        
        if ($search_name == '' || $product_id == -1 || $product_type == -1) wp_send_json_error();
        
        $product = wc_get_product($product_id);
        if ($product) {
            require_once COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/api/productType.php';
            $productIds = ProductTypeApi::getListProductsMappingByProductIdAndProductType($product_id, $product_type_id, $search_name);
            if ($productIds && count($productIds) > 0) {
                $results = array();
                foreach ($productIds as $key=>$item) {
                    $product_ = wc_get_product($item);
                    if ($product_) {
                        $url = home_url('sssp/');
                        $url .= $product->slug;
                        $url .= '-vs-' . $product_->slug;
                        $prdc = array(
                            "name" => $product_->get_name(),
                            "link" => $url
                        );
                        array_push($results, $prdc);
                    }
                }
                wp_send_json_success($results);
                die;
            }
            else wp_send_json_error();
        } else wp_send_json_error();
        
		die();
    }

    // rewrite rule
    function add_rewrite_rules( $wp_rewrite ) 
    {
        $new_rules = array
        (
            'sssp/(.*?)/?$' => 'index.php?pagename=so-sanh-san-pham'.
            '&product_compares='.$wp_rewrite->preg_index(1),
        );
        // Always add your rules to the top, to make sure your rules have priority
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
    
    function query_vars($public_query_vars)
    {
        $public_query_vars[] = "product_compares";
    
        return $public_query_vars;
    }
    
    function ebi_flush_rewrite_rules()
    {
        global $wp_rewrite;
    
        $wp_rewrite->flush_rules();
    }
    
    add_action( 'init', 'flush_rewrite_rules');
    add_action('generate_rewrite_rules', 'add_rewrite_rules');
    add_filter('query_vars', 'query_vars');

//}


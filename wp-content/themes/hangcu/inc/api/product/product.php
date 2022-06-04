<?php

if(  !function_exists('hc_add_product_review')) {
    function hc_add_product_review() {
        $post_data = $_POST;
        if( empty($post_data['product_id']) || empty($post_data['review']) || empty($post_data['rating']) ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"  => "Thông tin bình luận không chính xác"
            ]);
        }
        $current_user = wp_get_current_user();

        // check product
        $product = wc_get_product( $post_data['product_id'] );
        if( empty( $product ) ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"  => "Thông tin sản phẩm không tồn tại"
            ]);
        }

        $data = [
            'product_id' => $post_data['product_id'],
            'review' => trim($post_data['review']),
            'reviewer' => $current_user->display_name,
            'reviewer_email' => $current_user->user_email,
            'rating' => $post_data['rating']
        ];
        
        
        $comment_id = wp_insert_comment([
            'comment_post_ID'      => $post_data['product_id'],
            'comment_author'       => $current_user->display_name,
            'comment_author_email' => $current_user->user_email, // <== Important
            'comment_content'      => trim($post_data['review']),
            'comment_author_url'   => '',
            'comment_type'         => 'review',
            'comment_parent'       => 0,
            'comment_author_IP'    => '',
            'comment_agent'        => '',
            'comment_date'         => date('Y-m-d H:i:s'),
            'user_id'              => $current_user->ID,
            'comment_approved'     => 0
        ]);
        update_comment_meta( $comment_id, 'rating', $post_data['rating'] );
        if( empty($comment_id) ) {
            wp_send_json_error([
                "success" => false,
                "errMsg"  => "Có lỗi xảy ra. Vui lòng thử lại."
            ]);
        } else {
            wp_send_json_success([
                "success" => true,
                "errMsg"  => "",
                "data"    => $comment_id 
            ]);
        }
    }
    add_action("wp_ajax_hc_add_product_review", "hc_add_product_review");
}

if( !function_exists('export_product_data_by_cat_id') ) {
    function export_product_data_by_cat_id() {
        $post_data = $_POST;
        if( empty($post_data['cat_id']) ) {
            wp_send_json_error([
                "errMsg"  => "Dữ liệu không chính xác"
            ]);
        }
        $current_user = wp_get_current_user();
        $page = 1;
        if( !empty( $post_data['page'] ) && is_numeric( $post_data['page'] ) ) {
            $page = intval($post_data['page']);
            if( $page <= 0) $page = 1;
        }

        if( current_user_can('edit_products') ) {
            $product_objects = wc_get_products( array( 
                'status'    => array( 'draft', 'pending', 'private', 'publish' ), 
                'orderby'   => 'date', 
                'order'     => 'DESC', 
                // 'paged'      => $page,
                'posts_per_page'=> -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    =>  $post_data['cat_id'],
                        'operator' => 'IN',
                        'include_children' => true
                    ),
                )
            ) );

            $results = array();

            if( ! empty( $product_objects ) ) {
                foreach ( $product_objects as $product ) {
                    $id = $product->get_id();

                    $results[] = array(
                        "product_name" => $product->get_name(),
                        "specifications"  => export_product_short_specifications($product)
                    );
                }
            }

            wp_reset_postdata();
            wp_send_json_success($results);
        }

        wp_send_json_error([
            "errMsg"  => "Bạn không thể thực hiện thao tác này"
        ]);
    }
    add_action("wp_ajax_export_product_data_by_cat_id", "export_product_data_by_cat_id");
}

if( !function_exists('export_product_short_specifications') ) {
    function export_product_short_specifications($product) {

        $max_line_short_specification = 9;
        $count_line = 1;
        $short_specifications = '';
        $specifications = '';
        $hasValue = false;
        include_once WP_PLUGIN_DIR. '/hangcu-compare-products/product_compare/api/productType.php';
        include_once WP_PLUGIN_DIR. '/hangcu-compare-products/product_compare/api/groupAttributes.php';
    
        $group_mapping_id = ProductTypeApi::getGroupProductMappingByProductId($product->get_id());
    
        $group_attribute = GroupAttributesApi::getGroupAttributes($group_mapping_id, $product->get_id(), 'json');
    
        if (!empty($group_attribute)) {
            $display_thongso_kythuat = get_option( 'display_thongso_kythuat_' . $product->get_id() );
            $display_thongso_kythuat = json_decode( base64_decode($display_thongso_kythuat), true);
            
            // $short_specifications = '<ul>';
            foreach ($group_attribute as $index => $attribute) {
                if (isset($attribute['attribute'])) {
                    $is_exist_data_group = false;
                    
                    foreach($attribute['attribute'] as $key => $value) {
                        if ($value['type'] === 'Slider' || $value['type'] === 'Image') {
                            continue;
                        }
    
                        if (!$is_exist_data_group) {
                            $is_exist_data_group = true;
    
                            $specifications .= '<ul> <li>'.$attribute['group_name'].'</li>';
                        }
    
                        if ( $value['value']) {
                            $specifications .= '<li><span class="label">'.$value['name'].':</span><span class="value">'.$value['value'].'</span></li>';
                        }
    
                        if ( $count_line <= $max_line_short_specification && isset( $display_thongso_kythuat[$index]['attribute'][$key] ) ) {
                            $hasValue = true;
                            $count_line++;
                        }
                    }
    
                    if ($is_exist_data_group) {
                        $specifications .= '</ul>';
                    }
                }
            }
        }
    
        if( $specifications == '' ) {
            $specifications = get_field('specifications', $product->get_id());
        }
        return $specifications;
    }
}

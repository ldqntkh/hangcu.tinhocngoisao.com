<?php 
/*
    Template Name: Build PC
*/
    get_header();
    $list_product_type = get_option( 'custom_preferences_options' )['list_product_type'];
    $list_product_type = json_decode( $list_product_type, true );
    array_shift($list_product_type);
    

    // xử lý việc edit 
    if (isset($_GET['building_data'])) {
        $edit_building_data = [];
        $product_data_buildpc = json_decode(urldecode(base64_decode($_GET['building_data'])));
        foreach($product_data_buildpc as $key=>$item) {
            $product = wc_get_product(intval($item->product_id));
            if ( empty( $product ) ) {
                continue;
            }
        
            if ($product->get_type() === 'variable') {
                $regular_price = $product->get_variation_regular_price();
                $sale_price = $product->get_variation_sale_price();
            } else {
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
            }
            $arrPt = array(
                'id' => $product->get_id(),
                'name' => $product->name,
                'link' => get_permalink( $product->product_id),
                'regular_price' => $regular_price,
                'sale_price' => $sale_price,
                'image' => wp_get_attachment_image_src( $product->get_image_id(), 'medium', true )[0],
                'average_rating' => $product->average_rating,
                'review_count' => $product->review_count
            );
            
            $attributes = $product->get_attributes();
            if (count($attributes)) {
                $arrPt['attributes'] = [];
                foreach($attributes as $attribute) {
                    $get_terms_args = array( 'hide_empty' => '1' );
                    $terms = get_terms( $attribute['name'], $get_terms_args );
                    
                    $index = 0;
                    foreach($terms as $term) {
                        $options = $attribute->get_options();
                        $options = ! empty( $options ) ? $options : array();
                        if (wc_selected( $term->term_id, $options ) === "") {
                            unset($terms[$index]);
                            //var_dump($terms);
                        }
                        $index++;
                    }
                    if (count($terms)) {
                        $arrAttr = array(
                            "name" => $attribute['name'],
                            "values" => $terms
                        );
                        array_push($arrPt['attributes'], $arrAttr);
                    }
                }
            }
            $arrPt['manage_stock'] = true;
            $arrPt['stock_quantity'] = $product->stock_quantity;
            $edit_building_data[$key] = array(
                "product" => $arrPt,
                "quantity" => $item->quantity
            );
        }
    }
?>
    <script>
        var product_types = <?php echo json_encode($list_product_type); ?>;
        <?php if( isset($edit_building_data) && $edit_building_data !== null) : ?>
        var edit_building_data = <?php echo json_encode($edit_building_data); ?>;
        <?php endif; ?>
        <?php if ( !empty( get_option( 'custom_preferences_options' )['fb_appId'] ) ) : ?>
            var facebookAppId = <?php echo get_option( 'custom_preferences_options' )['fb_appId']; ?>;
        <?php endif; ?>
    </script>
    <div class="header-left"></div>
    <div id="build-pc-function"></div>
<?php
    get_footer();
?>
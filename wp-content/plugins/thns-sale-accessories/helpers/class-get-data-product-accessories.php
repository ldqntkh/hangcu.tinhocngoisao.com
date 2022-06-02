<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GetDataProductAccessories {

    public static function getProductSaleAccessories( $accessories_data ) {
        /**
         * [
         *      "group_name" => [
         *          name => name,
         *          products => [
         *              [
         *                  product_name    => name,
         *                  product_price   => price,
         *                  product_link    => link,
         *                  image           => link,
         *                  product_price_discount  => price
         *              ]
         *          ]
         *      ]
         * ]
         */
        $result = [];
        
        foreach( $accessories_data as $key => $item ) {
            $data = [];
            $data['name'] = $item['se_name'];

            $discount_type = $item['se_type'];
            $discount_down = $item['se_down'];

            $productIds = $item['products'];
            
            $products = [];
            foreach( $productIds as $id ) {
                $product = wc_get_product( $id );
                if ( !empty( $product ) && $product->is_type( array( 'simple', 'variable' ) ) ) {
                    
                    if ( $product->get_manage_stock() && ( !$product->get_stock_quantity() || $product->get_stock_quantity() <= 0 ) ) continue;

                    $images = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );

                    $price_discount = 0;
                    $percent_discount = 0;
                    if ( $discount_type == 'price' ) {
                        $price_discount = $discount_down;
                        $percent_discount = ceil( $discount_down / $product->get_price() * 100 );
                    } else {
                        $price_discount = $product->get_price() / 100 * $discount_down;
                        $percent_discount = $discount_down;
                    }

                    $_product = [
                        "product_id"                        => $product->get_id(),
                        "product_name"                      => $product->get_name(),
                        "product_sale_price"                => $product->get_price(),
                        "product_sale_price_display"        => wc_price( $product->get_price() ),
                        "product_price"                     => $product->get_price() - $price_discount,
                        "product_price_display"             => wc_price( $product->get_price() - $price_discount ),
                        "product_link"                      => get_permalink( $product->get_id() ),
                        "image"                             => count( $images ) > 0 ? $images[0] : '',
                        "product_price_discount"            => $price_discount,
                        "product_price_discount_display"    => wc_price($price_discount),
                        "product_percent_discount"          => $percent_discount
                    ];

                    $products[] = $_product;
                }
            }
            $data['products'] = $products;
            $result[] = $data;
        }
        
        return $result;
    }

    public static function gearvn_template_loop_categories( $product_id ) {
        $categories = wc_get_product_category_list( $product_id );
		echo apply_filters( 'gearvn_template_loop_categories_html', wp_kses_post( sprintf( '<span class="loop-product-categories">%s</span>', $categories ) ) );
	}
}
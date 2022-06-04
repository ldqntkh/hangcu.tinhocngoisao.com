<?php

if( !function_exists( 'hc_template_loop_product_sale_price_percent' ) ) {
    function hc_template_loop_product_sale_price_percent() {
        global $product;
        if( $product->is_on_sale() ) {
            $sale_price = $product->get_sale_price();
            $regular_price = $product->get_regular_price();

            $percent = ceil(100 -( $sale_price / $regular_price ) * 100); ?>
            <div class="sale-percent">
                <span>-<?= $percent ?>%<span>
            </div>
        <?php }
    }
}

if( !function_exists( 'hc_template_loop_product_thumbnail' ) ) {
    function hc_template_loop_product_thumbnail() {
        global $product;
        $san_pham_noi_bat = get_field('san_pham_noi_bat', $product->get_id());

			if ( !empty( $san_pham_noi_bat ) ) {
                if( $san_pham_noi_bat['is_special_product'] ) {
                    $special_image = $san_pham_noi_bat['special_product_image'];
                    if( $special_image ) : ?>
                        <img src="<?= $special_image ?>" alt="<?= $product->get_name() ?>" />
                    <?php endif;
                }
			}
    }
}

if ( ! function_exists( 'hc_template_loop_product_link_open' ) ) {
	/**
	 * Insert the opening anchor tag for products in the loop.
	 */
	function hc_template_loop_product_link_open() {
		global $product;

		$link = apply_filters( 'hc_loop_product_link', get_permalink( $product->get_id() ), $product );

		echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	}
}

if ( ! function_exists( 'hc_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function hc_template_loop_product_title() {
        global $product;
		echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . $product->get_title() . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if( !function_exists( 'show_only_products_with_specific_metakey' ) ) {
    function show_only_products_with_specific_metakey( $meta_query, $query ) {
        // Only on shop pages
        // if( ! is_shop() ) {
        //     die;
        //     return $meta_query;
    
        // }
        // die;
        $meta_query[] = array(
            'key'     => 'stop_selling',
            'value'   => '0',
            'compare' => '=',
        );
        return $meta_query;
    }
}


if( !function_exists( 'show_popup_product_addcart' ) ) {
    function show_popup_product_addcart() {
        if( wp_is_mobile() ) : 
            global $product;
    
        ?>
            <div id="mb-product-overlay" class="electro-overlay electro-close-off-canvas"></div>
            <div id="mb-product-detail-bottom">
                <span class="electro-close-icon"></span>
                <div class="line-1">
                    <span class="electro-checked-icon"></span>
                    Thêm vào giỏ hàng thành công!
                </div>
                <div class="line-2">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'medium', true )[0] ?>" />
                    <p>
                        <strong><?= $product->get_name() ?></strong>
                        <span class="electro-price"><span class="woocommerce-Price-amount amount"> <?= wc_price( wc_get_product($product->get_id())->get_price() )  ?> </span></span>
                    </p>
                </div>
                <div class="line-3">
                    <a href="<?= wc_get_cart_url() ?>">Xem giỏ hàng</a>
                </div>
            </div>
        <?php endif;
    }
}

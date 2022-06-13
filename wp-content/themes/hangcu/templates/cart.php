<?php 
/**
 * Template Name: CT giỏ hàng
 */
if( electro_detect_is_mobile() ) {
    get_header('mb-cart');
    do_action( 'electro_before_header_mb' );
} else {
    get_header();
}

echo do_shortcode('[woocommerce_cart]');
get_footer();
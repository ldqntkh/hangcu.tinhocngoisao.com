<?php

add_action( 'wp_enqueue_scripts', 'disable_woocommerce_cart_fragments', 11 ); 

add_action( 'wp_head', function () {
    $cart_hash_key   = apply_filters( 'woocommerce_cart_hash_key', 'wc_cart_hash_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) );
    $fragment_name   = apply_filters( 'woocommerce_cart_fragment_name', 'wc_fragments_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) );
?>
<script>
    const cart_hash_key = '<?= $cart_hash_key ?>';
    const fragment_name = '<?= $fragment_name ?>';
</script>
<?php
}, 1 );

add_action('init',function() {
    remove_filter( 'woocommerce_add_to_cart_fragments',			'electro_mini_cart_fragment' );
});

add_filter( 'woocommerce_add_to_cart_fragments',			'hc_mini_cart_fragment' );

add_filter( 'woocommerce_cart_product_not_enough_stock_already_in_cart_message', function( $message ) {
    return '';
} );

add_filter( 'woocommerce_cart_redirect_after_error', function( $link, $product_id ) {
    $product           = wc_get_product( $product_id );
    $product_name = $product->get_name();

    $stop_selling = get_field('stop_selling', $product_id);
    if( $stop_selling ) {
        return "Sản phẩm $product_name hiện đang tạm ngưng kinh doanh";
    }

    $total = $product->get_stock_quantity();
    return "Sản phẩm $product_name có số lượng tối đa được mua là $total";
}, 10, 2 );
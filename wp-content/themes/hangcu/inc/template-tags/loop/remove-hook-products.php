<?php
add_action( 'after_setup_theme', function() {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'electro_template_loop_categories', 20 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_categories', 50 );

    // title
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 30 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 40 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 45 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_header_close', 46 );

    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_body_open', 47 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_categories', 50 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 60 );

    remove_action( 'woocommerce_after_shop_loop_item_title', 'electro_template_loop_rating', 70 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'electro_template_loop_product_excerpt', 80 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'electro_template_loop_product_sku', 90 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 120 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'electro_wc_template_loop_sale', 99 );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover',          140 );
}, 0 );

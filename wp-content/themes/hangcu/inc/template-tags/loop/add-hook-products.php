<?php

add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 30 );
// add_action( 'woocommerce_shop_loop_item_title', 'hc_template_loop_product_sale_price_percent', 35 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 40 );
add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_header_close', 46 );

add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_body_open', 47 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 55 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 60 );

add_action( 'hc_shop_loop_item_title', 'hc_template_loop_product_link_open', 1 );
add_action( 'hc_shop_loop_item_title', 'hc_template_loop_product_thumbnail', 10 );
// add_action( 'hc_shop_loop_item_title', 'hc_template_loop_product_sale_price_percent', 15 );
add_action( 'hc_shop_loop_item_title', 'hc_template_loop_product_title', 20 );

add_filter( 'woocommerce_product_query_meta_query', 'show_only_products_with_specific_metakey', 10, 2 );
add_action( 'woocommerce_after_single_product', 'show_popup_product_addcart', 10 );

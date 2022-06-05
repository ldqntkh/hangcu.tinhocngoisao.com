<?php

/**
 * Rename "home" in breadcrumb
 */
// add_filter( 'woocommerce_get_breadcrumb', 'wcc_change_breadcrumb_home_text' );
add_filter( 'woocommerce_page_title', 'filter_woocommerce_page_title', 10, 1 );
add_action( 'woocommerce_no_products_found', 'hangcu_no_products_found' );

add_filter( 'woocommerce_catalog_orderby', 'change_filter_translate_sort' );
add_filter( 'woocommerce_breadcrumb_defaults', 'hc_change_config_breadcrumb', 10 );

add_action( 'electro_content_top', 'electro_wc_loop_title',  20 );
add_action( 'electro_content_top', 'electro_shop_archive_jumbotron',  20 );
add_action( 'electro_before_content', 'electro_breadcrumb', 10 ); 


/**
 * Add label promotion product. Giá sốc, giá giảm sốc
 */
// add_filter('electro_template_loop_product_thumbnail', 'hangcu_loop_product_thumbnail_with_label_campaign');
add_action( 'electro_content_top', 'hangcu_show_brand_category',  20 );

add_action( 'electro_content_top', 'category_banner_promotion',  20 );
add_action( 'woocommerce_before_shop_loop', 'hangcu_category_hot_deals', 5 );
add_action( 'woocommerce_before_shop_loop', 'hangcu_top_five_best_sellers', 5 );
add_action( 'woocommerce_before_shop_loop', 'hc_shop_control_bar', 11 );
<?php
add_action( 'after_setup_theme', function() {
    remove_action( 'electro_shop_control_bar', 'electro_advanced_pagination', 40 );
    remove_action( 'electro_shop_control_bar', 'electro_wc_products_per_page', 30 );

    remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );

    remove_action( 'woocommerce_before_main_content', 'electro_shop_archive_jumbotron', 50 );
    remove_action( 'woocommerce_before_shop_loop', 'electro_wc_loop_title', 10 );

    remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

    remove_action( 'woocommerce_before_shop_loop', 'electro_shop_control_bar', 11 );
}, 0);
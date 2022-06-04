<?php
/**
 * Template Hooks used in Single Product Page
 */

/**
 * Single Product
 */

remove_action( 'woocommerce_after_single_product_summary',  'woocommerce_output_related_products',          20 );
add_action( 'woocommerce_before_single_product',            'electro_toggle_single_product_hooks',          10 );

//add_filter( 'electro_show_shop_sidebar',                    'electro_toggle_shop_sidebar',                  10 );
add_filter( 'woocommerce_product_thumbnails_columns',       'electro_product_thumbnails_columns',           10 );



add_action( 'hangcu_before_single_product_summary',    'electro_wrap_single_product',                  0  );
add_action( 'hangcu_before_single_product_summary',    'electro_wrap_product_images',                  10  );
add_action( 'hangcu_before_single_product_summary',    'electro_show_product_images',                  20 );
add_action( 'hangcu_before_single_product_summary',    'electro_single_product_deal_countdown_timer',  29 );
add_action( 'hangcu_before_single_product_summary',    'electro_wrap_product_images_close',            30 );

// remove_action( 'hangcu_single_product_summary',           'electro_template_loop_categories',             1  );
// add_action( 'hangcu_single_product_summary',           'electro_template_loop_categories',             1  );

// remove_action( 'hangcu_single_product_summary',           'electro_template_single_brand',                10 );
// add_action( 'hangcu_single_product_summary',           'electro_template_single_brand',                10 );

// add_action( 'hangcu_single_product_summary',           'electro_template_loop_availability',           10 );
// add_action( 'hangcu_single_product_summary',           'electro_template_single_divider',              11 );
add_action( 'hangcu_single_product_summary',           'electro_loop_action_buttons',                  15 );
add_action( 'hangcu_single_product_summary',           'woocommerce_template_single_sharing',          15 );
add_action( 'hangcu_single_product_summary',           'woocommerce_template_single_price',            5 );
add_action( 'hangcu_single_product_summary',           'electro_template_single_add_to_cart',          30 );

// function of woocommerce
add_action( 'hangcu_single_product_summary', 'woocommerce_template_single_title', 1 );
// add_action( 'hangcu_before_single_product_summary', 'electro_template_single_divider', 5 );
add_action( 'hangcu_product_tab_compare', 'show_add_to_cart_form', 10 );
add_action( 'hangcu_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );

add_action( 'woocommerce_after_single_product_summary',     'electro_wrap_single_product_close',            1  );
// add_action( 'hangcu_related_product',     'woocommerce_upsell_display',              15 );
// add_action( 'hangcu_related_product',     'electro_output_related_products',              20 );
add_action( 'woocommerce_review_after_comment_text',        'hc_wc_review_meta',                       10 );
add_action( 'woocommerce_review_before_comment_meta',        'hc_wc_review_meta_approve',                       10 );


// single product right content
/**
 * see all functions at hangcu-single-product-template-tags.php
 */
add_action( 'hangcu_single_product_right_content',      'hangcu_product_storage',               10 );
add_action( 'hangcu_single_product_right_content',      'hangcu_product_policy',                20 );
add_action( 'hangcu_single_product_right_content',      'hangcu_product_old',                   30 );
add_action( 'woocommerce_after_add_to_cart_form',       'hangcu_after_add_to_cart_form',        30 );
add_action( 'woocommerce_after_add_to_cart_form',       'hangcu_after_add_to_cart_display_configuration',        40 );
add_action( 'woocommerce_after_add_to_cart_form_compare',       'hangcu_after_add_to_cart_form_compare',        30 );
add_action( 'hangcu_after_display_price',               'hangcu_after_price_display_installment', 10 );
// add_action( 'woocommerce_after_single_product_summary', 'hangcu_output_product_right_content', 9 );
// add_action( 'woocommerce_after_single_product_summary', 'hangcu_output_product_accessories', 8 );
add_action( 'woocommerce_after_add_to_cart_button', 'show_compare_product_button', 20 );
add_action( 'hangcu_product_tab_compare', 'display_product_tab_compare', 20 );
add_action('woocommerce_after_add_to_cart_button', 'clear_session_after_add_to_cart');
add_action( 'woocommerce_before_single_product', 'shuffle_variable_product_elements' );
/**
 * function to remove all action required by parent theme
 */
add_action( 'after_setup_theme', 'hangcu_after_setup_theme', 100 );

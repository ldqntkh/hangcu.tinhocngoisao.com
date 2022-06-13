<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// header top menu
// add_action( 'electro_before_header',    'hc_header_top_right_menu',      20 );
add_action( 'electro_navbar_v2', 'electro_primary_nav_menu', 20 );
add_action( 'electro_masthead_v2',   'hc_navbar_search', 20 );
add_action( 'electro_masthead_v2',   'electro_header_icons', 30 );
add_action( 'electro_navbar_v2',   'hc_departments_menu', 10 );

add_action( 'electro_header_icons', 'hc_header_mini_cart_icon', 90 );
add_action( 'electro_header_icons', 'hc_header_user_account',   80 );

if( electro_detect_is_mobile() ) {
    add_action( 'hc_header_checkout', 'hc_mb_checkout_step', 20 );
} else {
    add_action( 'hc_header_checkout', 'electro_header_logo_area', 10 );
    add_action( 'hc_header_checkout', 'hc_checkout_step', 20 );
    add_action( 'hc_header_checkout', 'electro_header_support',   30 );
}


add_action( 'electro_header_mb_v2', 'electro_header_mb_v2_row_1', 20 );
// add_action( 'electro_header_mb_v2', 'electro_header_mb_v2_row_2', 20 );
add_action( 'electro_header_mb_v2_row_1', 'hc_handle_nav_menu', 20 );
// add_action( 'electro_header_mb_v2_row_1',  'electro_off_canvas_nav',   20 );
add_action( 'electro_header_mb_v2_row_1', 'electro_handheld_header_logo', 20 );
add_action( 'electro_header_mb_v2_row_1', 'hc_navbar_search', 20 );
add_action( 'electro_header_mb_v2_row_1', 'hc_header_mini_cart_icon', 20 );

add_action( 'electro_header_mb_v2_row_2', 'hc_navbar_search', 20 );
add_action( 'electro_before_header_mb', 'hc_canvas_navigation', 20 );

add_action( 'electro_header_logo_area',          'hc_off_canvas_nav', 20 );
add_action( 'electro_header_handheld',           'hc_off_canvas_nav', 20 );
add_action( 'electro_handheld_header_v2',        'hc_off_canvas_nav', 10 );

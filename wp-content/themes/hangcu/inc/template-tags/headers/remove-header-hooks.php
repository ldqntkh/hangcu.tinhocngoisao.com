<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Header Logo Area
 */
if( !function_exists( 'remove_header_action_after_setup_theme' ) ) {
    function remove_header_action_after_setup_theme() {
        remove_action( 'electro_header_logo_area',          'electro_off_canvas_nav', 20 );
        remove_action( 'electro_header_handheld',           'electro_off_canvas_nav', 20 );
        remove_action( 'electro_handheld_header_v2',        'electro_off_canvas_nav', 10 );
        remove_action( 'electro_mobile_header_v1',          'electro_off_canvas_nav', 10 );
        remove_action( 'electro_mobile_header_v2',          'electro_off_canvas_nav', 10 );

        remove_action( 'electro_before_header',    'electro_top_bar',      10 );
        remove_action( 'electro_navbar_v2',   'electro_departments_menu_v2', 10 );
        // remove header-search
        remove_action( 'electro_navbar_v2',   'electro_navbar_search', 20 );
        remove_action( 'electro_navbar_v2',   'electro_header_icons', 30 );
        remove_action( 'electro_masthead_v2', 'electro_primary_nav_menu', 20 );
        remove_action( 'electro_masthead_v2', 'electro_header_support',   30 );

        remove_action( 'electro_after_header',         'electro_handheld_header',      10 );

        remove_action( 'electro_header_icons', 'electro_header_mini_cart_icon', 90 );
        remove_action( 'electro_header_icons', 'electro_header_user_account',   80 );
    }
    add_action( 'after_setup_theme', 'remove_header_action_after_setup_theme', 0 );
}


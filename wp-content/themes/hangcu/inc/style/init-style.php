<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !function_exists ( 'hc_activate_stylesheet_header' ) ) {
    function hc_activate_stylesheet_header() {
        // SCSS
        $style_link = THEME_URI . '/assets/styles/custom-style.css?ver='.STYLE_VERSION;
        wp_enqueue_style( 'custom-style', $style_link, array(), STYLE_VERSION );
        if( electro_detect_is_mobile() ) {
            $mb_style_link = get_stylesheet_directory_uri(). '/assets/styles/mb-custom-style.css?ver='.STYLE_VERSION;
            wp_enqueue_style( 'mb-custom-style', $mb_style_link, array(), STYLE_VERSION );
        }
    }
    add_action( 'wp_head', 'hc_activate_stylesheet_header', 1 );
}

if( !function_exists ( 'hc_activate_javascripts' ) ) {
    function hc_activate_javascripts() {
        // Javascript
        $script_file = get_stylesheet_directory_uri(). '/assets/javascript/app.js?ver='.STYLE_VERSION;
        $slick_file =  get_stylesheet_directory_uri() . '/assets/javascript/slick.min.js?ver='.STYLE_VERSION;
        wp_enqueue_script('slickJS', $slick_file, array('jquery'), STYLE_VERSION);
        wp_enqueue_script('app_script', $script_file, array('jquery'), STYLE_VERSION);
        if( electro_detect_is_mobile() ) {
            $mb_script_file = get_stylesheet_directory_uri(). '/assets/javascript/mb-app.js?ver='.STYLE_VERSION;
            wp_enqueue_script('mb_app_script', $mb_script_file, array('jquery'), STYLE_VERSION);
        }
    }
    add_action( 'wp_head', 'hc_activate_javascripts' );
}

function elctro_child_scripts() {
    // if (is_account_page() || is_checkout() || is_home() || is_front_page()) {
       
    // }
    wp_enqueue_script('validateJS', get_stylesheet_directory_uri() . '/assets/javascript/validate.min.js', array('jquery'), STYLE_VERSION);
}
add_action( 'wp_enqueue_scripts', 'elctro_child_scripts', 10 );
<?php 
define( 'STYLE_VERSION', '1.0.0' );
define( 'THEME_PATH', get_stylesheet_directory() );
define( 'THEME_URI', get_stylesheet_directory_uri() );
define( 'INC_PATH', THEME_PATH . '/inc' );

// Fix error jQuery 3.0
wp_deregister_script('jquery');
wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js', false, null);
wp_enqueue_script('jquery');

if ( ! function_exists( 'electro_detect_is_mobile' ) ) {
    function electro_detect_is_mobile() {
        require_once get_template_directory() . '/inc/classes/class-mobile-detect.php';

        $detect = new Mobile_Detect();
        $is_mobile_tablet = ( $detect->isMobile() || $detect->isTablet() );
        return $is_mobile_tablet;
    }
}

// init inc folder
include_once ( INC_PATH . '/init.php' );

add_action( 'admin_menu', function() {
    if ( empty ( $GLOBALS['admin_page_hooks']['hangcu-functions'] ) ) {
        add_menu_page('CT Functions', 'CT Functions', 'manage_options', 'hangcu_functions', 'custom_preferences_options', '', 24);
    }
}, 5 );

add_filter('electro_departments_menu_v2_enable_dropdown', 'hangcu_show_dropdown');
function hangcu_show_dropdown($enable_dropdown) {
    if ( is_front_page() ) {
        $enable_dropdown = false;
    }

    return $enable_dropdown;
}

add_action('electro_loop_before', function() {
    wp_redirect(home_url(), 301);
    die;
});

add_filter('show_admin_bar', '__return_false');

// to testing => if OK move it to the hook file

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );


add_action('init', 'session_start');


// customize
function remove_wpb_js_css() {
    if ( !is_admin() ) {
        wp_dequeue_script('wpb_composer_front_js');
        wp_deregister_script('wpb_composer_front_js');

        wp_dequeue_style('animate-css');
        wp_deregister_style('animate-css');

        wp_dequeue_style('wp-block-library');
        wp_deregister_style('wp-block-library');

        wp_dequeue_style('wc-block-vendors-style');
        wp_deregister_style('wc-block-vendors-style');

        wp_dequeue_style('wc-block-style');
        wp_deregister_style('wc-block-style');
        
        wp_dequeue_style('contact-form-7');
        wp_deregister_style('contact-form-7');

        wp_dequeue_style('electro-fonts');
        wp_deregister_style('electro-fonts');
    }
}
add_action( 'wp_enqueue_scripts', 'remove_wpb_js_css', 99 );

// Hide all admin notices
add_action( 'init', function() {
    remove_all_actions('admin_notices', 10);
});

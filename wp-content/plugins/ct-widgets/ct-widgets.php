<?php
/*
Plugin Name: CT Widgets
Description: Danh sách widgets cho CT.
Author: Quang Lê
Version: 1.0
*/
if (!defined( 'ABSPATH')) {
    die;
}

define( 'HC_WIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require HC_WIDGET_PLUGIN_DIR . '/helper/customize-functions.php';

require HC_WIDGET_PLUGIN_DIR . '/sidebars/init-sidebars.php';

require HC_WIDGET_PLUGIN_DIR . '/desktop/widget-homepage-slider.php';

require HC_WIDGET_PLUGIN_DIR . '/mobile/widget-mobile-slider.php';

require HC_WIDGET_PLUGIN_DIR . '/shared/widget-brands.php';
require HC_WIDGET_PLUGIN_DIR . '/shared/widget-display-static-block.php';
require HC_WIDGET_PLUGIN_DIR . '/shared/widget-show-product-carousel.php';
require HC_WIDGET_PLUGIN_DIR . '/shared/widget-show-special-products.php';


// init các sidebar
add_action( 'widgets_init', 'hangcu_init_sidebars', 10 );

// init các widget
add_action( 'widgets_init', 'hangcu_init_widgets', 20 );

// xóa các widget của theme 
if( !function_exists( 'hangcu_widget_init_after_theme_setup' ) ) {
    function hangcu_widget_init_after_theme_setup( ) {
        // add_action( 'widgets_init',         'electro_setup_sidebars',                   10 );
        remove_action( 'widgets_init',         'electro_register_widgets',                 20 );
    }
    add_action( 'after_setup_theme', 'hangcu_widget_init_after_theme_setup', 10 );
}


function hangcu_init_widgets() {
    // desktop
    register_widget('CT_Widget_HomePage_Slider');
    register_widget('HC_Products_Carousel');
    register_widget('HC_Special_Products');
    
    // mobile
    register_widget('CT_Widget_Mobile_HomePage_Slider');

    // shared
    register_widget('CT_Widget_Brands_Slider');
    register_widget('CT_Widget_Display_StaticBlockCT_Widget_Brands_Slider');
    
}

add_action( 'after_setup_theme', function() {
    require HC_WIDGET_PLUGIN_DIR . '/shared/filter/ct-widget-layered-nav-filters.php';
    require HC_WIDGET_PLUGIN_DIR . '/shared/filter/ct-widget-layered-nav.php';
    
    if ( class_exists( 'CT_Widget_Layered_Nav' ) )
        register_widget( 'CT_Widget_Layered_Nav' );
    
    if ( class_exists( 'CT_Layered_Nav_Filters' ) )
        register_widget( 'CT_Layered_Nav_Filters' );

} );

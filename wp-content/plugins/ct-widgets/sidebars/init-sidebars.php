<?php
if (!defined( 'ABSPATH')) {
    die;
}

if( !function_exists('hangcu_init_sidebars') ) {
    function hangcu_init_sidebars() {
        register_sidebar( array(
            'name'          => esc_html__( 'HomePage Desktop Sidebar', 'electro' ),
            'id'            => 'homepage-desktop-sidebar-widgets',
            'description'   => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'HomePage Mobile Sidebar', 'electro' ),
            'id'            => 'homepage-mobile-sidebar-widgets',
            'description'   => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
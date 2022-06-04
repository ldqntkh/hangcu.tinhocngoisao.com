<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !function_exists('hc_header_top_right_menu') ) {
    /**
     * Displays Top Bar
     */
    function hc_header_top_right_menu() { ?>

        <div class="top-bar">
            <div class="container">
            <?php 
                // wp_nav_menu( array(
                //     'theme_location'    => 'topbar-left',
                //     'container'         => false,
                //     'depth'             => 2,
                //     'menu_class'        => 'nav nav-inline pull-left electro-animate-dropdown flip',
                //     'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                //     'walker'            => new wp_bootstrap_navwalker()
                // ) );

                wp_nav_menu( array(
                    'theme_location'    => 'topbar-right',
                    'container'         => false,
                    'depth'             => 1,
                    'menu_class'        => 'nav nav-inline pull-right electro-animate-dropdown flip',
                    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                    'walker'            => new wp_bootstrap_navwalker()
                ) );
            ?>
            </div>
        </div><!-- /.top-bar -->

        <?php
    }
}

/**
 * header search
 */
if( !function_exists( 'hc_navbar_search' ) ) {
    function hc_navbar_search() {
        require ( INC_PATH . '/templates/header/nav-search.php' );
    }
}

/**
 * header account menu
 */
if( !function_exists( 'hc_header_account_menu' ) ) {
    function hc_header_account_menu() {

    }
}

/**
 * Department menu
 */
if( !function_exists('hc_departments_menu') ) {
    function hc_departments_menu() {
        $calling_action = current_filter();

        if ( 'electro_navbar_v2' === $calling_action ) {
        
            $theme_location = 'departments-menu';
            $menu_title     = apply_filters( 'electro_departments_menu_title', esc_html__( 'Shop by Department', 'electro' ) );
            $menu_icon      = apply_filters( 'electro_departments_menu_icon', 'ec ec-menu' );
            $menu_title     = '<i class="departments-menu-v2-icon ' . esc_attr( $menu_icon ) . '"></i>'. $menu_title;
        
        } else {
            
            $theme_location = 'all-departments-menu';
            $menu_title     = apply_filters( 'electro_vertical_menu_title', wp_kses_post( 'All Departments', 'electro' ) );
            $menu_icon      = apply_filters( 'electro_vertical_menu_icon', 'fa fa-list-ul' );
            $menu_title     = '<i class="departments-menu-v2-icon ' . esc_attr( $menu_icon ) . '"></i>' . $menu_title;
        }

        $enable_dropdown = true;
        
        if ( is_page_template( 'template-homepage-v1.php' ) || is_page_template( 'template-homepage-v2.php' ) ) {
            $enable_dropdown = false;
        }

        $enable_dropdown = apply_filters( 'electro_departments_menu_v2_enable_dropdown', $enable_dropdown );
        
        ?><div class="departments-menu-v2">
            <div class="dropdown <?php if ( ! $enable_dropdown ):?>show-dropdown<?php endif; ?>">
                <a href="#" class="departments-menu-v2-title" <?php if ( $enable_dropdown ) : ?>data-toggle="dropdown"<?php endif; ?>>
                    <span><?php echo wp_kses_post( $menu_title ); ?></span>
                </a>
                <?php
                    wp_nav_menu( array(
                        'theme_location'    => $theme_location,
                        'container'         => false,
                        'menu_class'        => 'dropdown-menu yamm',
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new wp_bootstrap_navwalker(),
                    ) );
                ?>
            </div>
        </div><?php
    }
}

/**
 * header mb row 1
 */
if( !function_exists( 'electro_header_mb_v2_row_1' ) ) {
    function electro_header_mb_v2_row_1() {
        echo '<div class="header-row-1">';
        do_action('electro_header_mb_v2_row_1');
        echo '</div>';
    }
}

/**
 * header mb row 1
 */
if( !function_exists( 'electro_header_mb_v2_row_2' ) ) {
    function electro_header_mb_v2_row_2() {
        if ( is_front_page() || is_home() ) {
            echo '<div class="header-row-2">';
            do_action('electro_header_mb_v2_row_2');
            echo '</div>';
        }
        
    }
}

/**
 * header menu
 */
if( !function_exists('hc_handle_nav_menu') ) {
    function hc_handle_nav_menu() { ?>
        <div class="off-canvas-navbar-toggle-buttons clearfix">
            <button class="navbar-toggler navbar-toggle-hamburger " type="button">
                <i class="ec ec-menu"></i>
            </button>
            <button class="navbar-toggler navbar-toggle-close " type="button">
                <i class="ec ec-close-remove"></i>
            </button>
        </div>
    <?php }
}

/**
 * menu
 */
if( !function_exists('hc_canvas_navigation') ) {
    function hc_canvas_navigation() { ?>
        <div class="off-canvas-navigation" id="default-oc-header">
            <?php
                // render account detail
                $current_user = wp_get_current_user();
            ?>
                <div class="mb-nav-user">
                    <div id="mb-nav-user" class="hide"></div>
                    <div class="account-logo">
                        <i></i>
                    </div>
                    <div class="user-content">
                    <?php
                        if( !$current_user->ID ) : ?>
                            <a href="<?= get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>" type="login" id="login"><?= __('Đăng nhập', 'hangcu') ?></a>
                            <a href="<?= get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>?type=register" type="register" id="register"><?= __('Đăng ký', 'hangcu') ?></a>
                        <?php else : ?>
                            <a href="<?= get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>" class="none-border" ><?= __('Xin chào: ', 'hangcu') . $current_user->display_name ?></a>
                        <?php endif;
                    ?>
                    </div>
                </div>
            <?php

                wp_nav_menu( array(
                    'theme_location'    => 'mobile-handheld-department',
                    'container'         => false,
                    'menu_class'        => 'nav nav-inline yamm',
                    'fallback_cb'       => 'electro_handheld_nav_fallback',
                    'walker'            => new wp_bootstrap_navwalker()
                ) );
            ?>
        </div>
    <?php }
}

if( !function_exists('hc_off_canvas_nav') ) {
    function hc_off_canvas_nav() {
        $classes = '';
        if( apply_filters( 'electro_off_canvas_nav_hide_in_desktop', false ) ) {
            $classes = 'off-canvas-hide-in-desktop';
        }
        ?>
        <div class="off-canvas-navigation-wrapper <?php echo esc_attr( $classes ); ?>">
            <div class="off-canvas-navbar-toggle-buttons clearfix">
                <button class="navbar-toggler navbar-toggle-hamburger " type="button">
                    <i class="ec ec-menu"></i>
                </button>
            </div>

            <div id="desktop-small-menu">
                <button class="navbar-toggler navbar-toggle-close " type="button">
                    <i class="ec ec-close-remove"></i>
                </button>
                <div class="lst-items">
                <?php
                    // render menu
                    $theme_locations = get_nav_menu_locations();

                    $menu_obj = get_term( $theme_locations['hand-held-nav'], 'nav_menu' );
                    $nav_menu = wp_get_nav_menu_items( $menu_obj->term_id, array('menu_item_parent' => "0") );
                    if( !empty( $nav_menu ) ) {
                        foreach( $nav_menu as $nav_item ): 
                            if( $nav_item->menu_item_parent == '0' ):
                        ?>
                            <a href="<?= $nav_item->url ?>"><i class="<?= $nav_item->icon ?>"></i> <h3><?= $nav_item->title ?></h3> </a>
                        <?php endif; 
                        endforeach;
                    }
                ?>
                </div>
            </div>
        </div>
        <?php
    }
}
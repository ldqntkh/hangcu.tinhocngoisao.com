<?php
/**
 * The template for displaying the homepage.
 * Template Name: Template Mobile Homepage
 *
 * @package electro-child
 */
// if( !wp_is_mobile() ) {
//     wp_redirect(home_url(), 301);
//     die;
// }

remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );
// electro_before_header
do_action( 'hangcu_before_homepage_v1' );

electro_get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php 
				dynamic_sidebar('homepage-mobile-sidebar-widgets');
			?>
        </main><!-- #main -->
    </div><!-- #primary -->

    <?php
get_footer( $footer_style ); 

?>

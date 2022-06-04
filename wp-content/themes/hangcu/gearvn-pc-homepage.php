<?php
/**
 * The template for displaying the homepage.
 * Template Name: Template PC Homepage
 *
 * @package electro-child
 */

remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );
// electro_before_header
do_action( 'hangcu_before_homepage_v1' );

electro_get_header(); ?>

	<div id="primary" class="content-area hc-home">
		<main id="main" class="site-main">

			<?php 
				dynamic_sidebar('homepage-desktop-sidebar-widgets');
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php 

get_footer();
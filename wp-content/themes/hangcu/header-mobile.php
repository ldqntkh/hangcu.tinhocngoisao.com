<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package electro
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="off-canvas-wrapper">
<div id="page" class="hfeed site">
    <?php
    /**
     * @hooked electro_skip_links - 0
     * @hooked electro_top_bar - 10
     */
    do_action( 'electro_before_header_mb' ); ?>

    <header id="masthead" class="header-v2 stick-this site-header
                                <?php if ( is_front_page() || is_home() ) echo ' content-homepage ' ?>
                                <?php if ( ! apply_filters( 'electro_show_header_support_info', true ) ) { echo esc_attr( ' no-header-support-info' ); } ?>">
        <div class="bg-header-mb">
            <?php
                if ( is_front_page() || is_home() ) {
                    echo '<div class="bg-red"></div>';
                }
            ?>

            <div class="bg-content-header">
                <?php
                /**
                 * @hooked electro_masthead_v2 - 10
                 * @hooked electro_navbar_v2   - 20
                 */
                do_action( 'electro_header_mb_v2' ); ?>
            </div>
        </div>
        <!-- <div class="container">
            <?php
            /**
             * @hooked electro_masthead_v2 - 10
             * @hooked electro_navbar_v2   - 20
             */
            // do_action( 'electro_header_mb_v2' ); ?>

        </div> -->

        <?php
        /**
         * @hooked electro_handheld_header - 10
         */
        do_action( 'electro_after_header_mb' ); ?>

    </header><!-- #masthead -->

    <?php
    /**
     * @hooked electro_navbar - 10
     */
    do_action( 'electro_before_content' ); ?>

    <div id="content" class="site-content" tabindex="-1" <?php if ( is_front_page() || is_home() ) echo ' style="margin-top: 50px" ' ?>>
        <div class="container">
        <?php
        /**
         * @hooked woocommerce_breadcrumb - 10
         */
        do_action( 'electro_content_top' ); ?>

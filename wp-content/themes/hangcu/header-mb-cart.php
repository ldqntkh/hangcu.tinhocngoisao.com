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
<header>
    <div class="mb-nav-cart">
        <i class="icon-back"></i>
        <h3><?= __('Giỏ hàng', 'hangcu') ?></h3>
    </div>
</header>
<div class="off-canvas-wrapper">
<div id="page" class="hfeed site">
    
    
    <?php
    /**
     * @hooked electro_navbar - 10
     */
    // do_action( 'electro_before_content' ); ?>

    <div id="content" class="site-content" tabindex="-1" <?php if ( is_front_page() || is_home() ) echo ' style="margin-top: 50px" ' ?>>
        <div class="container">
        <?php
        /**
         * @hooked woocommerce_breadcrumb - 10
         */
        do_action( 'electro_content_top' ); ?>

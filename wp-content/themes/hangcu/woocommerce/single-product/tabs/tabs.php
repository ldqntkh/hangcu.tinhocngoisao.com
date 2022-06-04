<?php

/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
echo '<div class="product-contents">';
if ( ! empty( $tabs ) ) : ?>
    <?php foreach ( $tabs as $key => $tab ) : 
        if ($key == 'description') :
            echo '<div class="text-detail">';
            if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); }
            echo '</div>';
            // echo '<hr/>';
            /**
             * @hooked display_product_tab_compare - 10
             */
            // do_action('hangcu_product_tab_compare');

            /**
             * @hooked woocommerce_upsell_display - 10
             * @hooked electro_output_related_products - 10
             */
            do_action('hangcu_related_product');
        elseif ($key == 'reviews') :
            if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); }
        endif;
    ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php 
    echo '</div>';
?>

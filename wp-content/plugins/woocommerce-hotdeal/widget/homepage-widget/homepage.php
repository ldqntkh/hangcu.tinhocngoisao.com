<?php

    function wg_hotdeal_homepage_init() {
        //if ( woocommerce_is_active() ) :
            require_once SALE_DATE_DIR . '/widget/homepage-widget/home-show-hot-deal.php';
            // require_once SALE_DATE_DIR . '/widget/homepage-widget/home-show-hot-deal-mobile.php';

            // register_widget( 'ShowListProductSaleOnMobile' );
            register_widget( 'ShowListProductSale' );
        //endif;
    }

    // init widget
    add_action('widgets_init', 'wg_hotdeal_homepage_init');

?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// header
include_once ( INC_PATH . '/template-tags/headers/remove-header-hooks.php' );
include_once ( INC_PATH . '/template-tags/headers/add-header-hooks.php' );
// body
include_once ( INC_PATH . '/template-tags/body/body-template-hooks.php' );

// loop
include_once ( INC_PATH . '/template-tags/loop/remove-hook-products.php' );
include_once ( INC_PATH . '/template-tags/loop/add-hook-products.php' );

//footer
include_once ( INC_PATH . '/template-tags/footer/remove-hook-footer-widgets.php' );
include_once ( INC_PATH . '/template-tags/footer/add-hook-footer-widgets.php' );

// categories
include_once ( INC_PATH . '/template-tags/categories/remove-categories-hook.php' );
include_once ( INC_PATH . '/template-tags/categories/add-categories-hook.php' );

// product
include_once ( INC_PATH . '/template-tags/product/product-add-hooks.php' );

// account
include_once ( INC_PATH . '/template-tags/account/account.php' );

// order
include_once ( INC_PATH . '/template-tags/order/order-add-hooks.php' );

// cart 
include_once ( INC_PATH . '/template-tags/cart/init-cart-template-tags.php' );

// admin
include_once ( INC_PATH . '/template-tags/admin/init-admin-template-tags.php' );

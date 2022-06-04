<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// header
include_once ( INC_PATH . '/template-hooks/headers/header-menu-hook.php' );
include_once ( INC_PATH . '/template-hooks/headers/header-account-hook.php' );
include_once ( INC_PATH . '/template-hooks/headers/header-cart-hook.php' );
include_once ( INC_PATH . '/template-hooks/headers/header-checkout-hook.php' );

// body
include_once ( INC_PATH . '/template-hooks/body/body-template-functions.php' );
// loop
include_once ( INC_PATH . '/template-hooks/loop/products-hook-functions.php' );

// categories
include_once ( INC_PATH . '/template-hooks/categories/category-breadcrumb-functions.php' );

// product
include_once ( INC_PATH . '/template-hooks/product/hangcu-single-product.php' );


// product
include_once ( INC_PATH . '/template-hooks/checkout/checkout-hooks.php' );

// account
include_once ( INC_PATH . '/template-hooks/account/account.php' );

// order
include_once ( INC_PATH . '/template-hooks/order/order-functions.php' );

// cart
include_once ( INC_PATH . '/template-hooks/cart/cart-hook-functions.php' );

// admin
include_once ( INC_PATH . '/template-hooks/admin/init-admin-template-functions.php' );

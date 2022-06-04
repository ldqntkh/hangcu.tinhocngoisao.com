<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ADMIN PAGE

if ( is_admin() ){
    require INC_PATH . '/admin/hangcu-class-electro-admin.php';
    require INC_PATH . '/admin/category/hangcu-category-hooks.php';
    require INC_PATH . '/admin/category/hangcu-category-functions.php';
    // require INC_PATH . '/admin/order/hangcu-order-hooks.php';
    // require INC_PATH . '/admin/order/hangcu-order-functions.php';
    // PLP
    // require INC_PATH . '/admin/PLP/hangcu-plp-hooks.php';
    // require INC_PATH . '/admin/PLP/hangcu-plp-functions.php';

    // edit login admin page
    require INC_PATH . '/admin/template/hangcu-admin-template-hooks.php';
    require INC_PATH . '/admin/template/hangcu-admin-template-functions.php';

    // PDP
    require INC_PATH . '/admin/product/product-hooks.php';
}

// rest-api
require INC_PATH . '/api/global/gb-init.php';

// My Account
require INC_PATH . '/api/account/my-address.php';
// Product
require INC_PATH . '/api/product/product.php';
require INC_PATH . '/api/woo_custom_rest_api.php';

// webhooks
require INC_PATH . '/webhooks/new-order.php';
require INC_PATH . '/webhooks/update-order-payment-status.php';
require INC_PATH . '/webhooks/tracking-order.php';

/**
 * Khai báo các hook cần thiết 
 * Remove các hook mặc định của theme
 * Folder template tags quản lý các định danh hooks
 * Folder template hooks quản lý các function hooks
 */
include_once( INC_PATH . '/template-hooks/init-template-hooks.php' );
include_once( INC_PATH . '/template-tags/init-template-tags.php' );

// STYLE
include_once( INC_PATH . '/style/init-style.php' );

// FUNCTIONS
require INC_PATH . '/functions/hangcu-home.php';
require INC_PATH . '/functions/custom-function.php';

// rewrite rule url
require INC_PATH . '/url/url-template-hooks.php';
require INC_PATH . '/url/url-template-functions.php';

// hook order
require INC_PATH . '/emails/order/hangcu-order-functions.php';
require INC_PATH . '/emails/order/hangcu-order-hooks.php';

// shortcode
require INC_PATH . '/shortcodes/hangcu-shortcode-tracking-order.php';
remove_shortcode( 'woocommerce_order_tracking' );
add_shortcode( 'hangcu_order_tracking', array( 'CT_Shortcode_Order_Tracking', 'output' ));
add_action( 'wp_head', function() {?>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<?php }, 10 );

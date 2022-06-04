<?php
// account
require_once HC_API_PLUGIN_DIR . '/functions/account/get_account_info.php';
require_once HC_API_PLUGIN_DIR . '/functions/account/update_account_info.php';
require_once HC_API_PLUGIN_DIR . '/functions/account/logout-account.php';

// orders
require_once HC_API_PLUGIN_DIR . '/functions/order/get-list-order.php';
require_once HC_API_PLUGIN_DIR . '/functions/order/get-order-detail.php';
require_once HC_API_PLUGIN_DIR . '/functions/order/buy-again-order.php';
require_once HC_API_PLUGIN_DIR . '/functions/order/request-cancel-order.php';

// address
require_once HC_API_PLUGIN_DIR . '/functions/address/get-list-address.php';
require_once HC_API_PLUGIN_DIR . '/functions/address/addnew-address.php';
require_once HC_API_PLUGIN_DIR . '/functions/address/update-address.php';
require_once HC_API_PLUGIN_DIR . '/functions/address/delete-address.php';
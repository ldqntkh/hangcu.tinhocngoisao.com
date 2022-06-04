<?php
// account info
add_action("wp_ajax_hc_get_account_info", "hc_get_account_info");
add_action("wp_ajax_hc_update_account_info", "hc_update_account_info");
add_action("wp_ajax_hc_logout_account", "hc_logout_account");

// address
add_action("wp_ajax_hc_get_list_address", "hc_get_list_address");
add_action("wp_ajax_hc_add_new_address", "hc_add_new_address");
add_action("wp_ajax_hc_update_address", "hc_update_address");
add_action("wp_ajax_hc_delete_address", "hc_delete_address");
// get list cities
// action wp_ajax_load_diagioihanhchinh


// order
add_action("wp_ajax_hc_get_list_orders", "hc_get_list_orders");
add_action("wp_ajax_hc_get_order_detail", "hc_get_order_detail");
add_action("wp_ajax_hc_re_buy_order", "hc_re_buy_order");
add_action("wp_ajax_hc_pending_cancel_order", "hc_pending_cancel_order");
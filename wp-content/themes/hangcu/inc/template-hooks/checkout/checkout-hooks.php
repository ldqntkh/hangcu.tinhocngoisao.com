<?php
add_action( 'hangcu_custom_template_checkout_payment', 'hangcu_show_order_notes', 10 );
add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form_js', 10 );
add_action( 'hangcu_custom_template_checkout_payment', 'hangcu_vat_form', 10 );
add_action('woocommerce_checkout_order_processed', 'hangcu_save_vat_info',20);
add_action('woocommerce_checkout_order_processed', 'hangcu_save_tracking_at',20);
add_action('woocommerce_checkout_order_processed', 'hangcu_save_depot_info',20);
add_action('woocommerce_after_checkout_validation', 'hangcu_validate_checkout');
add_action('woocommerce_after_checkout_validation', 'hangcu_validate_vat_info');
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'hangcu_empty_cart', 10 );

// Conditionally changing the shipping methods costs
add_filter( 'woocommerce_package_rates','conditional_custom_shipping_cost', 90, 2 );
// Enabling, disabling and refreshing session shipping methods data
add_action( 'hangcu_checkout_update_order_review', 'refresh_shipping_methods', 1, 1 );
add_action( 'woocommerce_checkout_update_order_review', 'refresh_shipping_methods', 10, 1 );

// payment 
add_filter( 'woocommerce_available_payment_gateways', 'check_valid_payment_methods', 10, 1 );
add_action( 'woocommerce_checkout_order_processed', 'check_valid_payment_method_processing_order', 10, 3 );

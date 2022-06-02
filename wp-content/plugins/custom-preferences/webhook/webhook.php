<?php
    add_action( 'admin_init', 'custom_preferences_webhook_init' );
    define( 'webhookOptions', get_option( 'custom_preferences_webhook_options' ) );

    function custom_preferences_webhook_init() {
        register_setting( 'custom_preferences_webhook_options', 'custom_preferences_webhook_options' );
        add_settings_section( 'configuration_webhook', __( 'Cấu hình thông báo trả góp', 'online-shop' ), 'configuration_section_hook', 'custom_preferences_webhook' );
        // enable facebook functions
        add_settings_field( 'enable_webhook_order_created', __( 'Kích hoạt webhook tạo đơn hàng', 'online-shop' ), 'enable_webhook_order_created_section', 'custom_preferences_webhook', 'configuration_webhook' );

        add_settings_field( 'order_created_delivery_url', __( 'Url hook tạo đơn hàng', 'online-shop' ), 'order_created_delivery_url_section', 'custom_preferences_webhook', 'configuration_webhook' );

    }

    function configuration_section_hook() {
        echo '<p>' . __( 'Cấu hình thông báo cho webhook.', 'online-shop' ) . '</p>';
    }

    function enable_webhook_order_created_section() {
        $enable_webhook_order_created = isset( webhookOptions['enable_webhook_order_created'] ) ? trim( webhookOptions['enable_webhook_order_created'] ) : '';
        $checked = '';
        if ($enable_webhook_order_created) $checked="checked";
        echo "<input type='checkbox' name='custom_preferences_webhook_options[enable_webhook_order_created]' value='true' {$checked}/>" ;
    }

    function order_created_delivery_url_section() {
        $order_created_delivery_url = isset( webhookOptions['order_created_delivery_url'] ) ? trim( webhookOptions['order_created_delivery_url'] ) : '';
        echo '<input type="text" size="200" name="custom_preferences_webhook_options[order_created_delivery_url]" value="'. $order_created_delivery_url .'"/>';
    }
?>
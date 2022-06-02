<?php
    add_action( 'admin_init', 'custom_preferences_global_init' );

    function custom_preferences_global_init() {
        // config global
        register_setting( 'custom_preferences_options', 'custom_preferences_options' );
        add_settings_section( 'configuration_main', 'Configuration Settings', 'configuration_section_title', 'custom_preferences' );
        add_settings_field( 'fb_appId', 'Facebook App ID', 'fb_app_id_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'render_chatbox', 'Render Chat Box By Script', 'render_chatbox_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'google_map_key', 'Google map key', 'google_map_key_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'list_address_store', 'Danh sách địa chỉ showroom', 'list_address_store_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'list_product_type', 'Cấu hình buildPC', 'list_product_type_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'list_product_sale_price', 'Cấu hình danh sách bảng giá bán', 'list_product_sale_price_section', 'custom_preferences', 'configuration_main' );
        add_settings_field( 'render_footer_script', 'Render custom script in footer', 'render_script_footer', 'custom_preferences', 'configuration_main' );
    }

    function fb_app_id_section() {
        $facebookAppID = get_option( 'custom_preferences_options' )['fb_appId'];
        echo "<input type='text' id='fb_app_id' name='custom_preferences_options[fb_appId]' size='40' value='{$facebookAppID}' />";
    }

    function render_chatbox_section() {
        $renderChatbox = get_option( 'custom_preferences_options' )['render_chatbox'];
        echo "<textarea name='custom_preferences_options[render_chatbox]' cols='60' rows='10'>{$renderChatbox}</textarea>";
    }

    function google_map_key_section() {
        $google_map_key = get_option( 'custom_preferences_options' )['google_map_key'];
        echo "<input type='text' id='google_map_key' name='custom_preferences_options[google_map_key]' size='40' value='{$google_map_key}' />";
    }

    function list_address_store_section() {
        $list_address_store = get_option( 'custom_preferences_options' )['list_address_store'];
        echo "<textarea name='custom_preferences_options[list_address_store]' cols='60' rows='10'>{$list_address_store}</textarea>";
    }

    function list_product_type_section() {
        $list_product_type = get_option( 'custom_preferences_options' )['list_product_type'];
        echo "<textarea name='custom_preferences_options[list_product_type]' cols='60' rows='10'>{$list_product_type}</textarea>";
    }

    function render_script_footer() {
        $script_footer = get_option( 'custom_preferences_options' )['render_footer_script'];
        echo "<textarea name='custom_preferences_options[render_footer_script]' cols='60' rows='10'>{$script_footer}</textarea>";
    }

    function configuration_section_title() {
        echo '<p>These configuration is used in storefront.</p>';
    }

    function list_product_sale_price_section() {
        $list_product_sale_price = get_option( 'custom_preferences_options' )['list_product_sale_price'];
        echo "<textarea name='custom_preferences_options[list_product_sale_price]' cols='60' rows='10'>{$list_product_sale_price}</textarea>";
    }
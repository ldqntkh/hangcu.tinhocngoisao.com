<?php

    add_action( 'admin_init', 'custom_preferences_global_init' );
    function custom_preferences_global_init() {
        // config global
        register_setting( CUSTOM_PREFERECE_GLOBAL, CUSTOM_PREFERECE_GLOBAL );
        add_settings_section( CUSTOM_PREFERECE_GLOBAL, 'Configuration Settings', 'configuration_section_title', CUSTOM_PREFERECE_GLOBAL );
        add_settings_field( 'config_capabilities', 'Config Capabilities', 'config_capabilities', CUSTOM_PREFERECE_GLOBAL, CUSTOM_PREFERECE_GLOBAL );
        add_settings_field( 'config_not_found_image', 'Image not found', 'config_not_found_image', CUSTOM_PREFERECE_GLOBAL, CUSTOM_PREFERECE_GLOBAL );
        add_settings_field( 'render_footer_script', 'Render custom script in footer', 'render_script_footer', CUSTOM_PREFERECE_GLOBAL, CUSTOM_PREFERECE_GLOBAL  );
    }

    function configuration_section_title() {
        echo '<p>These configuration is used in storefront and BM.</p>';
    }
    
    function config_capabilities() {
        $configCaps = get_option( CUSTOM_PREFERECE_GLOBAL )['config_capabilities'];
        echo "<textarea name='custom_preferences_global[config_capabilities]' cols='60' rows='10'>{$configCaps}</textarea>";
    }

    function config_not_found_image() {
        $imgNotFound = get_option( CUSTOM_PREFERECE_GLOBAL )['config_not_found_image'];

        echo '<input type="text" name="custom_preferences_global[config_not_found_image]" class="regular-text image_url" value="' . $imgNotFound . '">';
        echo '<input type="button" name="upload-btn" class="button-secondary upload-btn" value="Upload Image">';

        //echo "<input name='custom_preferences_global[config_not_found_image]' type='text' value='{$imgNotFound}' style='max-width: 400px;width: 100%;'/>";
    }

    function render_script_footer() {
        $script_footer = get_option( CUSTOM_PREFERECE_GLOBAL )['render_footer_script'];
        echo "<textarea name='custom_preferences_global[render_footer_script]' cols='60' rows='10'>{$script_footer}</textarea>";
    }
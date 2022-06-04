<?php
    add_action( 'admin_init', 'custom_preferences_facebook_init' );

    function custom_preferences_facebook_init() {
        // config facebook
        register_setting( CUSTOM_PREFERECE_FACEBOOK, CUSTOM_PREFERECE_FACEBOOK );
        // facebook chat
        add_settings_section( CUSTOM_PREFERECE_FACEBOOK, 'Configuration Settings for Facebook Chat', 'configuration_section_facebook_chat', CUSTOM_PREFERECE_FACEBOOK );

        add_settings_field( 'facebook_enable_chat', 'Enable Chat Facebook', 'facebook_enable_chat_section', CUSTOM_PREFERECE_FACEBOOK, CUSTOM_PREFERECE_FACEBOOK );

        add_settings_field( 'facebook_script_chat', 'Facebook Script', 'facebook_script_section', CUSTOM_PREFERECE_FACEBOOK, CUSTOM_PREFERECE_FACEBOOK );
    }

    function configuration_section_facebook_chat() {
        echo '<p>These configuration for function Facebook chat.</p>';
    }

    function facebook_enable_chat_section() {
        $facebookEnable = isset(get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_enable_chat']) ? get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_enable_chat'] : false;
        $checked = '';
        if ($facebookEnable) $checked="checked";
        echo "<input type='checkbox' id='facebook_enable_chat' name='custom_preferences_facebook[facebook_enable_chat]' value='true' {$checked}/>";
    }

    function facebook_script_section() {
        $facebook_script_chat = isset(get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_script_chat']) ? get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_script_chat'] : "";
        echo "<textarea name='custom_preferences_facebook[facebook_script_chat]' cols='60' rows='10'>{$facebook_script_chat}</textarea>";
    }
    
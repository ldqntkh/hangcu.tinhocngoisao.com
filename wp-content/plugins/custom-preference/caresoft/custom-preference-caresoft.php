<?php
    add_action( 'admin_init', 'custom_preferences_caresoft_init' );

    function custom_preferences_caresoft_init() {
        register_setting( CUSTOM_PREFERECE_CARESOFT, CUSTOM_PREFERECE_CARESOFT );
        
        add_settings_section( CUSTOM_PREFERECE_CARESOFT, 'Configuration Settings for Caresoft Chat', 'configuration_section_caresoft_chat', CUSTOM_PREFERECE_CARESOFT );

        add_settings_field( 'caresoft_enable_chat', 'Enable Chat Caresoft', 'caresoft_enable_chat_section', CUSTOM_PREFERECE_CARESOFT, CUSTOM_PREFERECE_CARESOFT );

        add_settings_field( 'caresoft_script_chat', 'Caresoft Script', 'caresoft_script_section', CUSTOM_PREFERECE_CARESOFT, CUSTOM_PREFERECE_CARESOFT );
    }

    function configuration_section_caresoft_chat() {
        echo '<p>These configuration for function Caresoft chat.</p>';
    }

    function caresoft_enable_chat_section() {
        $caresoftEnable = isset(get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_enable_chat']) ? get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_enable_chat'] : false;
        $checked = '';
        if ($caresoftEnable) $checked="checked";
        echo "<input type='checkbox' id='caresoft_enable_chat' name='custom_preferences_caresoft[caresoft_enable_chat]' value='true' {$checked}/>";
    }

    function caresoft_script_section() {
        $caresoft_script_chat = isset(get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_script_chat']) ? get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_script_chat'] : "";
        echo "<textarea name='custom_preferences_caresoft[caresoft_script_chat]' cols='60' rows='10'>{$caresoft_script_chat}</textarea>";
    }
    
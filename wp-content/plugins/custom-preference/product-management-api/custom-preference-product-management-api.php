<?php

    add_action( 'admin_init', 'custom_preferences_prm_init' );
    function custom_preferences_prm_init() {
        // config global
        register_setting( CUSTOM_PREFERECE_PRM, CUSTOM_PREFERECE_PRM );
        add_settings_section( CUSTOM_PREFERECE_PRM, 'Configuration Settings', 'configuration_section_title_prm', CUSTOM_PREFERECE_PRM );
        add_settings_field( 'config_enable_api_fee', 'On/Off Api', 'config_enable_api_fee', CUSTOM_PREFERECE_PRM, CUSTOM_PREFERECE_PRM );
        add_settings_field( 'config_api_mapping_city', 'Api mapping city', 'config_api_mapping_city', CUSTOM_PREFERECE_PRM, CUSTOM_PREFERECE_PRM );
        add_settings_field( 'config_api_get_shipping_fee', 'Api get shipping fee', 'config_api_get_shipping_fee', CUSTOM_PREFERECE_PRM, CUSTOM_PREFERECE_PRM );
    }

    function configuration_section_title_prm() {
        echo '<p>These configuration api is used in storefront.</p>';
    }

    function config_enable_api_fee() {
        $config_enable_api_fee = get_option( CUSTOM_PREFERECE_PRM )['config_enable_api_fee'];
        $checked = '';
        if ( $config_enable_api_fee ) {
            $checked = 'checked';
        }
        echo "<input type='checkbox' name='".CUSTOM_PREFERECE_PRM."[config_enable_api_fee]' value='true' ".$checked." />";
    }
    
    function config_api_mapping_city() {
        $config_api_mapping_city = get_option( CUSTOM_PREFERECE_PRM )['config_api_mapping_city'];
        echo "<input style='width:100%' name='".CUSTOM_PREFERECE_PRM."[config_api_mapping_city]' value='".$config_api_mapping_city."' />";
    }

    function config_api_get_shipping_fee() {
        $config_api_get_shipping_fee = get_option( CUSTOM_PREFERECE_PRM )['config_api_get_shipping_fee'];
        echo "<input style='width:100%' name='".CUSTOM_PREFERECE_PRM."[config_api_get_shipping_fee]' value='".$config_api_get_shipping_fee."' />";
    }
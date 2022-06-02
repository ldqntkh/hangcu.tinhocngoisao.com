<?php
    add_action( 'admin_init', 'custom_preferences_zalo_init' );

    function custom_preferences_zalo_init() {
        // config zalo
        register_setting( 'custom_preferences_zalo_options', 'custom_preferences_zalo_options' );
        add_settings_section( 'configuration_zalo', 'Configuration Settings for Zalo', 'configuration_section_zalo', 'custom_preferences_zalo' );
        // enable zalo functions
        add_settings_field( 'zalo_enable', 'Enable Zalo', 'zalo_enable_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_appId', 'Zalo App ID', 'zalo_app_id_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_script_url', 'Zalo script url', 'zalo_app_script_url_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_script_callback', 'Zalo script callback name', 'zalo_app_script_callback_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_script_callback_func', 'Zalo script callback function', 'zalo_app_script_callback_function_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_script_layout', 'Zalo layout', 'zalo_app_layout_section', 'custom_preferences_zalo', 'configuration_zalo' );

        add_settings_field( 'zalo_button_color', 'Zalo button color', 'zalo_button_section', 'custom_preferences_zalo', 'configuration_zalo' );
    }

    function configuration_section_zalo() {
        echo '<p>These configuration for function share Zalo.</p>';
    }

    function zalo_enable_section() {
        $zaloEnable = isset(get_option( 'custom_preferences_zalo_options' )['zalo_enable']) ? get_option( 'custom_preferences_zalo_options' )['zalo_enable'] : false;
        $checked = '';
        if ($zaloEnable) $checked="checked";
        echo "<input type='checkbox' id='zalo_enable' name='custom_preferences_zalo_options[zalo_enable]' value='true' {$checked}/>";
    }

    function zalo_app_id_section() {
        $zaloAppID = isset(get_option( 'custom_preferences_zalo_options' )['zalo_appId']) ? get_option( 'custom_preferences_zalo_options' )['zalo_appId'] : "";
        echo "<input type='text' id='zalo_appId' name='custom_preferences_zalo_options[zalo_appId]' size='40' value='{$zaloAppID}' />";
    }

    function zalo_app_script_url_section() {
        $zaloScriptUrl = isset(get_option( 'custom_preferences_zalo_options' )['zalo_script_url']) ? get_option( 'custom_preferences_zalo_options' )['zalo_script_url'] : "";
        echo "<input type='text' id='zalo_script_url' name='custom_preferences_zalo_options[zalo_script_url]' value='{$zaloScriptUrl}' />";
    }

    function zalo_app_script_callback_section() {
        $zaloScriptCallback = isset(get_option( 'custom_preferences_zalo_options' )['zalo_script_callback']) ? get_option( 'custom_preferences_zalo_options' )['zalo_script_callback'] : "";
        echo "<input type='text' id='zalo_script_callback' name='custom_preferences_zalo_options[zalo_script_callback]' value='{$zaloScriptCallback}' />";
    }

    function zalo_app_script_callback_function_section() {
        $zaloScriptCallbackFunc = isset(get_option( 'custom_preferences_zalo_options' )['zalo_script_callback_func']) ? get_option( 'custom_preferences_zalo_options' )['zalo_script_callback_func'] : "";
        echo '<i>Please use the function name same with value of "Zalo script callback name"</i></br>';
        echo "<textarea name='custom_preferences_zalo_options[zalo_script_callback_func]' cols='60' rows='10'>{$zaloScriptCallbackFunc}</textarea>";
    }

    function zalo_app_layout_section() {
        $zaloLayout = isset(get_option( 'custom_preferences_zalo_options' )['zalo_script_layout']) ? get_option( 'custom_preferences_zalo_options' )['zalo_script_layout'] : "1";
    ?>
        <select id="zalo_script_layout" name="custom_preferences_zalo_options[zalo_script_layout]">
            <option value="1" <?php if ($zaloLayout == "1") echo "selected"; ?>>Layout 1</option>
            <option value="2" <?php if ($zaloLayout == "2") echo "selected"; ?>>Layout 2</option>
            <option value="3" <?php if ($zaloLayout == "3") echo "selected"; ?>>Layout 3</option>
            <option value="4" <?php if ($zaloLayout == "4") echo "selected"; ?>>Layout 4</option>
            <option value="5" <?php if ($zaloLayout == "5") echo "selected"; ?>>Layout 5</option>
        </select>
    <?php }

    function zalo_button_section() {
        $zaloButtonColor = isset(get_option( 'custom_preferences_zalo_options' )['zalo_button_color']) ? get_option( 'custom_preferences_zalo_options' )['zalo_button_color'] : "blue";
    ?>
        <select id="zalo_button_color" name="custom_preferences_zalo_options[zalo_button_color]">
            <option value="blue" <?php if ($zaloButtonColor == "blue") echo "selected"; ?>>Blue</option>
            <option value="white" <?php if ($zaloButtonColor == "white") echo "selected"; ?>>White</option>
        </select>
    <?php }
<?php
    add_action( 'admin_init', 'custom_preferences_zalo_init' );

    function custom_preferences_zalo_init() {
        // config zalo
        register_setting( CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_section( CUSTOM_PREFERECE_ZALO, 'Configuration Settings for Zalo', 'configuration_section_zalo', CUSTOM_PREFERECE_ZALO );
        // enable zalo functions
        add_settings_field( 'zalo_enable_share', 'Enable Share Zalo', 'zalo_enable_share_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_appId', 'Zalo App ID', 'zalo_app_id_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_script_url', 'Zalo script url', 'zalo_app_script_url_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_script_callback', 'Zalo script callback name', 'zalo_app_script_callback_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_script_callback_func', 'Zalo script callback function', 'zalo_app_script_callback_function_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_script_layout', 'Zalo layout', 'zalo_app_layout_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_button_color', 'Zalo button color', 'zalo_button_section', CUSTOM_PREFERECE_ZALO, CUSTOM_PREFERECE_ZALO );

        // zalo chat
        add_settings_section( 'configuration_zalo_chat', 'Configuration Settings for Zalo Chat', 'configuration_section_zalo_chat', CUSTOM_PREFERECE_ZALO );

        add_settings_field( 'zalo_enable_chat', 'Enable Chat Zalo', 'zalo_enable_chat_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_OAId', 'Zalo OA ID', 'zalo_oa_id_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_OAMsg', 'Zalo OA Message', 'zalo_oa_msg_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_second_display', 'Zalo Second display chat', 'zalo_seconde_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_width_popup', 'Zalo Width Popup', 'zalo_width_popup_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_height_popup', 'Zalo Height Popup', 'zalo_height_popup_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );

        add_settings_field( 'zalo_postion_popup', 'Zalo Popup Position', 'zalo_postion_popup_section', CUSTOM_PREFERECE_ZALO, 'configuration_zalo_chat' );
    }

    function configuration_section_zalo() {
        echo '<p>These configuration for function share Zalo.</p>';
    }
    
    function configuration_section_zalo_chat() {
        echo '<p>These configuration for function Zalo chat.</p>';
    }

    function zalo_enable_share_section() {
        $zaloEnable = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_share']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_share'] : false;
        $checked = '';
        if ($zaloEnable) $checked="checked";
        echo "<input type='checkbox' id='zalo_enable_share' name='custom_preferences_zalo[zalo_enable_share]' value='true' {$checked}/>";
    }

    function zalo_app_id_section() {
        $zaloAppID = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_appId']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_appId'] : "";
        echo "<input type='text' id='zalo_appId' name='custom_preferences_zalo[zalo_appId]' size='40' value='{$zaloAppID}' />";
    }

    function zalo_app_script_url_section() {
        $zaloScriptUrl = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_url']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_url'] : "";
        echo "<input type='text' id='zalo_script_url' name='custom_preferences_zalo[zalo_script_url]' size='40' value='{$zaloScriptUrl}' />";
    }

    function zalo_app_script_callback_section() {
        $zaloScriptCallback = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_callback']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_callback'] : "";
        echo "<input type='text' id='zalo_script_callback' name='custom_preferences_zalo[zalo_script_callback]' size='40' value='{$zaloScriptCallback}' />";
    }

    function zalo_app_script_callback_function_section() {
        $zaloScriptCallbackFunc = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_callback_func']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_callback_func'] : "";
        echo '<i>Please use the function name same with value of "Zalo script callback name"</i></br>';
        echo "<textarea name='custom_preferences_zalo[zalo_script_callback_func]' cols='60' rows='10'>{$zaloScriptCallbackFunc}</textarea>";
    }

    function zalo_app_layout_section() {
        $zaloLayout = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_layout']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_script_layout'] : "1";
    ?>
        <select id="zalo_script_layout" name="custom_preferences_zalo[zalo_script_layout]">
            <option value="1" <?php if ($zaloLayout == "1") echo "selected"; ?>>Layout 1</option>
            <option value="2" <?php if ($zaloLayout == "2") echo "selected"; ?>>Layout 2</option>
            <option value="3" <?php if ($zaloLayout == "3") echo "selected"; ?>>Layout 3</option>
            <option value="4" <?php if ($zaloLayout == "4") echo "selected"; ?>>Layout 4</option>
            <option value="5" <?php if ($zaloLayout == "5") echo "selected"; ?>>Layout 5</option>
        </select>
    <?php }

    function zalo_button_section() {
        $zaloButtonColor = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_button_color']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_button_color'] : "blue";
    ?>
        <select id="zalo_button_color" name="custom_preferences_zalo[zalo_button_color]">
            <option value="blue" <?php if ($zaloButtonColor == "blue") echo "selected"; ?>>Blue</option>
            <option value="white" <?php if ($zaloButtonColor == "white") echo "selected"; ?>>White</option>
        </select>
    <?php }


    function zalo_enable_chat_section() {
        $zaloEnable = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_chat']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_chat'] : false;
        $checked = '';
        if ($zaloEnable) $checked="checked";
        echo "<input type='checkbox' id='zalo_enable_chat' name='custom_preferences_zalo[zalo_enable_chat]' value='true' {$checked}/>";
    }

    function zalo_oa_id_section() {
        $zaloOAID = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAId']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAId'] : "";
        echo "<input type='text' id='zalo_OAId' name='custom_preferences_zalo[zalo_OAId]' size='40' value='{$zaloOAID}' />";
    }

    function zalo_oa_msg_section() {
        $zalo_OAMsg = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAMsg']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAMsg'] : "";
        echo "<input type='text' id='zalo_OAMsg' name='custom_preferences_zalo[zalo_OAMsg]' size='40' value='{$zalo_OAMsg}' />";
    }

    function zalo_seconde_section() {
        $zalo_second_display = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_second_display']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_second_display'] : "0";
        echo "<input min='0' type='number' id='zalo_second_display' name='custom_preferences_zalo[zalo_second_display]' size='40' value='{$zalo_second_display}' />";
    }

    function zalo_width_popup_section() {
        $zalo_width_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_width_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_width_popup'] : "300";
        echo "<input min='300' max='500' type='number' id='zalo_width_popup' name='custom_preferences_zalo[zalo_width_popup]' size='40' value='{$zalo_width_popup}' />";
    }

    function zalo_height_popup_section() {
        $zalo_height_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_height_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_height_popup'] : "300";
        echo "<input min='300' max='500' type='number' id='zalo_height_popup' name='custom_preferences_zalo[zalo_height_popup]' size='40' value='{$zalo_height_popup}' />";
    }

    function zalo_postion_popup_section() {
        $zalo_postion_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_postion_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_postion_popup'] : "bottom-right";
    ?>
        <select id="zalo_postion_popup" name="custom_preferences_zalo[zalo_postion_popup]">
            <option value="bottom-right" <?php if ($zalo_postion_popup == "bottom-right") echo "selected"; ?>>Góc dưới bên phải</option>
            <option value="bottom-left" <?php if ($zalo_postion_popup == "bottom-left") echo "selected"; ?>>Góc dưới bên trái</option>
            <option value="top-right" <?php if ($zalo_postion_popup == "top-right") echo "selected"; ?>>Góc trên bên phải</option>
            <option value="top-left" <?php if ($zalo_postion_popup == "top-left") echo "selected"; ?>>Góc trên bên trái</option>
            <option value="mid-right" <?php if ($zalo_postion_popup == "mid-right") echo "selected"; ?>>Chính giữa bên phải</option>
            <option value="mid-left" <?php if ($zalo_postion_popup == "mid-left") echo "selected"; ?>>Chính giữa bên trái</option>
        </select>
    <?php }
    
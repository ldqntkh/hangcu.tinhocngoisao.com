<?php
    add_action( 'admin_init', 'custom_preferences_facebook_init' );
    define( 'facebookOptions', get_option( 'custom_preferences_facebook_options' ) );

    function custom_preferences_facebook_init() {
        register_setting( 'custom_preferences_facebook_options', 'custom_preferences_facebook_options' );
        add_settings_section( 'configuration_facebook', __( 'Configuration Settings for Facebook', 'online-shop' ), 'configuration_section_facebook', 'custom_preferences_facebook' );
        // enable facebook functions
        add_settings_field( 'facebook_enable', __( 'Enable Facebook', 'online-shop' ), 'facebook_enable_section', 'custom_preferences_facebook', 'configuration_facebook' );

        add_settings_field( 'facebook_layout', __( 'Layout', 'online-shop' ), 'facebook_layout_section', 'custom_preferences_facebook', 'configuration_facebook' );

        add_settings_field( 'facebook_button_size', __( 'Button size', 'online-shop' ), 'facebook_button_size_section', 'custom_preferences_facebook', 'configuration_facebook' );

        add_settings_field( 'facebook_body_script', __( 'Body script', 'online-shop' ), 'facebook_body_script_section', 'custom_preferences_facebook', 'configuration_facebook' );
    }

    function configuration_section_facebook() {
        echo '<p>' . __( 'These configuration for function share Facebook.', 'online-shop' ) . '</p>';
    }

    function facebook_enable_section() {
        $facebookEnable = isset(get_option( 'custom_preferences_facebook_options' )['facebook_enable']) ? get_option( 'custom_preferences_facebook_options' )['facebook_enable'] : false;
        $checked = '';
        if ($facebookEnable) $checked="checked";
        echo "<input type='checkbox' id='facebook_enable' name='custom_preferences_facebook_options[facebook_enable]' value='true' {$checked}/>";
    }

    function facebook_layout_section() {
        $layout = isset( facebookOptions['facebook_layout'] ) ? facebookOptions['facebook_layout'] : 'button_count';
        ?>
            <select id="facebook_layout" name="custom_preferences_facebook_options[facebook_layout]">
                <option value="box_count" <?php if ($layout == "box_count") echo "selected"; ?>>Box count</option>
                <option value="button_count" <?php if ($layout == "button_count") echo "selected"; ?>>Button count</option>
                <option value="button" <?php if ($layout == "button") echo "selected"; ?>>Button</option>
            </select>
        <?php
    }

    function facebook_button_size_section() {
        $buttonSize = isset( facebookOptions['facebook_button_size'] ) ? facebookOptions['facebook_button_size'] : 'small';
    ?>
        <select id="facebook_button_size" name="custom_preferences_facebook_options[facebook_button_size]">
            <option value="small" <?php if ($buttonSize == "small") echo "selected"; ?>>Small</option>
            <option value="large" <?php if ($buttonSize == "large") echo "selected"; ?>>Large</option>
        </select>
    <?php
    }

    function facebook_body_script_section() {
        $bodyScript = isset( facebookOptions['facebook_body_script'] ) ? trim( facebookOptions['facebook_body_script'] ) : '';
        echo '<textarea name="custom_preferences_facebook_options[facebook_body_script]" rows="4" cols="50">' . $bodyScript . '</textarea>';
    }
?>
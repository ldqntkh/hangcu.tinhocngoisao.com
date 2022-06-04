<?php

    add_action( 'admin_init', 'custom_preferences_order_init' );
    function custom_preferences_order_init() {
        // config global
        register_setting( CUSTOM_PREFERECE_ORDER, CUSTOM_PREFERECE_ORDER );
        add_settings_section( CUSTOM_PREFERECE_ORDER, 'Configuration Settings', 'configuration_section_order_title', CUSTOM_PREFERECE_ORDER );
        add_settings_field( 'config_label_cancel_order', 'Config Order', 'config_label_cancel_order', CUSTOM_PREFERECE_ORDER, CUSTOM_PREFERECE_ORDER );
        item_order_promotion();
    }

    function configuration_section_order_title() {
        echo '<p>These configuration is used in account order.</p>';
    }
    
    function config_label_cancel_order() {
        $configCaps = get_option( CUSTOM_PREFERECE_ORDER )['config_label_cancel_order'];
        echo "<div><textarea name='" .CUSTOM_PREFERECE_ORDER. "[config_label_cancel_order]' cols='60' rows='10'>{$configCaps}</textarea>
                <br/><i>Mỗi giá trị được thêm trên 1 dòng</i>    
            </div>";
    }

    function item_order_promotion() {
        for ($i = 1; $i <= 1; $i++) {
            add_settings_section( $i. '_section', 'Item Promotion ', '', CUSTOM_PREFERECE_ORDER );
            add_settings_field( $i. '_section_image', 'Image', 'init_order_fields', CUSTOM_PREFERECE_ORDER, $i. '_section', array( 'section' => 'section_' . $i, 'type' => 'image' ) );
            add_settings_field( $i. '_section_url', 'URL', 'init_order_fields', CUSTOM_PREFERECE_ORDER, $i. '_section', array( 'section' => 'section_' . $i, 'type' => 'url' ) );
        }
    }

    function init_order_fields($args) {
        $section = $args['section'];
        $type = $args['type'];
        $fieldValue = get_option( CUSTOM_PREFERECE_ORDER )[$section][$type];
        if ( $type === 'image' ) {
            echo '<input type="text" name="'.CUSTOM_PREFERECE_ORDER.'[' . $section . '][' . $type . ']" class="regular-text image_url" value="' . $fieldValue . '">';
            echo '<input type="button" name="upload-btn" class="button-secondary upload-btn" value="Upload Image">';
        } elseif ($type === 'background_color') {
            echo '<input type="text" name="'.CUSTOM_PREFERECE_ORDER.'[' . $section . '][' . $type . ']" value="' . $fieldValue . '" class="cpa-color-picker" >';
        } else {
            echo "<input type='text' name='".CUSTOM_PREFERECE_ORDER."[" . $section . "][" . $type . "]' value='".  $fieldValue . "' class='regular-text' />";
        }
    }

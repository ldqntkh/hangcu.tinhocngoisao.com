<?php
    add_action( 'admin_init', 'custom_header_promotion_init' );
    function custom_header_promotion_init() {
        // config global
        register_setting( CUSTOM_PREFERECE_HEADER_PROMOTION, CUSTOM_PREFERECE_HEADER_PROMOTION );
        add_settings_section( CUSTOM_PREFERECE_HEADER_PROMOTION, 'Settings header promotion', 'configuration_header_promotion_title', CUSTOM_PREFERECE_HEADER_PROMOTION );
        add_settings_field( 'enable_header_promotion', 'On/Off Header Promotion', 'enable_header_promotion', CUSTOM_PREFERECE_HEADER_PROMOTION, CUSTOM_PREFERECE_HEADER_PROMOTION );
        add_settings_field( 'enable_carousel_header_promotion', 'On/Off Carousel Header Promotion', 'enable_carousel_header_promotion', CUSTOM_PREFERECE_HEADER_PROMOTION, CUSTOM_PREFERECE_HEADER_PROMOTION );
        items_header_promotion();
    }

    function configuration_header_promotion_title() {
        echo '<p>These configuration is used in header promotion of storefront.</p>';
    }
    
    function enable_header_promotion() {
        $enable_header_promotion = isset(get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_header_promotion']) ? get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_header_promotion'] : '';
        $checked = '';
        if ($enable_header_promotion == 'on') {
            $checked = 'checked';
        }
        echo "<input type='checkbox' name='".CUSTOM_PREFERECE_HEADER_PROMOTION."[enable_header_promotion]' " .$checked. "/>";
    }

    function enable_carousel_header_promotion() {
        $enable_carousel_header_promotion = isset(get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_carousel_header_promotion']) ? get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )['enable_carousel_header_promotion'] : '';
        $checked = '';
        if ($enable_carousel_header_promotion == 'on') {
            $checked = 'checked';
        }
        echo "<input type='checkbox' name='".CUSTOM_PREFERECE_HEADER_PROMOTION."[enable_carousel_header_promotion]' " .$checked. "/>";
        echo "<i>If carousel is OFF then header promotion will be display random index";
    }

    function init_fields($args) {
        $section = $args['section'];
        $type = $args['type'];
        $fieldValue = get_option( CUSTOM_PREFERECE_HEADER_PROMOTION )[$section][$type];
        if ( $type === 'image' ) {
            echo '<input type="text" name="'.CUSTOM_PREFERECE_HEADER_PROMOTION.'[' . $section . '][' . $type . ']" class="regular-text image_url" value="' . $fieldValue . '">';
            echo '<input type="button" name="upload-btn" class="button-secondary upload-btn" value="Upload Image">';
        } elseif ($type === 'background_color') {
            echo '<input style="width: 100px" type="text" name="'.CUSTOM_PREFERECE_HEADER_PROMOTION.'[' . $section . '][' . $type . ']" value="' . $fieldValue . '" class="cpa-color-picker" >';
        } else {
            echo "<input type='text' name='".CUSTOM_PREFERECE_HEADER_PROMOTION."[" . $section . "][" . $type . "]' value='".  $fieldValue . "' class='regular-text' />";
        }
    }

    function items_header_promotion() {
        for ($i = 1; $i <= 10; $i++) {
            add_settings_section( $i. '_section', 'Item header ' . $i, '', CUSTOM_PREFERECE_HEADER_PROMOTION );
            add_settings_field( $i. '_section_background', 'Background Color', 'init_fields', CUSTOM_PREFERECE_HEADER_PROMOTION, $i. '_section', array( 'section' => 'section_' . $i, 'type' => 'background_color' ) );
            add_settings_field( $i. '_section_image', 'Image', 'init_fields', CUSTOM_PREFERECE_HEADER_PROMOTION, $i. '_section', array( 'section' => 'section_' . $i, 'type' => 'image' ) );
            add_settings_field( $i. '_section_url', 'URL', 'init_fields', CUSTOM_PREFERECE_HEADER_PROMOTION, $i. '_section', array( 'section' => 'section_' . $i, 'type' => 'url' ) );
        }
    }


    add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
    add_action( 'admin_enqueue_scripts', 'register_customer_header_script' );
    
    function load_wp_media_files() {
        // WordPress library
        wp_enqueue_media();
    }

    function register_customer_header_script() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
        wp_register_script( 'custom_header_script', plugins_url('custom-preference/assets/js/admin-custom-header.js'), '', '', true );
        wp_enqueue_script( 'custom_header_script' );
    }
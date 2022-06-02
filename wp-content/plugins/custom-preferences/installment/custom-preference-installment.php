<?php
    add_action( 'admin_init', 'custom_preferences_installment_init' );
    define( 'installmentOptions', get_option( 'custom_preferences_installment_options' ) );

    function custom_preferences_installment_init() {
        register_setting( 'custom_preferences_installment_options', 'custom_preferences_installment_options' );
        add_settings_section( 'configuration_installment', __( 'Cấu hình thông báo trả góp', 'online-shop' ), 'configuration_section_installment', 'custom_preferences_installment' );
        // enable facebook functions
        add_settings_field( 'installment_hotline', __( 'Liên hệ', 'online-shop' ), 'installment_hotline_section', 'custom_preferences_installment', 'configuration_installment' );

        add_settings_field( 'installment_message', __( 'Thông báo', 'online-shop' ), 'installment_message_section', 'custom_preferences_installment', 'configuration_installment' );

    }

    function configuration_section_installment() {
        echo '<p>' . __( 'Cấu hình thông báo cho trang trả góp.', 'online-shop' ) . '</p>';
    }

    function installment_hotline_section() {
        $installment_hotline = isset( installmentOptions['installment_hotline'] ) ? trim( installmentOptions['installment_hotline'] ) : '';
        echo '<textarea name="custom_preferences_installment_options[installment_hotline]" rows="4" cols="50">' . $installment_hotline . '</textarea>';
    }

    function installment_message_section() {
        $installment_message = isset( installmentOptions['installment_message'] ) ? trim( installmentOptions['installment_message'] ) : '';
        echo '<textarea name="custom_preferences_installment_options[installment_message]" rows="4" cols="50">' . $installment_message . '</textarea>';
    }
?>
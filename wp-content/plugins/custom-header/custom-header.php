<?php
    /**
     * Plugin Name:       Custom Header
     * Description:       Custom Header Options
     * Version:           1.0.0
     * Author:            Phat Le
     */
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    add_action( 'admin_menu', 'custom_header_menu' );

    function custom_header_menu() {
        add_options_page( 'Custom Header', 'Custom Header', 'manage_options', 'custom-header', 'custom_header_options' );
    }

    function custom_header_options() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        echo '<div class="wrap">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_header_options' );
        do_settings_sections( 'custom_header' );
        echo '<input name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';
    }

    add_action( 'admin_init', 'custom_header_init' );

    function custom_header_init() {
        register_setting( 'custom_header_options', 'custom_header_options' );
        add_settings_section( 'configuration_main', 'First Section', 'first_section_title', 'custom_header' );
        add_settings_field( 'first_section_background', 'Background Color', 'init_fields', 'custom_header', 'configuration_main', array( 'section' => 'section_1', 'type' => 'background_color' ) );
        add_settings_field( 'first_section_image', 'Image', 'init_fields', 'custom_header', 'configuration_main', array( 'section' => 'section_1', 'type' => 'image' ) );
        add_settings_field( 'first_section_url', 'URL', 'init_fields', 'custom_header', 'configuration_main', array( 'section' => 'section_1', 'type' => 'url' ) );
        add_settings_field( 'first_section_text', 'Promotion Text', 'init_fields', 'custom_header', 'configuration_main', array( 'section' => 'section_1', 'type' => 'text' ) );

        add_settings_section( 'second_section', 'Second Section', '', 'custom_header' );

        add_settings_field( 'second_section_background', 'Background Color', 'init_fields', 'custom_header', 'second_section', array( 'section' => 'section_2', 'type' => 'background_color' ) );
        add_settings_field( 'second_section_image', 'Image', 'init_fields', 'custom_header', 'second_section', array( 'section' => 'section_2', 'type' => 'image' ) );
        add_settings_field( 'second_section_url', 'URL', 'init_fields', 'custom_header', 'second_section', array( 'section' => 'section_2', 'type' => 'url' ) );
        add_settings_field( 'second_section_text', 'Promotion Text', 'init_fields', 'custom_header', 'second_section', array( 'section' => 'section_2', 'type' => 'text' ) );

        add_settings_section( 'third_section', 'Third Section', '', 'custom_header' );

        add_settings_field( 'third_section_background', 'Background Color', 'init_fields', 'custom_header', 'third_section', array( 'section' => 'section_3', 'type' => 'background_color' ) );
        add_settings_field( 'third_section_image', 'Image', 'init_fields', 'custom_header', 'third_section', array( 'section' => 'section_3', 'type' => 'image' ) );
        add_settings_field( 'third_section_url', 'URL', 'init_fields', 'custom_header', 'third_section', array( 'section' => 'section_3', 'type' => 'url' ) );
        add_settings_field( 'third_section_text', 'Promotion Text', 'init_fields', 'custom_header', 'third_section', array( 'section' => 'section_3', 'type' => 'text' ) );

        add_settings_section( 'fourth_section', 'Fourth Section', '', 'custom_header' );

        add_settings_field( 'fourth_section_background', 'Background Color', 'init_fields', 'custom_header', 'fourth_section', array( 'section' => 'section_4', 'type' => 'background_color' ) );
        add_settings_field( 'fourth_section_image', 'Image', 'init_fields', 'custom_header', 'fourth_section', array( 'section' => 'section_4', 'type' => 'image' ) );
        add_settings_field( 'fourth_section_url', 'URL', 'init_fields', 'custom_header', 'fourth_section', array( 'section' => 'section_4', 'type' => 'url' ) );
        add_settings_field( 'fourth_section_text', 'Promotion Text', 'init_fields', 'custom_header', 'fourth_section', array( 'section' => 'section_4', 'type' => 'text' ) );
    }

    function init_fields($args) {
        $section = $args['section'];
        $type = $args['type'];
        $fieldValue = get_option( 'custom_header_options' )[$section][$type];
        if ( $type === 'image' ) {
            echo '<input type="text" name="custom_header_options[' . $section . '][' . $type . ']" class="regular-text image_url" value="' . $fieldValue . '">';
            echo '<input type="button" name="upload-btn" class="button-secondary upload-btn" value="Upload Image">';
        } else {
            echo "<input type='text' name='custom_header_options[" . $section . "][" . $type . "]' value='".  $fieldValue . "' class='regular-text' />";
        }
    }

    function first_section_title() {
        echo '<h2>First Section</h2>';
    }

add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
add_action( 'admin_enqueue_scripts', 'register_customer_header_script' );

function load_wp_media_files() {
    // WordPress library
    wp_enqueue_media();
}

function register_customer_header_script() {
    wp_register_script( 'custom_header_script', plugins_url('custom-header/assets/js/admin-custom-header.js'), '', '', true );
    wp_enqueue_script( 'custom_header_script' );
}

add_action( 'wp_body_open', function() {
    $headerPromotions = get_option( 'custom_header_options' );
    $_headerPromotions = [];
    foreach( $headerPromotions as $h ) {
        if( $h['image'] != '' ) {
            array_push( $_headerPromotions, $h );
        }
    }
    if ( !empty( $_headerPromotions ) && count($_headerPromotions) > 0 ) : ?>
        <div class="top-header-promotion featured-slider hide-mobile" data-autoplay="1" data-autoplayspeed="5000">
            <?php
                foreach ( $_headerPromotions as  $headerPromotion ) :
                    $backgroundColor = $headerPromotion['background_color'];
            ?>
                <a href="<?php echo $headerPromotion['url']; ?>">
                    <div class="promotion-banner" style="background-image:url('<?php echo $headerPromotion['image'] ?>'),linear-gradient(to right, <?php echo $backgroundColor; ?> 40%, <?php echo $backgroundColor; ?> 50%, <?php echo $backgroundColor; ?> 60%)"></div>
                </a>
                <?php endforeach; ?>
        </div>
    <?php endif;
}, 10 );

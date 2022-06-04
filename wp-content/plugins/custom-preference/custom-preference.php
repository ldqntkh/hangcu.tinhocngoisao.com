<?php
    /**
     * Plugin Name:       Custom Preferences
     * Description:       Custom Preferences contais some configure information
     * Version:           1.0.0
     * Author:            Anthony Lê
     */
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }
    define( 'CUSTOM_PREFERECE_VALUE_ID', 
        array(
            "custom_preferences_global" => "Global",
            "custom_preferences_product_management" => "Product Management",
            "custom_preferences_order" => "Order",
            "custom_preferences_caresoft" => "Caresoft",
            "custom_preferences_zalo" => "Zalo",
            "custom_preferences_facebook" => "Facebook",
            "custom_preferences_header_promotion" => "Header Promotion",
            "create_icon" => "Tạo icon"
        )
    );
    define( 'CUSTOM_PREFERECE_DIR', plugin_dir_path( __FILE__ ) );
    // define key
    define( 'CUSTOM_PREFERECE_GLOBAL', 'custom_preferences_global' );
    define( 'CUSTOM_PREFERECE_PRM', 'custom_preferences_product_management' );
    define( 'CUSTOM_PREFERECE_ORDER', 'custom_preferences_order' );
    define( 'CUSTOM_PREFERECE_CARESOFT', 'custom_preferences_caresoft' );
    define( 'CUSTOM_PREFERECE_ZALO', 'custom_preferences_zalo' );
    define( 'CUSTOM_PREFERECE_FACEBOOK', 'custom_preferences_facebook' );
    define( 'CUSTOM_PREFERECE_HEADER_PROMOTION', 'custom_preferences_header_promotion' );
    define( 'CREATE_ICON', 'create_icon' );

    include CUSTOM_PREFERECE_DIR . '/global/custom-preference-global.php';
    include CUSTOM_PREFERECE_DIR . '/product-management-api/custom-preference-product-management-api.php';
    include CUSTOM_PREFERECE_DIR . '/order/custom-preference-order.php';
    include CUSTOM_PREFERECE_DIR . '/zalo/custom-preference-zalo.php';
    include CUSTOM_PREFERECE_DIR . '/facebook/custom-preference-facebook.php';
    include CUSTOM_PREFERECE_DIR . '/caresoft/custom-preference-caresoft.php';
    include CUSTOM_PREFERECE_DIR . '/header-promotion/custom-header-promotion.php';
    include CUSTOM_PREFERECE_DIR . '/icons/create-icon.php';

    include CUSTOM_PREFERECE_DIR . '/hooks/init-template-hooks.php';
    include CUSTOM_PREFERECE_DIR . '/hooks/init-function-hooks.php';

    add_action( 'admin_menu', 'custom_preferences_menu', 10 );
    function custom_preferences_menu() {
        add_submenu_page('hangcu_functions', 'Custom preferences', 'Custom preferences', 'manage_options', 'custom_preferences', 'custom_preferences_options');
        // add_submenu_page( 'custom_preferences', 'Global preferences', "Global preferences", 'manage_options', 'custom_preferences', 'custom_preferences_global' );
        // add_submenu_page( 'custom_preferences', 'Header promotion', "Header promotion preferences", 'manage_options', 'custom_header_promotion_preferences', 'custom_header_promotion_preferences' );
    }

    function custom_preferences_options() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        render_select_box_tab();
        // render global preferece
        echo '<div id="'.CUSTOM_PREFERECE_GLOBAL.'" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_GLOBAL );
        do_settings_sections( CUSTOM_PREFERECE_GLOBAL );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render product management preference
        echo '<div id="' . CUSTOM_PREFERECE_PRM . '" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_PRM );
        do_settings_sections( CUSTOM_PREFERECE_PRM );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render order preference
        echo '<div id="' . CUSTOM_PREFERECE_ORDER . '" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_ORDER );
        do_settings_sections( CUSTOM_PREFERECE_ORDER );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render caresoft preference
        echo '<div id="' . CUSTOM_PREFERECE_CARESOFT . '" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_CARESOFT );
        do_settings_sections( CUSTOM_PREFERECE_CARESOFT );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render zalo preference
        echo '<div id="' . CUSTOM_PREFERECE_ZALO . '" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_ZALO );
        do_settings_sections( CUSTOM_PREFERECE_ZALO );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render facebook preference
        echo '<div id="' . CUSTOM_PREFERECE_FACEBOOK . '" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_FACEBOOK );
        do_settings_sections( CUSTOM_PREFERECE_FACEBOOK );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render header promotion preferece
        echo '<div id="custom_preferences_header_promotion" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( CUSTOM_PREFERECE_HEADER_PROMOTION );
        do_settings_sections( CUSTOM_PREFERECE_HEADER_PROMOTION );
        echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        echo '</form></div>';

        // render page create icon
        echo '<div id="create_icon" class="custom_preferences_tab wrap" style="display:none">';
        //echo '<form action="options.php" method="post">';
        settings_fields( CREATE_ICON );
        do_settings_sections( CREATE_ICON );
        //echo '<button class="button" name="Submit" type="submit">'.__( 'Save Changes' ).'</button>';
        //echo '</form></div>';
        echo '</div>';
    }
    
    function render_select_box_tab() {
    ?>
        <p>
            <label for="custom_preference_id">Chọn nhóm cấu hình:</label>
            <select id="custom_preference_id">
                <?php 
                    foreach( CUSTOM_PREFERECE_VALUE_ID as $key=>$value)
                    {
                        echo '<option value="' . $key . '">' .$value. '</option>';
                    }
                ?>
            </select>
        </p>
        <!-- wait on -->
        <script>
            const CUSTOM_PREFERENCE_TAB = 'CUSTOM_PREFERENCE_TAB';
            jQuery(document).on('change', '#custom_preference_id', function(e) {
                jQuery('.custom_preferences_tab').attr('style',  'display:none');
                jQuery('#' + e.target.value).removeAttr('style');
                sessionStorage.setItem(CUSTOM_PREFERENCE_TAB, e.target.value);
            });
            
            jQuery( document ).ready(function() {
                var item = sessionStorage.getItem(CUSTOM_PREFERENCE_TAB);
                if (item && item !== "") {
                    jQuery('#custom_preference_id').val(item);
                }
                jQuery('#custom_preference_id').trigger('change');
            });
        </script>
<?php
    }

    add_action( 'admin_enqueue_scripts', 'register_custom_preference_style' );
    
    function register_custom_preference_style() {
        wp_enqueue_style('custom_preference_css', plugins_url('assets/style/style.css',__FILE__), true);
    }

    // add_action( 'wp_footer', 'printChatOption' );

    add_action( 'rest_api_init', function () {
        register_rest_route( 'rest_api/v1', '/getchatoption', array(
          array(
            'methods'             => 'GET',
            'callback'            => 'getchatoption',
            'args'                => array(),
            'permission_callback' => '__return_true'
          ))
        );
    });

    function getchatoption() {
        $key = 'getchatoption';
        $transChatData = get_transient ( $key );
        if ( empty($transChatData) ) {
            ob_start();
            printChatOption();
            $content = ob_get_contents();
            ob_clean();
            ob_end_flush();
            set_transient( $key, $content, 60*60*2 ); // 2 hour
            wp_send_json_success($content);
        } else {
            wp_send_json_success($transChatData);
        }
    }

    add_action( 'rest_api_init', function () {
        register_rest_route( 'rest_api/v1', '/alepay_order_change', array(
          array(
            'methods'             => 'POST',
            'callback'            => 'alepay_order_change',
            'args'                => array(),
            'permission_callback' => '__return_true'
          ))
        );
    });

    function alepay_order_change(WP_REST_Request $request) {
        $key = 'alepay_order_change';
        delete_transient( $key );
        set_transient( $key, json_encode($request->get_body()), 60*60*2 ); // 2 hour
        
        return true;
    }

    function printChatOption() {
        // print zalo chat
        $zaloChatEnable = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_chat']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_enable_chat'] : false;
        $zaloOAID = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAId']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAId'] : "";
        if ( $zaloChatEnable && !empty( $zaloOAID ) ) { 
            $zalo_OAMsg = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAMsg']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_OAMsg'] : "Xin chào, rất vui được hỗ trợ quý khách.";    
            $zalo_second_display = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_second_display']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_second_display'] : 0;
            $zalo_width_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_width_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_width_popup'] : 300;
            $zalo_height_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_height_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_height_popup'] : 300;
            $zalo_postion_popup = isset(get_option( CUSTOM_PREFERECE_ZALO )['zalo_postion_popup']) ? get_option( CUSTOM_PREFERECE_ZALO )['zalo_postion_popup'] : "bottom-right";

            if ( $zalo_second_display < 0 ) $zalo_second_display = 0;

            if ( $zalo_width_popup < 300 ) $zalo_width_popup = 300;

            if ( $zalo_width_popup > 500 ) $zalo_width_popup = 500;

            if ( $zalo_height_popup < 300 ) $zalo_height_popup = 300;

            if ( $zalo_height_popup > 500 ) $zalo_height_popup = 500;

            $style = '';
            switch( $zalo_postion_popup ) {
                case 'bottom-right': 
                    $style = 'bottom: 0!important; right: 0!important';
                break;
                case 'bottom-left': 
                    $style = 'bottom: 0!important; left: 0!important';
                break;
                case 'top-right': 
                    $style = 'top: 0!important; right: 0!important';
                break;
                case 'top-left': 
                    $style = 'top: 0!important; left: 0!important';
                break;
                case 'mid-right': 
                    $style = 'top: 50%!important; right: 0!important';
                break;
                case 'mid-left': 
                    $style = 'top: 50%!important; left: 0!important';
                break;
            }
        ?>

            <div class="zalo-chat-widget" data-oaid="<?php echo $zaloOAID ?>"
                data-welcome-message="<?php echo $zalo_OAMsg ?>" 
                data-autopopup="<?php echo $zalo_second_display ?>" 
                data-width="<?php echo $zalo_width_popup ?>" 
                data-height="<?php echo $zalo_height_popup ?>"></div>

            <script src="https://sp.zalo.me/plugins/sdk.js"></script>
            <style>
                .zalo-chat-widget {
                    <?php echo $style ?>
                }
            </style>

        <?php }

        // facebook chat
        $facebookEnable = isset(get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_enable_chat']) ? get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_enable_chat'] : false;
        $facebook_script_chat = isset(get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_script_chat']) ? get_option( CUSTOM_PREFERECE_FACEBOOK )['facebook_script_chat'] : "";

        if ( $facebookEnable && !empty( $facebook_script_chat ) ) {
            echo $facebook_script_chat;
        }

        // caresoft chat
        $caresoftEnable = isset(get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_enable_chat']) ? get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_enable_chat'] : false;
        $caresoft_script_chat = isset(get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_script_chat']) ? get_option( CUSTOM_PREFERECE_CARESOFT )['caresoft_script_chat'] : "";

        if ( $caresoftEnable && !empty( $caresoft_script_chat ) ) {
            echo $caresoft_script_chat;
        }

    }

    add_action( 'wp_footer', 'printFooterScript', 10 );
    if( !function_exists('printFooterScript') ) {
        function printFooterScript() {
            echo get_option( CUSTOM_PREFERECE_GLOBAL )['render_footer_script'];
            printChatOption();
        }
    }

    $enableRedirectOld = get_option( CUSTOM_PREFERECE_GLOBAL )['config_show_redirect_to_old'];
    if($enableRedirectOld) {
        // testing new layout
        add_action( 'electro_before_header_mb', 'hangcu_new_template', 1 );
        function hangcu_new_template() { 
            echo get_option( CUSTOM_PREFERECE_GLOBAL )['config_show_redirect_to_old'];
        }
    }
?>
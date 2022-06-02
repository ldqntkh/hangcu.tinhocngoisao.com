<?php
/**
 * Plugin Name:       WooCommerce build PC manager
 * Description:       WooCommerce build PC manager
 * Version:           1.0.0
 * Author:            Anthony Le
 * Text Domain:       woocommerce-buildpc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'BUILD_PC_DIR', plugin_dir_path( __FILE__ ) );
define( 'BUILD_PC_URL', plugin_dir_url( __FILE__ ) );

if ( class_exists( 'CDN_Enabler' ) ) {
    function check_valid_cdn_buildpc() {
        $options = CDN_Enabler::get_options();
        if ( !empty( $options['url'] ) ) {
            return $options['url'];
        }
        return false;
    }
}

class Build_PC_Manager {
    function __construct() {
        $this->register_header_admin_css();
        $this->register_footer_admin_script();
    }
    public function init_tab_label() {
        global $product_object;
        include BUILD_PC_DIR . '/views/build-pc-tab-label.php';
    }

    public function init_tab_data() {
        global $product_object;
        // this data will uses to display in storefront
        $list_product_type = get_option( 'custom_preferences_options' )['list_product_type'];
        $product_types = json_decode( $list_product_type, true );

        include BUILD_PC_DIR . '/views/build-pc-tab-data.php';
    }

    // register footer script
    function register_footer_admin_script() {
        function buildpc_scripts() {
            wp_register_script( 'build-pc-bm', BUILD_PC_URL. '/assets/js/build-pc-bm.js', '', '', true );
            wp_enqueue_script( 'build-pc-bm' );
        }
        add_action( 'admin_enqueue_scripts', 'buildpc_scripts' );
    }

    // register css
    function register_header_admin_css() {
        function buildpc_style() {
            wp_register_style( 'build-pc-bm-css', BUILD_PC_URL.'/assets/css/style_buildpc.css', false, '1.0.0' );
            wp_enqueue_style( 'build-pc-bm-css' );
        }
        add_action( 'admin_enqueue_scripts', 'buildpc_style' );
    }

    public function save_buildpc_data($post_id) {
        try {
            $product = wc_get_product( $post_id );
            //die;
            if (isset( $_POST['buildpc-type'] )) {
                $product->update_meta_data( '_buildpc-type', $_POST['buildpc-type'] );
            }
            if (isset( $_POST['selected_product_value'] )) {
                //var_dump($_POST['selected_product_value']);
               $product->update_meta_data( '_selected_product_value', $_POST['selected_product_value'] );
            }
            if ( isset( $_POST['buildpc-ids'] ) ) { // not use
                $buildpcs = array();
                $ids     = $_POST['buildpc-ids'];

                if ( ! empty( $ids ) ) {
                    foreach ( $ids as $id ) {
                        if ( $id && $id > 0 ) {
                            $buildpcs[] = $id;
                        }
                    }

                    $product->update_meta_data( '_linked_buildpc_ids', empty($buildpcs) ? [] : $buildpcs );
                } 
            }
            $product->save();
        } catch(Exception $e) {
            var_dump($e);
            die;
        }
        
    }
}
$buildpcMgr = new Build_PC_Manager(); 
// woocommerce_product_write_panel_tabs
add_action( 'woocommerce_product_write_panel_tabs' , array($buildpcMgr, 'init_tab_label') );
add_action( 'woocommerce_product_data_panels' , array($buildpcMgr, 'init_tab_data') );

// save custom fields
add_action( 'woocommerce_process_product_meta' , array($buildpcMgr, 'save_buildpc_data') );

// register api
include BUILD_PC_DIR . '/api/function.php';
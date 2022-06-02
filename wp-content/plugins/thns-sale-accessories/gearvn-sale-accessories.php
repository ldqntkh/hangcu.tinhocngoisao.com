<?php 
/**
 * Plugin Name:       THNS Sale Accessories
 * Description:       THNS Sale Accessories
 * Version:           1.0.0
 * Author:            Quang Lê
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THNS_SALE_ACCESSORIES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'THNS_SALE_ACCESSORIES_PLUGIN', 'thns_sale_accessories' );

add_action('admin_enqueue_scripts', 'load_sale_accessories_scripts');
function load_sale_accessories_scripts(){
    wp_enqueue_style('sale_accessories_css', plugins_url('assets/css/sale-accessories.css',__FILE__), true);
    wp_enqueue_script('sale_accessories_js', plugins_url('assets/js/sale-accessories.js',__FILE__), array('jquery'));
}

add_action( 'wp_enqueue_scripts', 'thns_sale_accessories_script' );
function thns_sale_accessories_script() {
    wp_enqueue_style('sale_accessories_css_storefront', plugins_url('assets/css/sale-accessories-storefront.css',__FILE__), true);
    wp_enqueue_script( 'sale_accessories_script', plugins_url('assets/js/sale-accessories-storefront.js',__FILE__), array('jquery'), '1.0.0', true );
}


require_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/models/class-campaign.php';
require_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/models/class-group-accessoties.php';
require_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/models/class-group-product.php';
require_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/models/class-history.php';

include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/admin-main-init.php';
include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/helpers/class-admin-calculate-accessories-price.php';
include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/helpers/class-calculate-accessories-price.php';
include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/storefront/storefront-main-init.php';
include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/storefront/display-sale-accessories-tab.php';

include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/helpers/class-check-sale-product-accessories.php';



function bbloomer_split_product_individual_cart_items( $cart_item_data, $product_id, $variation_id, $quantity ){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if ( empty( $_SESSION[ 'sale_accessories_cart_item_key' ] ) ) $sale_accessories_cart_item_key = [];
    else $sale_accessories_cart_item_key = (array)json_decode( $_SESSION[ 'sale_accessories_cart_item_key' ] , true);

    $check = CheckSaleProductAccessories::checkProductAccessoriesInCart( $product_id, $quantity );
    
    if ( !$check ) {
        $unique_cart_item_key = uniqid();
        $cart_item_data['unique_key'] = $unique_cart_item_key;
        $sale_accessories_cart_item_key[$product_id] = $cart_item_data['unique_key'];
    } else {
        if ( isset( $sale_accessories_cart_item_key[$product_id] ) ) {
            $cart_item_data['unique_key'] = $sale_accessories_cart_item_key[$product_id];
        } else {
            $unique_cart_item_key = uniqid();
            $cart_item_data['unique_key'] = $unique_cart_item_key;
            $sale_accessories_cart_item_key[$product_id] = $cart_item_data['unique_key'];
        }
    }


    unset( $_SESSION[ 'sale_accessories_cart_item_key' ] );
    $_SESSION['sale_accessories_cart_item_key'] = json_encode( $sale_accessories_cart_item_key );

    return $cart_item_data;
}

// add_filter( 'woocommerce_add_cart_item_data', 'bbloomer_split_product_individual_cart_items', 10, 4 );
// add_filter( 'woocommerce_is_sold_individually', '__return_true' );

add_filter( 'woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10, 3 );



function myplugin_woocommerce_locate_template( $template, $template_name, $template_path ) {
    global $woocommerce;

    $_template = $template;

    if ( ! $template_path ) $template_path = $woocommerce->template_url;

    $plugin_path  = THNS_SALE_ACCESSORIES_PLUGIN_DIR . 'woocommerce/';

    // Modification: Get the template from this plugin, if it exists
    if ( file_exists( $plugin_path . $template_name ) )
        return $plugin_path . $template_name;
    return $template;
}

// to test
add_action( 'admin_footer', 'gearvn_delete_auto_drafts', 1, 1 );
function gearvn_delete_auto_drafts( $array ) {
    global $pagenow;
    if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
        global $wpdb;
        
        $old_posts = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_status = 'auto-draft' AND DATE_SUB( NOW(), INTERVAL 7 DAY ) > post_date" );
        // remove all campaign of draft
        $campaign = new GEARVNSaleAccessoriesCampaign( );
        foreach ( (array) $old_posts as $delete ) {
            $campaign->removeDrafCampaignByProductId( $delete );
            wp_delete_post( $delete, true );
        }
    }
}



// create table
register_activation_hook( __FILE__, 'create_accessories_database_table' );
function create_accessories_database_table( ) {

    global $table_prefix, $wpdb, $wnm_db_version;

    $charset_collate = $wpdb->get_charset_collate();

    $tb_campaign    = $table_prefix . 'se_campaign';
    $tb_group       = $table_prefix . 'se_group';
    $tb_products    = $table_prefix . 'se_products';
    $tb_history     = $table_prefix . 'se_history';
    

    if($wpdb->get_var( "show tables like '$tb_campaign'" ) != $tb_campaign &&  
            $wpdb->get_var( "show tables like '$tb_group'" ) != $tb_group && 
            $wpdb->get_var( "show tables like '$tb_products'" ) != $tb_products &&
            $wpdb->get_var( "show tables like '$tb_history'" ) != $tb_history) 
    {
        $sql = array();

        
        $sql[] = "CREATE TABLE `$tb_campaign` (
                            `ID` int(11) NOT NULL AUTO_INCREMENT,
                            `name` varchar(100) NOT NULL,
                            `start_date` date NOT NULL,
                            `end_date` date NOT NULL,
                            `enable` int(1) DEFAULT 0,
                            `product_id` int(11) NOT NULL,
                            `user_create` varchar(100) NOT NULL,
                            `create_at` datetime DEFAULT current_timestamp(),
                            PRIMARY KEY (`ID`),
                            UNIQUE KEY `product_id_UNIQUE` (`ID`,`product_id`)
                        );";

        $sql[] = "CREATE TABLE `$tb_group` (
                    `ID` int(11) NOT NULL AUTO_INCREMENT,
                    `campaign_id` int(11) NOT NULL,
                    `name` varchar(100) NOT NULL,
                    `discount_type` varchar(45) NOT NULL,
                    `discount_value` int(11) NOT NULL,
                    `user_create` varchar(100) NOT NULL,
                    `enable` int(11) DEFAULT 1,
                    `create_at` datetime DEFAULT current_timestamp(),
                    `display_index` int(11) DEFAULT 0,
                    PRIMARY KEY (`ID`),
                    KEY `gvn_se_group_ibfk_1` (`campaign_id`),
                    CONSTRAINT `gvn_se_group_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `$tb_campaign` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
                );";

        $sql[] = "CREATE TABLE `$tb_products` (
                    `group_id` int(11) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    `user_create` varchar(100) NOT NULL,
                    `display_index` int(11) DEFAULT 0,
                    `create_at` datetime DEFAULT current_timestamp(),
                    PRIMARY KEY (`group_id`,`product_id`),
                    CONSTRAINT `gvn_se_products_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `$tb_group` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
                );";

        $sql[] = "CREATE TABLE `$tb_history` (
                    `ID` int(11) NOT NULL AUTO_INCREMENT,
                    `user_create` varchar(100) NOT NULL,
                    `table_name` varchar(100) NOT NULL,
                    `new_data` longtext NOT NULL,
                    `old_data` longtext DEFAULT NULL,
                    `action` varchar(45) NOT NULL,
                    `create_at` datetime DEFAULT current_timestamp(),
                    PRIMARY KEY (`ID`)
                );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql);
        add_option("wnm_db_version", $wnm_db_version);

    }

}

function artisansweb_scripts() {
    wp_enqueue_style( 'bootstrapcss', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), false, 'all' );
     
    wp_register_script('bootstrapjs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), false, true);
    wp_enqueue_script('bootstrapjs');
}
add_action( 'wp_enqueue_scripts', 'artisansweb_scripts' );
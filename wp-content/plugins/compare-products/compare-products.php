<?php 
/**
 * Plugin Name:       Compare products details
 * Description:       Compare products details
 * Version:           1.0.0
 * Author:            Anthony Lê
 */

define( 'COMPARE_PRODUCT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TEXT_COMPARE_PRODUCT', 'gearvn_compare_product' );
// define table name
define( 'TB_COMPARE_PRODUCT_TYPE', 'compare_product_type');
define( 'TB_COMPARE_GROUP_ATTRIBUTES', 'compare_group_attributes' );
define( 'TB_COMPARE_PRODUCT_ATTRIBUTES', 'compare_product_attributes' );
define( 'TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES', 'compare_group_product_attributes' );
define( 'TB_COMPARE_ATTRIBUTE_TYPE', 'compare_attribute_type' );
define( 'TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE', 'compare_mapping_product_attribute_value' );


include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/products/product_type.php';

include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/attributes/attributes.php';
include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/attributes/group_attributes.php';
include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/attributes/mapping-product-attribute.php';
include COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/attributes/assign-attribute.php';

if( is_admin() ) {
    include_once COMPARE_PRODUCT_PLUGIN_DIR. '/product_compare/api/caching_product_compare.php';
    $caching = new CachingCompareData;
    $caching->setupApi();
}

// API
$productTypes = new ProductTypes;
$productTypes->setupApi();

$attributes = new CP_Attribute;
$attributes->setupApi();

$groupAttribute = new GroupAttribute;
$groupAttribute->setupApi();

$mappingProductAttribute = new MappingProductAttribute;
$mappingProductAttribute->setupApi();

// HOOK creaet admin menu
add_action( 'admin_menu', 'compare_product_menu', 20 );
function compare_product_menu() {

    add_menu_page('Setting Compare product', 'Setting Compare product', 'manage_options', 'compare_products', 'setting_product_types');
    // add_submenu_page( 'gearvn_functions' ,'Setting Compare product', 'Setting Compare product', 'manage_options', 'compare_products', 'setting_product_types');

    add_submenu_page( 'compare_products', 'Product types', "Product types", 'manage_options', 'compare_products', 'setting_product_types' );
    // only set for admin
    //add_submenu_page( 'compare_products', 'Attribute types', "Attribute types", 'manage_options', 'attribute_types', 'setting_attribute_types' );

    add_submenu_page( 'compare_products', 'Attributes', "Attributes", 'manage_options', 'attributes', 'setting_attributes' );

    add_submenu_page( 'compare_products', 'Group product compare', "Group product compare", 'manage_options', 'group_products', 'setting_group_product' );

    add_submenu_page( '', 'Mapping group product attributes', "Mapping group product attributes", 'manage_options', 'group_product_attributes', 'setting_group_product_attributes' );

    add_submenu_page( null, 'Assign attributes', "Assign attributes", 'manage_options', 'assign_attributes', 'setting_assign_attributes' );
}

add_action('admin_enqueue_scripts', 'load_product_compare_scripts');
function load_product_compare_scripts(){
    wp_enqueue_style('product_compare_css', plugins_url('assets/css/product_compare.css',__FILE__), true);
    wp_enqueue_script('custom-js-product-compare', plugins_url('assets/js/product_compare.js',__FILE__), array('jquery'));
    wp_enqueue_script('ckeditor-product-compare', plugins_url('assets/js/ckeditor/ckeditor.js',__FILE__), array('jquery'));
}

// HOOK create table
register_activation_hook( __FILE__, 'create_plugin_compare_products_database_table' );
function create_plugin_compare_products_database_table()
{
    global $table_prefix, $wpdb, $wnm_db_version;

    $charset_collate = $wpdb->get_charset_collate();

    $wp_product_type = $table_prefix . TB_COMPARE_PRODUCT_TYPE;

    $wp_group_table = $table_prefix . TB_COMPARE_GROUP_ATTRIBUTES;

    $wp_product_attr_table = $table_prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;

    $wp_group_product_attr_table = $table_prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;

    $wp_mapping_product_attribute = $table_prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;

    // now, I will design a table to contain value type, but it's not showing
    $wp_attr_type_table = $table_prefix . TB_COMPARE_ATTRIBUTE_TYPE;

    $table_post = $table_prefix . 'posts';

    #Check to see if the table exists already, if not, then create it
//     if($wpdb->get_var( "show tables like '$wp_attr_type_table'" ) != $wp_attr_type_table &&  
//             $wpdb->get_var( "show tables like '$wp_group_table'" ) != $wp_group_table && 
//             $wpdb->get_var( "show tables like '$wp_product_attr_table'" ) != $wp_product_attr_table &&
//             $wpdb->get_var( "show tables like '$wp_group_product_attr_table'" ) != $wp_group_product_attr_table) 
//     {
        
        $sql = array();

        $sql[] = "CREATE TABLE `". $wp_product_type ."` ( "
                . "  `id`  int(128)   NOT NULL AUTO_INCREMENT, "
                . "  `product_type_name`  varchar(255)   NOT NULL, "
                . "  `is_default`  boolean   DEFAULT 0 , "
                . "  UNIQUE (product_type_name) ,"
                . "  PRIMARY KEY `product_type` (`id`) "
                . ") $charset_collate; ";

        $sql[] = "CREATE TABLE `". $wp_group_table ."` ( "
                . "  `group_id`  varchar(255)   NOT NULL, "
                . "  `group_name`  varchar(255)   NOT NULL, "
                . "  `group_desc`  varchar(255) , "
                . "  `product_type` int(128) NOT NULL, "
                . "  `display_index` int(128) NOT NULL DEFAULT 0, "
                . "  PRIMARY KEY (group_id, product_type), "
                . "  FOREIGN KEY (`product_type`) REFERENCES ".$wp_product_type."(id)"
                . ") $charset_collate; ";

        $sql[] = "CREATE TABLE `". $wp_attr_type_table ."` ( "
                . "  `id`  int(128)   NOT NULL AUTO_INCREMENT , "
                . "  `name`  varchar(255)   NOT NULL, "
                . "  `value`  int(10) UNIQUE , "
                . "  PRIMARY KEY (id, value)"
                . ") $charset_collate; ";

        $sql[] = "CREATE TABLE `". $wp_product_attr_table ."` ( "
                . "  `attribute_id`  varchar(255)   NOT NULL, "
                . "  `attribute_name`  varchar(255)   NOT NULL, "
                . "  `attribute_type`  int(10)   NOT NULL, "
                . "  `attribute_desc`  varchar(255) , "
                . "  PRIMARY KEY `attribute_id` (`attribute_id`), "
                . "  FOREIGN KEY (`attribute_type`) REFERENCES ".$wp_attr_type_table."(value)"
                . ") $charset_collate; ";

        $sql[] = "CREATE TABLE `". $wp_group_product_attr_table ."` ( "
                . "  `id`  int(128)   NOT NULL AUTO_INCREMENT UNIQUE, "
                . "  `attribute_id` varchar(255) NOT NULL, "
                . "  `group_id` varchar(255) NOT NULL, "
                . "  `display_index` int(128) NOT NULL DEFAULT 0, "
                . "  PRIMARY KEY (attribute_id, group_id), "
                . "  FOREIGN KEY (`attribute_id`) REFERENCES ".$wp_product_attr_table."(attribute_id) ON DELETE CASCADE,"
                . "  FOREIGN KEY (`group_id`) REFERENCES ".$wp_group_table."(group_id) ON DELETE CASCADE "
                . ") $charset_collate; ";
        
        $sql[] = "CREATE TABLE `" . $wp_mapping_product_attribute . "` ( "
                . " `product_id` bigint(20) unsigned NOT NULL, "
                . " `group_id` varchar(255) NOT NULL, "
                . " `attribute_id` varchar(255) NOT NULL, "
                . " `attribute_type_text` VARCHAR(45) NULL, "
                . " `attribute_value` LONGTEXT NULL, "
                . " PRIMARY KEY (`product_id`, `group_id`, `attribute_id`) , "
                . " FOREIGN KEY (`product_id`) REFERENCES $table_post(`ID`), "
                . " FOREIGN KEY (`group_id`) REFERENCES ".$wp_group_table."(`group_id`) ON DELETE CASCADE , "
                . " FOREIGN KEY (`attribute_id`) REFERENCES ".$wp_product_attr_table."(`attribute_id`) ON DELETE CASCADE"
                . ") $charset_collate; ";
                
        $sql[] = "INSERT INTO `$wp_product_type` (`id`, `product_type_name`, `is_default`) VALUES (1, 'default', 1)";
        // insert data to attribute type
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('String', 1)";
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('Text', 2)";
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('Html', 3)";
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('Image', 4)";
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('Slider', 5)";
        $sql[] = "INSERT INTO `$wp_attr_type_table` (`name`, `value`) VALUES ('Number', 6)";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql);
        add_option("wnm_db_version", $wnm_db_version);
//     }
}

// init hook
require_once COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/hook/add-metabox-product-compare.php';
require_once COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/hook/client-request-product-compare.php';
require_once COMPARE_PRODUCT_PLUGIN_DIR . '/hooks.php';

// init data compare caching
add_action( 'wp_head', function() {
    // $path = plugin_dir_url('/gearvn-compare-products/assets/js/compare-data/compare-datas.js');
    if( $path ) {
        // global $_wp_using_ext_object_cache;

        // $_wp_using_ext_object_cache_previous = $_wp_using_ext_object_cache;
        // $_wp_using_ext_object_cache = false;
    }
    // $upload_dir = wp_upload_dir();
    // $basedir = $upload_dir['basedir'];
    $caching_file = '/wp-content/uploads' . '/compare-datas.js';

    // $caching_file = $path . 'compare-datas.js' ;
    $transient_key_ver = 'product_compare_caching_data_ver';
    $version = get_transient( $transient_key_ver );
    // $_wp_using_ext_object_cache = $_wp_using_ext_object_cache_previous;
    if( !$version ) $version = date();
    wp_enqueue_script('compare_caching_data', $caching_file, array('jquery'), $version);
} );
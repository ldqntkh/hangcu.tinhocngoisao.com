<?php 
/**
 * Plugin Name:       Tính phí trả góp
 * Description:       Hỗ trợ tính phí trả góp và tư vấn khách hàng
 * Version:           1.0.0
 * Author:            Anthony Le
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'BANK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BANK_PLUGIN_NAME', 'star_brand' );

include_once BANK_PLUGIN_DIR . '/includes/models/class-bank.php';
include_once BANK_PLUGIN_DIR . '/includes/models/class-installment.php';
include_once BANK_PLUGIN_DIR . '/includes/function.php';

include_once BANK_PLUGIN_DIR . '/includes/main-page.php';

add_action('admin_enqueue_scripts', 'star_brands_scripts');
function star_brands_scripts(){
    wp_enqueue_style('star_brand_css', plugins_url('assets/css/star-brands.css',__FILE__), true);
    wp_enqueue_script('star_brand_script', plugins_url('assets/js/star-app.js',__FILE__), array('jquery'));
}



// create table
register_activation_hook( __FILE__, 'create_installment_database_table' );
function create_installment_database_table( ) {

    global $table_prefix, $wpdb, $wnm_db_version;

    $tb_bank                = $table_prefix . 'bank';
    $tb_sub_bank            = $table_prefix . 'sub_bank';
    $tb_monthly_installment = $table_prefix . 'monthly_installment';
    
    // if( $wpdb->get_var( "show tables like '$tb_installment'" ) != $tb_installment ||
    //     $wpdb->get_var( "show tables like '$tb_sub_bank'" ) != $tb_sub_bank ||
    //     $wpdb->get_var( "show tables like '$tb_monthly_installment'" ) != $tb_monthly_installment ) 
    // {
        try {
            $sql = array();

            $sql[] = "DROP TABLE $tb_monthly_installment;";
            $sql[] = "DROP TABLE $tb_sub_bank;";
            $sql[] = "DROP TABLE $tb_bank;";

            $sql[] = 'CREATE TABLE '. $tb_bank  .' (
                        `ID` INT NOT NULL AUTO_INCREMENT,
                        `bank_name` VARCHAR(100) NOT NULL,
                        `bank_type` VARCHAR(45) NOT NULL,
                        `bank_img` VARCHAR(45) NOT NULL,
                        `display_index` INT NULL DEFAULT 0,
                        PRIMARY KEY (`ID`));';

            $sql[] = 'CREATE TABLE '.$tb_sub_bank.' (
                        `sub_bank_name` VARCHAR(100) NOT NULL,
                        `bank_id` INT NOT NULL,
                        `display_index` INT NULL DEFAULT 0,
                        PRIMARY KEY (sub_bank_name, bank_id),
                        FOREIGN KEY (`bank_id`)
                        REFERENCES '.$tb_bank .' (`ID`)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE);';

            $sql[] = 'CREATE TABLE '.$tb_monthly_installment.' (
                        `month` INT NOT NULL,
                        `bank_id` INT NOT NULL,
                        `min_price` INT NOT NULL,
                        `prepaid_percentage` FLOAT NOT NULL,
                        `fee` FLOAT NOT NULL,
                        `fee_desc` VARCHAR(45) NULL,
                        `docs_require` TEXT NULL,
                        PRIMARY KEY (month, bank_id),
                        FOREIGN KEY (`bank_id`)
                        REFERENCES '.$tb_bank .' (`ID`)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE);';
    
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            
            dbDelta($sql);
            add_option("wnm_db_version", $wnm_db_version);
        } catch ( Exception $e ) {
            var_dump( $e->getMessage() );
        }
    // }
}
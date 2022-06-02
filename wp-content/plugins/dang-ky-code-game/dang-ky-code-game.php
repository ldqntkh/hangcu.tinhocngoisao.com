<?php
/**
 * Plugin Name: Đăng ký code game
 * Description: Đăng ký code game
 * Author: Quang Le
 * Version: 1.0
 */

define( 'DKCODEGAME_PLUGIN', __FILE__ );
define( 'DKCODEGAME_PLUGIN_DIR', untrailingslashit( dirname( DKCODEGAME_PLUGIN ) ) );
define( 'DKCODEGAME_PLUGIN_FULL_PATH', __FILE__ );


register_activation_hook( DKCODEGAME_PLUGIN_FULL_PATH, 'create_service_codegame_database_table' );
function create_service_codegame_database_table() {
    global $table_prefix, $wpdb, $wnm_db_version;
    $table_dang_ky_code_game = $table_prefix . 'dang_ky_code_game';
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql = "CREATE TABLE IF NOT EXISTS `$table_dang_ky_code_game` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `phone_number` VARCHAR(11) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `fullname` NVARCHAR(200) NOT NULL,
                `address` NVARCHAR(200) NOT NULL,
                `company_name` NVARCHAR(200) NOT NULL,
                -- `has_thebaohanh` INT(1) NOT NULL,
                -- `nganh_hang` NVARCHAR(200) NOT NULL,
                `description` TEXT NOT NULL,
                `file_data` TEXT NOT NULL,
                `status` INT(1) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))
            ENGINE = InnoDB";
    dbDelta($sql);
    
    add_option("wnm_db_version", $wnm_db_version);
}
// $administrator     = get_role('administrator');
// $administrator->add_cap( "quan_ly_bao_hanh" );

// init admin page
require_once (DKCODEGAME_PLUGIN_DIR . '/admin/init-menu.php');

// init hook
require_once (DKCODEGAME_PLUGIN_DIR . '/hooks/init.php');

// init hook
// require_once (DKBH_PLUGIN_DIR . '/hooks/api-hooks.php');
// require_once (DKBH_PLUGIN_DIR . '/hooks/display-form-cost.php');
// require_once (DKBH_PLUGIN_DIR . '/hooks/product-detail-add-to-cart.php');


// add_action( 'render_form_dang_ky_bao_hanh', 'form_dang_ky_bao_hanh', 10, 1 );


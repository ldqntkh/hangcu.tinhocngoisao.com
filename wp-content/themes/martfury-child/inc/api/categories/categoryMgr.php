<?php

include plugin_dir_path( __FILE__ ) . '/lib/categoryHelper.php';

if (!function_exists('getListCategorySpecial')) :
    function getListCategorySpecial(WP_REST_Request $request) {
        try {
            $special_menus_content = get_cache_by_key('special_menus_content');
            
            if (!$special_menus_content) {
                $special_menus = wp_get_nav_menu_items('special-menu');
                // $ID = isset($_GET[ 'cat_id' ]) ? esc_attr( $_GET[ 'cat_id' ] ) : "0";
                $special_menus_content = findListMenuAttributes($special_menus);
                set_cache_by_key('special_menus_content', $special_menus_content);
            }
            
            return array(
                "status" => "OK",
                "errMsg" => "",
                "data" => $special_menus_content
            );
        } catch( Exception $e ) {
            return array(
                "status" => "FAIL",
                "errMsg" => "Cannot find mennu",
                "data" => wp_get_nav_menu_items('special-menu')
            ); 
        }
        // if ( has_nav_menu( 'special-menu' ) ) {
            
        // }
        
    }
endif;


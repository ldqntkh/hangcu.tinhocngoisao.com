<?php
class ProductTypeApi {
    public function __construct() {

    }

    public static function getListProductType() {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $producttypes = $wpdb->get_results( "select * from $table_name where  product_type_name <> 'default' ORDER BY id" );
        return $producttypes;
    }

    public static function getGroupProductMappingByProductId($id) {
        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $wp_group_table = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $group = $wpdb->get_results( "SELECT * FROM $table_mapping_product_attribute_value INNER JOIN $wp_group_table ON $table_mapping_product_attribute_value.group_id = $wp_group_table.group_id
                                        WHERE $table_mapping_product_attribute_value.product_id = $id LIMIT 1" );

        if (count($group) == 1) {
            return $group[0]->product_type;
        } else return null;
    }

    public static function getListProductsMappingByProductIdAndProductType($id, $type, $search_name = null) {
        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $wp_group_table = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
       
        if ( $search_name != null) {
            
            $wp_posts = $wpdb->prefix .'posts';
            $group = $wpdb->get_results( "SELECT product_id from $table_mapping_product_attribute_value
                                        inner join $wp_posts on $wp_posts.ID = $table_mapping_product_attribute_value.product_id
                                        where product_id <> '$id' AND group_id IN (SELECT group_id from $wp_group_table WHERE product_type = '$type')
                                        AND post_title LIKE '%$search_name%'
                                        group by product_id" );
        } else {
            $group = $wpdb->get_results( "SELECT product_id from $table_mapping_product_attribute_value
                                        where product_id <> '$id' AND group_id IN (SELECT group_id from $wp_group_table WHERE product_type = '$type')
                                        group by product_id" );
        }
        
        if (count($group) > 0) {
            $arrResult = array();
            foreach ($group as $item) {
                array_push($arrResult, $item->product_id);
            }
            return $arrResult;
        } else return null;
    }
}
<?php
class ProductCompareRestApi {
    public function __construct() {

    }

    public function getListProductType() {
        global $wpdb;
        $table_name = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;
        $producttypes = $wpdb->get_results( "select * from $table_name where  product_type_name <> 'default' AND product_type_name LIKE '%${product_type_search}%' ORDER BY id" );
        return $producttypes;
    }
}
<?php 

class MappingProductAttributeRestApi {
    public function __construct(){}

    public function insertMappingDataForm($product_id, $attributes) {
        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        // remove all data
        $rs = $wpdb->delete( $table_mapping_product_attribute_value , array( 'product_id' => $product_id) );

        $values = array();
        $place_holders = array();
        $query = "INSERT INTO $table_mapping_product_attribute_value (product_id, group_id, attribute_id, attribute_value) VALUES ";

        foreach($attributes as $key => $value) {
            $keys = explode('/', $key);
            $query .= '( '. $product_id .', "' . $keys[1] . '", "' . $keys[0] . '", "' . $value . '" ),';
        }

        $query = substr_replace($query ,"",-1);
        
        $wpdb->query( $query );
    }

    public function deleteMappingDataByProductId($productId) {
        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $wpdb->delete( $table_mapping_product_attribute_value , array( 'product_id' => $productId) );
    }
}
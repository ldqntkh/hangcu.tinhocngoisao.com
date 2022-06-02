<?php

include_once COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/media/slider-product-images.php';

class GroupAttributesApi {
    public function __construct() {

    }

    public static function getGroupAttributes($productType, $product_id, $renderType = 'html') {
        if ( empty( $productType ) || strval($productType) === '1') {
            return null;
        }
        global $wpdb;
        $tb_product_attributes = $wpdb->prefix . TB_COMPARE_PRODUCT_ATTRIBUTES;
        $table_group_attribute = $wpdb->prefix . TB_COMPARE_GROUP_ATTRIBUTES;
        $table_group_product_attributes = $wpdb->prefix . TB_COMPARE_GROUP_PRODUCT_ATTRIBUTES;
        $table_attribute_type = $wpdb->prefix . TB_COMPARE_ATTRIBUTE_TYPE;
        $table_product_type = $wpdb->prefix . TB_COMPARE_PRODUCT_TYPE;

        $attributes = $wpdb->get_results( "SELECT tb3.group_name, tb3.group_id, tb1.attribute_id, tb1.attribute_name, tb4.name as attribute_type, tb1.attribute_desc, tb2.display_index as tb2_index, tb3.display_index as tb3_index 
                                            FROM $tb_product_attributes as tb1 
                                            INNER JOIN $table_group_product_attributes as tb2 ON tb1.attribute_id = tb2.attribute_id
                                            INNER JOIN $table_group_attribute as tb3 ON tb2.group_id = tb3.group_id
                                            INNER JOIN $table_attribute_type as tb4 ON tb1.attribute_type = tb4.value
                                            INNER JOIN $table_product_type as tb5 ON tb3.product_type = tb5.id
                                            WHERE tb5.id = $productType 
                                            ORDER BY tb3.display_index, tb2.display_index" );
        
        if (count($attributes) == 0) return null;
        if ($renderType == 'html') {
            return GroupAttributesApi::renderHtmlGroupAttributes($attributes, $product_id);
        } else {
            return GroupAttributesApi::renderJsonGroupAttributes($attributes, $product_id);
        }
    }

    protected static function getMappingValue($product_id, $group_id, $attribute_id) {
        global $wpdb;
        $table_mapping_product_attribute_value = $wpdb->prefix . TB_COMPARE_MAPPING_PRODUCT_ATTRIBUTE_VALUE;
        $mappingvalue = $wpdb->get_results( "SELECT * FROM $table_mapping_product_attribute_value 
                                                WHERE $table_mapping_product_attribute_value.product_id = $product_id 
                                                AND $table_mapping_product_attribute_value.group_id = '$group_id' 
                                                AND $table_mapping_product_attribute_value.attribute_id = '$attribute_id'" );

        if (count($mappingvalue) == 1) {
            return $mappingvalue[0]->attribute_value;
        } else return null;
    }

    protected static function renderHtmlGroupAttributes($attributes, $product_id) {
        $group_id = '';
        $flag = false;
        $html = '';
        $attribute_keys = '';
        $index = 0;
        $numItems = count($attributes);
        foreach($attributes as $attribute) {
            $index++;
            if ($flag && $attribute->group_id != $group_id || $index -1 === $numItems) {
                $flag = false;
                $html .= '<input type="hidden" name="group_id['.$group_id.']" value="'.$attribute_keys.'"/>';
                $html .= '</div>';
                $group_id = '';
            }

            if ( !$flag && $attribute->group_id != $group_id ) {
                $group_id = $attribute->group_id;
                $flag = true;
                $html .= '<div class="group-attributes">';
                $html .= '<div class="header">';
                $html .= '<h3>' . $attribute->group_name . '</h3>'; 
                $html .= '</div>';
            }

            switch ($attribute->attribute_type) {
                case 'String':
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= '<input type="text" value="' . GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id) . '" name="attribute[' . $attribute->attribute_id . '/'.$group_id.']" id="attribute[' . $attribute->attribute_id . '/'.$group_id.']" />';
                    $html .= '</div>';
                    break;
                case 'Text':
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= '<textarea name="attribute[' . $attribute->attribute_id . '/'.$group_id.']" id="attribute[' . $attribute->attribute_id . '/'.$group_id.']" rows="4" cols="50">' . GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id) . '</textarea>';
                    $html .= '</div>';
                    break;
                case 'Html':
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= '<textarea name="attribute[' . $attribute->attribute_id . '/'.$group_id.']" id="' . $attribute->attribute_id . '-'.$group_id.'" rows="4" cols="50">' . GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id) . '</textarea>';
                    $html .= '<script> CKEDITOR.replace( "' . $attribute->attribute_id . '-'.$group_id.'" ); </script>';
                    $html .= '</div>';
                    break;
                case 'Image':
                    // I will add an image button
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= Session_Product_Images::outputImage( $attribute->attribute_id, $group_id, GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id));
                    $html .= '</div>';
                    break;
                case 'Slider':
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= Session_Product_Images::outputSlider( $attribute->attribute_id, $group_id, GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id));
                    $html .= '</div>';
                    break;
                case 'Number':
                    $html .= '<div class="form-group">';
                    $html .= '<label for="attribute[' . $attribute->attribute_id . '/'.$group_id.']">' . $attribute->attribute_name . '</label>';
                    $html .= '<input type="number" value="' . GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id) . '" name="attribute[' . $attribute->attribute_id . '/'.$group_id.']" id="attribute[' . $attribute->attribute_id . '/'.$group_id.']" />';
                    $html .= '</div>';
                    break;
            }
        }
        if (strlen($html) > 0) {
            $html .= '<input type="hidden" name="group_id['.$group_id.']" value="'.$attribute_keys.'"/>';
            $html .= '</div>';
        }
        
        return $html;
    }

    protected static function renderJsonGroupAttributes($attributes, $product_id) {
        $result = array();
        $group_id = '';
        $flag = false;
        $item = null;
        $index = 0;
        $numItems = count($attributes);
        foreach($attributes as $attribute) {
            $index++;
            if ($flag && $attribute->group_id != $group_id || $index -1 === $numItems) {
                array_push($result, $item);
                $group_id = '';
                $flag = false;
                $item = null;
                $index = 0;
            }

            if ( !$flag && $attribute->group_id != $group_id ) {
                $item = array();
                $group_id = $attribute->group_id;
                $flag = true;
                $item['group_id'] = $group_id;
                $item['group_name'] = $attribute->group_name;
            }
            if (!isset($item['attribute'])) {
                $item['attribute'][0] = array('id' => $attribute->attribute_id, 
                                                'name' => $attribute->attribute_name, 
                                                "type" => $attribute->attribute_type, 
                                                "value" => GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id));
            } else {
                $item['attribute'][count($item['attribute'])] = array('id' => $attribute->attribute_id, 
                                                                    'name' => $attribute->attribute_name, 
                                                                    "type" => $attribute->attribute_type, 
                                                                    "value" => GroupAttributesApi::getMappingValue($product_id, $group_id, $attribute->attribute_id));
            }
            
        }
        array_push($result, $item);
        return $result;
    }
}
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Session_Product_Images {

	public static function outputSlider( $attribute_id, $group_id, $images ) {
        // Now images will be save with syntax <image_id>,<image_id>,....
        $html = '';
		$html .= '<div id="product_images_container-'.$attribute_id.'-'.$group_id.'">';
        $html .= '<ul class="product_images">';
        $images_ = null;
        if ($images != null) {
            $images_ = explode(',', $images);
        }
        $images__ = '';
        if ( ! empty( $images_ ) ) {
            foreach ( $images_ as $image_id ) {
                $image = wp_get_attachment_image( $image_id, 'thumbnail' );
                if ( empty( $image ) ) {
                    continue;
                } else {
                    if (strlen($images__) == 0) {
                        $images__ .= $image_id;
                    } else {
                        $images__ .= ',' . $image_id;
                    }
                }

                $html .= '<li class="image" data-attachment_id="'.esc_attr( $image_id ).'">';
                $html .= $image;
                $html .= '<ul class="actions">
                            <li><a href="#" class="delete tips" 
                                data-input-id="' . $attribute_id . '-'.$group_id.'"
                                data-container-id="product_images_container-'.$attribute_id.'-'.$group_id.'"
                                data-tip="'.__( 'Delete image', TEXT_COMPARE_PRODUCT ).'">'.__( 'Delete', TEXT_COMPARE_PRODUCT ).'</a></li>
                        </ul>';
                $html .= '</li>';
            }
        }
		$html .= '</ul>';
        
        $html .= '<input type="hidden" name="attribute[' . $attribute_id . '/'.$group_id.']" id="' . $attribute_id . '-'.$group_id.'" 
                value="'.$images__.'" />';
		$html .= '<p class="add_product_images_slider hide-if-no-js">
                        <a href="#" data-choose="'.__( 'Add images to product gallery', TEXT_COMPARE_PRODUCT ).'" 
                            data-update="'. __( 'Add to gallery', TEXT_COMPARE_PRODUCT ).'" 
                            data-delete="'.__( 'Delete image', TEXT_COMPARE_PRODUCT ).'" 
                            data-text="'.__( 'Delete', TEXT_COMPARE_PRODUCT ).'"
                            data-input-id="' . $attribute_id . '-'.$group_id.'"
                            data-block="-'.$attribute_id.'-'.$group_id.'">'.__( 'Add slider images', TEXT_COMPARE_PRODUCT ).'</a>
                    </p>';
        $html .= '</div>';
        return $html;
    }
    
    public static function outputImage( $attribute_id, $group_id, $image_id ) {
        $html = '';
		$html .= '<div id="product_images_container-'.$attribute_id.'-'.$group_id.'">';
        $html .= '<ul class="product_images">';
        $image = wp_get_attachment_image( $image_id, 'thumbnail' );
        if ( !empty( $image ) ) {
            $html .= '<li class="image" data-attachment_id="'.esc_attr( $image_id ).'">';
            $html .= $image;
            $html .= '<ul class="actions">
                        <li><a href="#" class="delete tips" 
                            data-input-id="' . $attribute_id . '-'.$group_id.'"
                            data-container-id="product_images_container-'.$attribute_id.'-'.$group_id.'"
                            data-tip="'.__( 'Delete image', TEXT_COMPARE_PRODUCT ).'">'.__( 'Delete', TEXT_COMPARE_PRODUCT ).'</a></li>
                    </ul>';
            $html .= '</li>';
            
        }

        $html .= '</ul>';
        $html .= '<input data-select="image" type="hidden" name="attribute[' . $attribute_id . '/'.$group_id.']" id="' . $attribute_id . '-'.$group_id.'" 
                value="'.$image_id.'" />';
        
		$html .= '<p class="add_product_images_slider hide-if-no-js">
                        <a href="#" data-choose="'.__( 'Add images to product compare', TEXT_COMPARE_PRODUCT ).'" 
                            data-update="'. __( 'Add to gallery', TEXT_COMPARE_PRODUCT ).'" 
                            data-delete="'.__( 'Delete image', TEXT_COMPARE_PRODUCT ).'" 
                            data-text="'.__( 'Delete', TEXT_COMPARE_PRODUCT ).'"
                            data-input-id="' . $attribute_id . '-'.$group_id.'"
                            data-block="-'.$attribute_id.'-'.$group_id.'">'.__( 'Add an images', TEXT_COMPARE_PRODUCT ).'</a>
                    </p>';
        $html .= '</div>';
        return $html;
	}
}

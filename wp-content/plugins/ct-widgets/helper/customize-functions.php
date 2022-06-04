<?php


/**
 * Image Size
 *
 * @param null
 * @return array $hc_get_image_sizes_options
 *
 */
if ( !function_exists('hc_get_image_sizes_options') ) :
	function hc_get_image_sizes_options( $add_disable = false ) {
		global $_wp_additional_image_sizes;
		$choices = array();
		if ( true == $add_disable ) {
			$choices['disable'] = esc_html__( 'Không có hình ảnh', 'hangcu' );
		}
		foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
			$choices[ $_size ] = $_size . ' ('. get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		}
		$choices['full'] = esc_html__( 'full (original)', 'hangcu' );
		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {

			foreach ($_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key . ' ('. $size['width'] . 'x' . $size['height'] . ')';
			}
		}
		return apply_filters( 'hc_get_image_sizes_options', $choices );
	}
endif;


/**
 * WooCommerce Advanced Options
 *
 * @param null
 * @return array $hc_wc_advanced_options
 *
 */
if ( !function_exists('hc_wc_advanced_options') ) :
    function hc_wc_advanced_options() {
        $hc_wc_advanced_options =  array(
            'recent' => esc_html__( 'All products', 'hangcu' ),
            'cat' => esc_html__( 'Categories', 'hangcu' ),
            'tag' => esc_html__( 'Tags', 'hangcu' ),
            'featured' => esc_html__( 'Featured products', 'hangcu' ),
            'onsale' => esc_html__( 'On-sale products', 'hangcu' ),
        );
        return apply_filters( 'hc_wc_advanced_options', $hc_wc_advanced_options );
    }
endif;



/**
 * Column Selection
 *
 * @param null
 * @return array $hc_widget_column_number
 *
 */
if ( !function_exists('hc_widget_column_number') ) :
    function hc_widget_column_number() {
        $hc_widget_column_number =  array(
            1 => esc_html__( '1', 'hangcu' ),
            2 => esc_html__( '2', 'hangcu' ),
            3 => esc_html__( '3', 'hangcu' ),
            4 => esc_html__( '4', 'hangcu' ),
            5 => esc_html__( '5', 'hangcu' )
        );
        return apply_filters( 'hc_widget_column_number', $hc_widget_column_number );
    }
endif;



/**
 * Display Type
 *
 * @param null
 * @return array $hc_widget_display_type
 *
 */
if ( !function_exists('hc_widget_display_type') ) :
    function hc_widget_display_type() {
        $hc_widget_display_type =  array(
            'column' => esc_html__( 'Normal Column', 'hangcu' ),
            'carousel' => esc_html__( 'Carousel Column', 'hangcu' ),
            'scroll' => esc_html__( 'Scroll', 'hangcu' )
        );
        return apply_filters( 'hc_widget_display_type', $hc_widget_display_type );
    }
endif;


/**
 * Show selected category image and details
 *
 * @param null
 * @return array $hc_wc_cat_display_options
 *
 */
if ( !function_exists('hc_wc_cat_display_options') ) :
    function hc_wc_cat_display_options() {
        $hc_wc_cat_display_options =  array(
            'disable' => esc_html__( 'Disable', 'hangcu' ),
            'left' => esc_html__( 'Left', 'hangcu' ),
            'right' => esc_html__( 'Right', 'hangcu' )
        );
        return apply_filters( 'hc_wc_cat_display_options', $hc_wc_cat_display_options );
    }
endif;



/**
 * Order by
 *
 * @param null
 * @return array $hc_wc_product_orderby
 *
 */
if ( !function_exists('hc_wc_product_orderby') ) :
    function hc_wc_product_orderby() {
        $hc_wc_product_orderby =  array(
            'none' => esc_html__( 'None', 'hangcu' ),
            'ID' => esc_html__( 'ID', 'hangcu' ),
            'author' => esc_html__( 'Author', 'hangcu' ),
            'title' => esc_html__( 'Title', 'hangcu' ),
            'date' => esc_html__( 'Date', 'hangcu' ),
            'modified' => esc_html__( 'Modified Date', 'hangcu' ),
            'rand' => esc_html__( 'Random', 'hangcu' ),
            'comment_count' => esc_html__( 'Comment Count', 'hangcu' ),
            'menu_order' => esc_html__( 'Menu Order', 'hangcu' ),
            'sales' => esc_html__( 'Sales', 'hangcu' ),
            'price' => esc_html__( 'Price', 'hangcu' ),
        );
        return apply_filters( 'hc_wc_product_orderby', $hc_wc_product_orderby );
    }
endif;



/**
 * Order ASC DESC
 *
 * @param null
 * @return array $hc_post_order
 *
 */
if ( !function_exists('hc_post_order') ) :
    function hc_post_order() {
        $hc_post_order =  array(
            'ASC' => esc_html__( 'ASC', 'hangcu' ),
            'DESC' => esc_html__( 'DESC', 'hangcu' )
        );
        return apply_filters( 'hc_post_order', $hc_post_order );
    }
endif;


/**
 * View all options
 *
 * @param null
 * @return array $hc_adv_link_options
 *
 */
if ( !function_exists('hc_adv_link_options') ) :
    function hc_adv_link_options() {
        $hc_adv_link_options =  array(
            'disable' => esc_html__( 'Disable', 'hangcu' ),
            'normal-link' => esc_html__( 'Normal Link', 'hangcu' ),
            'new-tab-link' => esc_html__( 'Open in New Tab', 'hangcu' )
        );
        return apply_filters( 'hc_adv_link_options', $hc_adv_link_options );
    }
endif;


/**
 * Sanitize choices
 * @param null
 * @return string $hc_sanitize_choice_options
 *
 */
if ( ! function_exists( 'hc_sanitize_choice_options' ) ) :
    function hc_sanitize_choice_options( $value, $choices, $default ) {
        $input = esc_attr( $value );
        $output = array_key_exists( $input, $choices ) ? $input : $default;
        return $output;
    }
endif;
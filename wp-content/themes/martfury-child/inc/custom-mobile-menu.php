<?php

if( !function_exists( 'custom_mobile_menu_icon' ) ) {
    function custom_mobile_menu_icon($item_output, $item, $depth, $args) {
        if( $depth > 0 ) return $item_output;

        $atts           = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';


        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $icon_url = '';
        $icon_url = get_field( 'menu_icon_image', $item->ID );

        if (strlen($icon_url) > 0) {
            $icon_url = '<span class="menu-mobile-icon" style="background: url('. $icon_url .'); background-size: cover;"></span>';
        }

        $item_output = ! empty( $args->before ) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $icon_url;
        $item_output .= ( ! empty( $args->link_before ) ? $args->link_before : '' ) . $item->title . ( ! empty( $args->link_after ) ? $args->link_after : '' );
        $item_output .= '</a>';
        $item_output .= ! empty( $args->after ) ? $args->after : '';

        return $item_output;
    }
    add_filter( 'walker_nav_menu_start_el', 'custom_mobile_menu_icon', 10, 4 );
}
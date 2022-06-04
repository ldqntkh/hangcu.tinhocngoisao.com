<?php

if( !function_exists( 'hc_change_config_breadcrumb' ) ) {
    function hc_change_config_breadcrumb($args) {
        return array(
            'delimiter'   => '<span class="delimiter"><i class="fa fa-angle-right"></i></span>',
            'wrap_before' => '<div class="container-breadcrumb"><div class="container"><nav class="woocommerce-breadcrumb">',
            'wrap_after'  => '</nav></div></div>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Trang chủ', 'breadcrumb', 'electro' )
        );
    }
}

if( !function_exists('hc_shop_control_bar') ) {
    function hc_shop_control_bar() {
        global $wp_query;

		// if ( 1 === $wp_query->found_posts || ! woocommerce_products_will_display() ) {
		// 	return;
		// }

		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '>=' ) ) {
			if( wc_get_loop_prop( 'is_shortcode' ) ) {
				return;
			}
		}

		?><div class="shop-control-bar">
			<?php
			/**
			 * @hooked electro_shop_view_switcher - 10
			 * @hooked woocommerce_sorting - 20
			 */
			do_action( 'electro_shop_control_bar' );
			?>
		</div><?php
    }
}

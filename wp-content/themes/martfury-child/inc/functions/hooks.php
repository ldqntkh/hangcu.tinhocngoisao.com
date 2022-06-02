<?php


add_action( 'woocommerce_product_meta_start', 'woocommerce_template_loop_period', 10 );
if ( ! function_exists( 'woocommerce_template_loop_period' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function woocommerce_template_loop_period($post_post_excerpt) {
		$period = get_post_meta( get_the_id(), 'warranty_period', true );
		if (!empty($period)) {
			echo '<p class="warranty_period">Bảo hành: <strong>'. $period .'</strong> tháng</p><hr />';
		}
	}
}
add_filter( 'woocommerce_get_return_url', function($return_url) {
    return $return_url .= '?order_page=thank_you';
}, 10, 1 );

add_action( 'wp_head', function() { ?>
    <meta name="google-site-verification" content="8hVw_H1BnFRdhmFHDm_MviDGTC8YXvEeJFnzRKTLq9E" />
<?php } );

if ( ! function_exists( 'electro_is_wide_enabled' ) ) {
	/**
	 * Option to toggle wide
	 */
	function electro_is_wide_enabled() {
		return apply_filters( 'electro_is_wide_enabled', true );
	}
}
// test code
add_shortcode( 'show_nav_menu', function() {
    if ( has_nav_menu( 'shop_department' ) ) {
        echo '<ul id="menu-special-menu" class="sub-menu special-sub-menu menu">';

        $special_menus_html = '';
        
        if ($special_menus_html && strlen($special_menus_html) > 0) {
            echo $special_menus_html;
        } else {
            $special_menus_html = '';
            $special_menus = wp_get_nav_menu_items('shop-by-department');
            
            foreach($special_menus as $menu_item) {
                if ($menu_item->post_status === 'publish' && $menu_item->menu_item_parent === '0') {
                    $icon_url = '';
                    $background_url = '';

                    $icon_url = get_field( 'menu_icon_image', $menu_item->ID );
                        
                    // $icon_url = get_field('menu_icon', $cat);
                    $background_url = get_field('background_menu', $menu_item->ID );

                    if (strlen($icon_url) > 0) {
                        $icon_url = '<span style="background: url('. $icon_url .'); background-size: cover;"></span>';
                    }

                    if (strlen($background_url) > 0) {
                        $background_url = 'style="background: url('. $background_url .') no-repeat; background-size: 300px 300px; background-position: bottom right; background-color: white;"';
                    }
                    
                    $special_menus_html .= '<li id="menu-item-' . $menu_item->ID . '" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-has-children menu-item-' . $menu_item->ID . '">'
                            // .'<i class="fa fa-angle-right angle-down"></i>'.
                            .'<a href="' . $menu_item->url . '">'
                                . $icon_url
                                . '<span class="title-url">'.$menu_item->title.'</span>'
                            . '</a>';
                    $special_menus_html .= '<div class="sub-menus" ' .$background_url.  '>';
                        $special_menus_html .= '<section class="sub-menu-lv1">';
                        foreach($special_menus as $menu_item_lv1) {
                            if ($menu_item_lv1->post_status === 'publish' && $menu_item_lv1->menu_item_parent == $menu_item->ID) {
                                
                                if ( !empty( $menu_item_lv1->title ) ) {
                                    $posts = get_posts(
                                        array(
                                            'name'      => $menu_item_lv1->title,
                                            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')  
                                        )
                                    );
                                    if ( $posts )
                                    {
                                        $special_menus_html .= get_post_field('post_content', $posts[0]->ID);
                                    }
                                    break;
                                }

                                //$special_menus_html .= '</section>';
                            }
                        }
                        $special_menus_html .= '</section>';
                    $special_menus_html .= '</div>';
                    $special_menus_html .= '</li>';
                }
            }

            // set_cache_by_key('special_menus_html', $special_menus_html);
            echo $special_menus_html;
        }
        
        echo '</ul>';
    }
} );
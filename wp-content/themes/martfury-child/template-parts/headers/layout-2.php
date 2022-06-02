<?php
$css_header_logo = '';
$extras          = martfury_menu_extras();
if ( empty( $extras ) || ! in_array( 'department', $extras ) ) {
	$css_header_logo = 'hide-department';
}

$dep_open  = false;
$dep_class = '';
if ( martfury_is_homepage() && martfury_get_option( 'department_open_homepage' ) == 'open' ) {
	$dep_open  = true;
	$dep_class = 'mf-close';
}

$sticky_header = intval( martfury_get_option( 'sticky_header' ) );


function custom_martfury_extra_department( $dep_close = false, $id = '' ) {
    $extras   = martfury_menu_extras();
    $location = 'shop_department';

    if ( empty( $extras ) || ! in_array( 'department', $extras ) ) {
        return;
    }

    if ( ! has_nav_menu( $location ) ) {
        return;
    }

    $dep_text = '<i class="icon-menu"><span class="s-space">&nbsp;</span></i>';
    $c_link   = martfury_get_option( 'custom_department_link' );
    if ( ! empty( $c_link ) ) {
        $dep_text .= '<a href="' . esc_url( $c_link ) . '" class="text">' . martfury_get_option( 'custom_department_text' ) . '</a>';
    } else {
        $dep_text .= '<span class="text">' . martfury_get_option( 'custom_department_text' ) . '</span>';
    }

    $dep_open = 'mf-closed';

    if ( $dep_close && martfury_is_homepage() ) {
        $dep_open = martfury_get_option( 'department_open_homepage' ) == 'open' ? 'open' : $dep_open;
    }
    $cat_style = '';
    $space     = martfury_get_option( 'department_space_2_homepage' );
    if ( in_array( martfury_get_option( 'header_layout' ), array( '2', '3' ) ) ) {
        $space = martfury_get_option( 'department_space_homepage' );
    }
    if ( martfury_is_homepage() && $space ) {
        $cat_style = sprintf( 'style=padding-top:%s', esc_attr( $space ) );
    }

    ?>
    <div class="products-cats-menu <?php echo esc_attr( $dep_open ); ?>">
        <h2 class="cats-menu-title"><?php echo wp_kses( $dep_text, wp_kses_allowed_html( 'post' ) ); ?></h2>

        <div class="toggle-product-cats nav" <?php echo esc_attr( $cat_style ); ?>>

            <?php
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
                                // $slug = $menu_item->url;
                                // if (strlen($slug) && $slug[strlen($slug) - 1] === '/') $slug = substr($slug, 0, strlen($slug) - 1);
                                // $slug = explode('/', $slug);
                                // $slug = $slug[count($slug)- 1];
                                // $cat = get_term_by( 'slug', $slug, 'product_cat' );
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
                ?>
                <div class="responsive-special-sub-menu clearfix"></div>

        </div>
    </div>
    <?php
}


?>

<div class="header-main-wapper">
    <div class="header-main">
        <div class="<?php echo martfury_header_container_classes(); ?>">
            <div class="row header-row">
                <div class="header-logo col-lg-3 col-md-6 col-sm-6 col-xs-6 <?php echo esc_attr( $css_header_logo ); ?>">
                    <div class="d-logo">
						<?php get_template_part( 'template-parts/logo' ); ?>
                    </div>

                    <div class="d-department hidden-xs hidden-sm <?php echo esc_attr( $dep_class ); ?>">
						<?php custom_martfury_extra_department( false ); ?>
                    </div>

                </div>
                <div class="header-extras col-lg-9 col-md-6 col-sm-6 col-xs-6">
					<?php martfury_extra_search(); ?>
                    <ul class="extras-menu">
						<?php
						martfury_extra_hotline();
						martfury_extra_compare();
						martfury_extra_wislist();
						martfury_extra_cart();
						martfury_extra_account();
						?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-menu hidden-xs hidden-sm">
    <div class="<?php echo martfury_header_container_classes(); ?>">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="col-header-menu">
					<?php martfury_header_menu(); ?>
					<?php if ( $dep_open ) : ?>
                        <div class="d-department-sticky hidden-md hidden-xs hidden-sm">
							<?php martfury_extra_department( true ); ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mobile-menu hidden-lg hidden-md">
    <div class="container">
        <div class="mobile-menu-row">
            <a class="mf-toggle-menu" id="mf-toggle-menu" href="#">
                <i class="icon-menu"></i>
            </a>
			<?php martfury_extra_search( false ); ?>
        </div>
    </div>
</div>
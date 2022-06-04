<?php 
/**
 * Template Name: CT danh mục sản phẩm
 */
// if( !wp_is_mobile() ) {
//     wp_redirect(home_url(), 301);
//     die;
// }
get_header();
// get danh sách parent menu
// $categories = get_terms(['taxonomy' => 'product_cat','hide_empty' => true, 'parent' => 0]);

// if ($categories) {
    echo '<div id="danh-muc-san-pham">';

    

    // render menu
    $theme_locations = get_nav_menu_locations();

    $menu_obj = get_term( $theme_locations['hand-held-nav'], 'nav_menu' );
    $nav_menu = wp_get_nav_menu_items( $menu_obj->term_id, array('menu_item_parent' => "0") );
    if( !empty( $nav_menu ) ) {
        foreach( $nav_menu as $nav_item ): 
            if( $nav_item->menu_item_parent == '0' ):
        ?>
            <a href="<?= $nav_item->url ?>"><i class="<?= $nav_item->icon ?>"></i> <h3><?= $nav_item->title ?></h3> </a>
        <?php endif; 
        endforeach;
    }

    echo '</div>';
// }

get_footer();

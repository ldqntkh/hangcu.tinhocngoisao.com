<?php

    add_filter( 'body_class', function( $classes ) {
        if (checkHasFilter()) {
            return array_merge( $classes, array( 'has-filter-category' ) );
        }

        if (!wc_get_loop_prop( 'total' ) && get_queried_object() && (is_product_category() || is_shop())) {
            return array_merge( $classes, array( 'hangcu-product-not-found' ) );
        }

        return $classes;
    } );

    function category_banner_promotion() {
        if (checkHasFilter() || !wc_get_loop_prop( 'total' ) || !get_queried_object() || !is_product_category()) {
            return null;
        }

        $cat_id = get_queried_object()->term_id;
        $bannerPromotion = get_field('banner_promotion', 'product_cat_'.$cat_id);

        if ($bannerPromotion) {
            echo '<div class="banner-promotion">';
            echo '<i class="fas fa-chevron-left btn-prev-slick"></i>';
            echo $bannerPromotion;
            echo '<i class="fas fa-chevron-right btn-next-slick"></i>';
            echo '</div>';
        }
    }

    function hangcu_category_hot_deals() {
        if (checkHasFilter() || !wc_get_loop_prop( 'total' )  || !get_queried_object() || !is_product_category()) {
            return null;
        }

        $cat_id = get_queried_object()->term_id;

        $meta_key_docquyengiatot = "hangcu_cat_docquyengiatot";
        $meta_key_docquyengiatot_title = "hangcu_cat_docquyengiatot_title";

        $meta_key_sanphamgiamsoc = "hangcu_cat_sanphamgiamsoc";
        $meta_key_sanphamgiamsoc_title = "hangcu_cat_sanphamgiamsoc_title";

        $group_better_price = get_term_meta( intval($cat_id), 'hangcu_cat_docquyengiatot', true);
        $value_docquyengiatot_title = get_term_meta( intval($cat_id), $meta_key_docquyengiatot_title, true );
        $value_docquyengiatot_title = empty( $value_docquyengiatot_title ) ? __('Độc quyền giá tốt', 'hangcu') : $value_docquyengiatot_title;

        $group_discount_shock = get_term_meta( intval($cat_id), 'hangcu_cat_sanphamgiamsoc', true);
        $value_sanphamgiamsoc_title = get_term_meta( intval($cat_id), $meta_key_sanphamgiamsoc_title, true );
        $value_sanphamgiamsoc_title = empty( $value_sanphamgiamsoc_title ) ? __('Sản phẩm giảm sốc', 'hangcu') : $value_sanphamgiamsoc_title;

        if (empty($group_discount_shock) && empty($group_better_price)) {
            return null;
        }
    ?>
        <div class="group-tab-hot-deals">
            <ul class="nav nav-tabs" role="tablist">
                <?php 
                    $discount_products = hangcu_get_products_by_cat($group_discount_shock);
                    if (!empty($group_discount_shock) && $discount_products && $discount_products->post_count > 0 ) : ?>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#san_pham_giam_soc" role="tab" aria-controls="san_pham_giam_soc" aria-selected="true">
                            <?php echo $value_sanphamgiamsoc_title; ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php 
                    $better_products = hangcu_get_products_by_cat($group_better_price);
                    if (!empty($group_better_price) && $better_products && $better_products->post_count > 0 ) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo empty($group_discount_shock) ? 'active' : ''; ?>" data-toggle="tab" href="#doc_quyen_gia_tot" role="tab" aria-controls="doc_quyen_gia_tot" aria-selected="false">
                            <?php echo  $value_docquyengiatot_title; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="tab-content">
                <?php if (!empty($group_discount_shock)) : ?>
                    <div class="tab-pane active" id="san_pham_giam_soc" role="tabpanel" aria-labelledby="san_pham_giam_soc-tab">
                        <?php hangcu_generate_slider_cat_products($group_discount_shock); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($group_better_price)) : ?>
                    <div class="tab-pane <?php echo empty($group_discount_shock) ? 'active' : ''; ?>" id="doc_quyen_gia_tot" role="tabpanel" aria-labelledby="doc_quyen_gia_tot-tab">
                        <?php hangcu_generate_slider_cat_products($group_better_price); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    if ( ! function_exists( 'hangcu_generate_slider_cat_products' ) ) {
        function hangcu_generate_slider_cat_products($cat_slug) {
            $product_columns_wide = 4;

            $args = apply_filters( 'hangcu_product_hot_deals_carousel', array(
                'columns'       => 4,
                'section_args'  => array(
                    'section_class'     => 'section-products-carousel'
                ),
                'carousel_args' => array(
                    'items'             => 4,
                    'autoplay'          => false,
                    'responsive'        => array(
                        '0'     => array( 'items' => 2 ),
                        '576'  => array( 'items' => 3),
                        '992'  => array( 'items' => 4)
                    )
                )
            ) );

            if ( electro_is_wide_enabled() ) {
                $args['carousel_args']['responsive']['1480'] = array( 'items' => $product_columns_wide );
                $args['columns_wide'] = $product_columns_wide;
            }

            $products       = hangcu_cat_product_block_content($cat_slug);

            $args['section_args']['products_html'] = $products;

            electro_products_carousel( $args['section_args'], $args['carousel_args'] );
        }
    }

    function hangcu_cat_product_block_content($cat_slug) {
        $products = hangcu_get_products_by_cat($cat_slug);

        if ( $products != null && $products->have_posts()) {
            ob_start();
            $original_post = $GLOBALS['post'];
            while ( $products->have_posts() ) : $products->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            $GLOBALS['post'] = $original_post; 
            return '<div class="woocommerce columns-5 "><ul data-view="grid" data-toggle="regular-products" class="products columns-5 columns__wide--5">' . ob_get_clean() . '</ul></div>';
        }
        return null;
    }

    function hangcu_get_products_by_cat($cat_slug) {
        if ( !empty( $cat_slug ) ) {
            ob_start();
  
            $query_args = array(
                'posts_per_page' => -1,
                'no_found_rows'  => 1,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'product_cat'       => $cat_slug,
                'orderby'        => 'post__in',
            );
 
            if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'outofstock',
                        'operator' => 'NOT IN',
                        ),
                    );
                }
    
            $products = new WP_Query( apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $query_args ) );

            wp_reset_query();

            return $products;
        }

        return null;
    }

    function hangcu_top_five_best_sellers() {
        if (checkHasFilter() || !wc_get_loop_prop( 'total' ) || !get_queried_object() || !is_product_category()) {
            return null;
        }

        $cat_id = get_queried_object()->term_id;

        $meta_key_top5 = "hangcu_cat_top5banchay";

        $bestSellers = get_term_meta( intval($cat_id), $meta_key_top5, true);

        $bestSellers = json_decode($bestSellers);

        if (empty($bestSellers) || !is_array($bestSellers)) {
            return null;
        }
    ?>
        <div class="top-five-best-sellers">
            <h3 class="title-block"><span><?php echo $bestSellers[0]->title; ?></span></h3>
            <div class="content-block">
                <div class="list-filter">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                            foreach($bestSellers as $index => $value):
                                $className = '';

                                if ($index === 0) {
                                    $className = 'active';
                                }
                        ?>
                            <li class="nav-item" data-link-cat="<?php echo $value->link_category; ?>" data-title="<?php echo $value->title; ?>">
                                <a class="nav-link <?php echo $className; ?>" data-toggle="tab" href="#<?php echo 'tab_best_seller'.$index; ?>" role="tab" aria-controls="<?php echo $index; ?>" aria-selected="true">
                                    <?php echo $value->short_title;?>
                                </a>
                            </li>
                        <?php
                            endforeach;
                        ?>
                    </ul>
                    <a class="link-cat" href="<?php echo $bestSellers[0]->link_category; ?>">
                        Xem thêm <strong><?php echo $bestSellers[0]->title; ?></strong>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="tab-content">
                    <?php
                        foreach($bestSellers as $index => $value):
                            $className = '';

                            if ($index === 0) {
                                $className = 'active';
                            }
                    ?>
                        <div class="tab-pane <?php echo $className; ?>" id="<?php echo 'tab_best_seller'.$index; ?>" role="tabpanel" aria-labelledby="<?php echo $index;?>">
                            <?php hangcu_generate_slider_cat_products($value->group_category); ?>
                        </div>
                    <?php
                        endforeach;
                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    function checkHasFilter() {
        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
        $min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0;
        $max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0;
        $orderby          = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : 0;
		$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.

		if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) || !empty($orderby) ) {
            return true;
        }

        return false;
    }

    function wcc_change_breadcrumb_home_text( $defaults ) {
        $cate = get_queried_object();

        if($cate && is_product_category() && wc_get_loop_prop( 'total' )){
            $defaults[count($defaults) - 1][0] = "<strong>".wc_get_loop_prop( 'total' )."&nbsp;&nbsp;</strong>".$defaults[count($defaults) - 1][0];
        }
    
        return $defaults;
    }

    function filter_woocommerce_page_title( $page_title ) {
        $cate = get_queried_object();

        if($cate && is_product_category()){
            return wc_get_loop_prop( 'total' ).' '.$page_title;
        }

        return $page_title;
    }

    function hangcu_no_products_found() {
        $cate = get_queried_object();

        if( is_search() ) {
            if( electro_detect_is_mobile() ) {
                $url = add_query_arg( array(
                    's' => $_GET['s'],
                    'post_type' => $_GET['post_type'],
                    '_type' => 'mb'
                ), home_url() );
            } else {
                $url = add_query_arg( array(
                    's' => $_GET['s'],
                    'post_type' => $_GET['post_type'],
                    '_type' => 'pc'
                ), home_url() );
            }
        } else {
            $category = get_queried_object();
            $url = get_category_link($category);
        }
        
        $name = __('sản phẩm', 'hangcu');

        if ($cate && is_product_category()) {
            $url = get_category_link($cate->term_id);
            $name = $cate->name;
        }

        if($cate && is_product_category() || is_shop()) {
        ?>
            <div class="not-found-product">
                <img src="<?php echo get_option( CUSTOM_PREFERECE_GLOBAL )['config_not_found_image']; ?>"/>
                <span class="content-not-found"><?php echo __('Chúng tôi không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm', 'hangcu') ?></span>
                <?php if( is_search() ) : ?>
                    <span class="content-contact">
                        Nếu bạn cần hỗ trợ, vui lòng liên hệ tổng đài <a href="tel:18006975">1800 6975</a>
                    </span>
                    <a class="go-home" href="<?= get_permalink( woocommerce_get_page_id( 'shop' ) ) ?>">Xem tất cả sản phẩm</a>
                <?php else : ?>
                    <?php the_widget('CT_Layered_Nav_Filters'); ?>
                    <?php
                        if (!wc_get_loop_prop( 'total' ) && checkHasFilter()):
                    ?>
                        <div class="view-all">
                            hoặc <a href="<?php echo $url; ?>"><?php echo __('xem tất cả', 'hangcu') .' '. $name; ?></a>
                        </div>
                    <?php endif;?>
                <?php endif; ?>
            </div>
        <?php
        }
    }

    function change_filter_translate_sort( $options ) {
        unset( $options['menu_order'] );
        unset( $options['date'] );
        unset( $options['rating'] );
        unset($options['relevance']);

        $options['popularity'] = __('Bán chạy', 'hangcu');
        $options['price'] = __('Giá thấp đến cao', 'hangcu');
        $options['price-desc'] = __('Giá cao đến thấp', 'hangcu');
        return $options;
    }

    /**
	 * Get the product thumbnail for the loop.
	 */
    function hangcu_loop_product_thumbnail_with_label_campaign($content) {
        $thumbnail = woocommerce_get_product_thumbnail();
        $top_left_obj = get_field_object('tag_label_left');
        $top_left = get_field('tag_label_left');
        $top_left_value = $top_left_obj['choices'][ $top_left ];

        $top_right_obj = get_field_object('tag_label_right');
        $top_right = get_field('tag_label_right');
        $top_right_value = $top_right_obj['choices'][ $top_right ];


        // $prepaid = get_field('cho_phep_tra_gop');
        // $promotion = get_field('khuyen_mai_kem_theo');
        $template = '<div class="product-thumbnail product-item__thumbnail">%s %s %s</div>';

        if ($top_left && $top_left != '""' && $top_left_value) {
            $top_left_html = '<span class="top-left">'. $top_left_value .'</span>';
        }
        if ($top_right && $top_right != '""' && $top_right_value) {
            if( $top_right == 'giam-x' || $top_right == 'giam-%' ) {
                $top_right_html = '';
                global $product;
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                if( $sale_price && $sale_price > 0 ) {
                    if( $top_right == 'giam-x' ) {
                        $top_right_value = str_replace('xK', '', $top_right_value);
                        $price = $regular_price - $sale_price;
                        if( $price > 0 ) {
                            $price = floor($price / 1000); /// vnd
                            $top_right_html = '<span class="top-right">'. $top_right_value .' ' . $price . 'K</span>';
                        }
                    } else {
                        $top_right_value = str_replace('%', '', $top_right_value);
                        $percent = ceil(100 -( $sale_price / $regular_price ) * 100);
                        $top_right_html = '<span class="top-right">'. $top_right_value .' ' . $percent . '%</span>';
                    }
                    
                }
            } else if( $top_right == 'qua-tang' ) {
                $top_right_html = '<span class="top-right"><i class="fas fa-gift"></i>'. $top_right_value .'</span>';
            } else {
                $top_right_html = '<span class="top-right">'. $top_right_value .'</span>';
            }
        }

        // if ($promotion) {
        //     $promtion_label = get_field('ten_khuyen_mai_kem_them');

        //     if ($promtion_label) {
        //         $promotion = $promtion_label;
        //     } else {

        //         $promtion_label = $promotion['label'];
        //     }

        //     $promotion = '<div class="wrapper-promotion-label"><i class="fas fa-gift"></i><span class="promotion-label">'.$promtion_label.'</span></div>';
        // }

        return wp_kses_post( sprintf( $template, $top_left_html, $thumbnail, $top_right_html ) );
    }

    function hangcu_hook_ratting() {
        global $product;

        $totalCount = round($product->get_average_rating(), 1);
        ?>
            <div class="hangcu-rating">
                <?php if ($totalCount): ?>
                    <span class="total-rating"><?php echo $totalCount; ?>/5</span>
                    <span><?php echo $product->get_review_count(); ?> đánh giá</span>
                <?php endif; ?>
            </div>
        <?php
    }


    function hangcu_custom_variation_price( $price, $product ) {
      $price = '';

      $price .= wc_price($product->get_price());

      return $price;
    }

    function hangcu_hook_description_loop_product() {
      $description = get_field('gift_description');

      echo '<div class="gift-description">'.$description.'</div>';
    }

    function hangcu_show_brand_category() {
      if (!wc_get_loop_prop( 'total' ) || !get_queried_object() || !is_product_category()) {
          return null;
      }

      $cat_id = get_queried_object()->term_id;
      $brands = get_field('category_brands', 'product_cat_'.$cat_id);

      if ($brands) {
        echo '<div class="hangcu-category-brands">';
        echo $brands;
        echo '</div>';
      }
    }

    function hangcu_remove_cat_name_is_group($terms) {
        foreach($terms as $key => $term) {
          if (get_term_meta( intval($term->term_id), 'is_cat_select', true) === '1') {
            unset($terms[$key]);
          }
        }

        return $terms;
    }

    // define the woocommerce_breadcrumb_main_term callback 
    function filter_woocommerce_breadcrumb_main_term( $terms_0, $terms ) { 
        if ( count( $terms ) == 1 ) {
            return $terms_0;
        } 
        else {
            if (get_term_meta( intval($terms_0->term_id), 'is_cat_select', true) !== '1') {
                return $terms_0; 
            } else {
                foreach( $terms as $term ) {
                    if (get_term_meta( intval($term->term_id), 'is_cat_select', true) !== '1') {
                        return $term; 
                    }
                }
            }
        }
        return $terms_0;
    };
    
    // add_filter( 'walker_nav_menu_start_el', 'hangcu_display_menu_icon', 10, 4 );
    function hangcu_display_menu_icon( $item_output, $item, $depth, $args ) {

        if ( strpos( $item_output, '<i class="ec ec-user"></i>' ) !== false ) {
            $current_user = wp_get_current_user();
            if ( $current_user ) {
                $item_output_text = str_replace( '<i class="ec ec-user"></i>', '', $item_output );
                $text = explode( '>', explode( '</a>', $item_output_text )[0] )[1];
                $user_display_name = !empty( $current_user->display_name ) ? 'Chào ' . esc_html( $current_user->display_name ) : $text;
                $item_output = str_replace( $text . '</a>', $user_display_name . '</a>', $item_output );
            }
            // $item_output = str_replace( $text . '</a>', $text_html . '</a>', $item_output );
            $titles = explode( 'title=', $item_output );
            $item_output = $titles[0];
            $titles = explode( 'href=', $titles[1] );
            $item_output .= ' href='.$titles[1];
            return $item_output;
        } else {
            // $icon_image = get_field( 'menu_icon_image', $item->ID );
    
            // // replace text in to element span
            // $item_output_text = str_replace( '<i class="icon-menu"></i>', '', $item_output );
            // $text = explode( '>', explode( '</a>', $item_output_text )[0] )[1];
            // $text_html = '<span class="mn-title">'.$text.'</span>';

            // if ( !empty( $icon_image ) && $icon_image ) {
            //     $icon_html = '<i class="icon-menu" style="
            //         background-image: url('.$icon_image.');
            //         background-size: cover;
            //         background-position: center;
            //     "></i>';
            //     $item_output = str_replace( '<i class="icon-menu"></i>', $icon_html, $item_output );
            // }
            // $item_output = str_replace( $text . '</a>', $text_html . '</a>', $item_output );
            // return $item_output;

            // try display icon with css
            $icon_image = get_field( 'menu_icon_class', $item->ID );
    
            // replace text in to element span
            $item_output_text = str_replace( '<i class="icon-menu"></i>', '', $item_output );
            $text = explode( '>', explode( '</a>', $item_output_text )[0] )[1];
            $text_html = '<span class="mn-title">'.$text.'</span>';

            if ( !empty( $icon_image ) && $icon_image ) {
                $icon_html = '<i class="icon-menu '.$icon_image.'"></i>';
                $item_output = str_replace( '<i class="icon-menu"></i>', $icon_html, $item_output );
            }
            $item_output = str_replace( $text . '</a>', $text_html . '</a>', $item_output );
            $titles = explode( 'title=', $item_output );
            $item_output = $titles[0];
            $titles = explode( 'href=', $titles[1] );
            $item_output .= ' href='.$titles[1];
            return $item_output;
        }
    }

    add_filter( 'electro_nav_menu_link_attributes', function($atts, $item, $args, $depth) {
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                if( 'title' === $attr ) {
                    $atts['title']='';
                }
            }
        }
        return $atts;
    }, 10, 4 );

    // add_filter( 'walker_nav_menu_start_el', function($item_output, $item, $depth, $args) {
    //     $item_output = str_replace( $text . '</a>', $text_html . '</a>', $item_output );
    //     $titles = explode( 'title=', $item_output );
    //     $item_output = $titles[0];
    //     $titles = explode( 'href=', $titles[1] );
    //     $item_output .= ' href='.$titles[1];

    //     return '';
    //     return $item_output;
    // }, 10, 4 )
?>
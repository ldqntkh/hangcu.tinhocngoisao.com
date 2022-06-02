<?php 
/*
    Template Name: Recent Viewed Products
*/
    get_header();
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
    $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

    if ( empty( $viewed_products ) ) {
        // code here
        echo '<span class="error">Bạn chưa xem qua sản phẩm nào</span>';
    } else {
        ob_start();

        $query_args = array(
            'posts_per_page' => -1,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'post__in'       => $viewed_products,
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
            ); // WPCS: slow query ok.
        }

        $online_shop_featured_query = new WP_Query( apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $query_args ) );
        $div_attr = 'class="featured-entries-col woocommerce column"';
        if ( $online_shop_featured_query->have_posts() ) { ?>
            <div <?php echo $div_attr;?>>
                <?php
                $online_shop_featured_index = 1;
                $template_args = array(
                    'widget_id' => 'woocommerce_recently_viewed_products',
                );
                while ( $online_shop_featured_query->have_posts() ) :$online_shop_featured_query->the_post();
                    $online_shop_list_classes = 'single-list  acme-col-4';
                    ?>
                    <div class="<?php echo esc_attr( $online_shop_list_classes ); ?>">
                        <ul class="post-container products custom-products-viewed">
                            <?php wc_get_template( 'content-widget-product.php', $template_args ); ?>
                        </ul><!--.post-container-->
                    </div><!--dynamic css-->
                    <?php
                    $online_shop_featured_index++;
                endwhile;
                ?>
            </div><!--featured entries-col-->
            <?php
            echo "<div class='clearfix'></div>";


            // while ( $r->have_posts() ) {
            //     $r->the_post();
            //     wc_get_template( 'content-widget-product.php', $template_args );
            // }

            // echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );

        } else {
            echo '<span class="error">Bạn chưa xem qua sản phẩm nào</span>';
        }

        wp_reset_postdata();
    }
?>

<?php get_footer();
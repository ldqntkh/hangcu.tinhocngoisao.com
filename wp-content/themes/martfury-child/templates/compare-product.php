<?php 

/*
    Template Name: Compare product
*/

if ( isset( $wp->query_vars['product_compares'] ) ) {
    $product_compares = $wp->query_vars['product_compares'];
    $product_slug_1 = explode('-vs-', $product_compares)[0];
    $product_slug_2 = explode('-vs-', $product_compares)[1];

    $_pf = new WC_Product_Factory();  
    $product_1 = get_page_by_path( $product_slug_1, OBJECT, 'product' );
    $product_1 = $_pf->get_product( $product_1->ID );
    $product_2 = get_page_by_path( $product_slug_2, OBJECT, 'product' );
    $product_2 = $_pf->get_product( $product_2->ID );

    include_once WP_PLUGIN_DIR. '/compare-products/product_compare/api/productType.php';
    include_once WP_PLUGIN_DIR. '/compare-products/product_compare/api/groupAttributes.php';
    // check variant product and product type
    $group_id_1 = ProductTypeApi::getGroupProductMappingByProductId($product_1->get_id());
    $group_id_2 = ProductTypeApi::getGroupProductMappingByProductId($product_2->get_id());
    if ($group_id_1 != $group_id_2) {
        header("Location:" . home_url());
        exit;
    }

    $custom_title = __('So sánh chi tiết ', 'compare-product') . $product_1->get_name() . ' & ' . $product_2->get_name() . __('|TinHocNgoiSao', 'compare-product');

    $group_attributes_1 = GroupAttributesApi::getGroupAttributes($group_id_1, $product_1->get_id(), 'json');
    $group_attributes_2 = GroupAttributesApi::getGroupAttributes($group_id_2, $product_2->get_id(), 'json');
    if (empty($group_attributes_1) || empty($group_attributes_2)) {
        header("Location:" . home_url());
        exit;
    }
    $product_type_id = ProductTypeApi::getGroupProductMappingByProductId($product_1->get_id());
    global $custom_seo_title;
    $custom_seo_title = $custom_title;
    
    add_filter('wpseo_opengraph_title', 'change_compare_title', 1, 1);
    add_filter('wpseo_title', 'change_compare_title', 1, 1);
    function change_compare_title($title) {
        return $GLOBALS['custom_seo_title'];
    }
    get_header();
?>
    <div class="product-compare">

        <input class="d-none list-id-compare" type="hidden" value="<?php echo $product_1->get_id() . ',' . $product_2->get_id(); ?>"/>
        <div class="header">
            <p>
                <?= __('So sánh chi tiết sản phẩm', 'compare-product') ?>
            </p>
        </div>
        <div class="rows">
            <div class="column column-1"></div>
            <div class="column column-2">
                <div class="product-data">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_1->get_id() ), 'medium', true )[0] ?>" alt="" />
                </div>
            </div>
            <div class="column column-2">
                <div class="product-data">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_2->get_id() ), 'medium', true )[0] ?>" alt="" />
                </div>
                <button class="remove-compare" data-id="<?php echo $product_2->get_id(); ?>">-</button>
            </div>
            <div class="column column-3">
                <div class="product-data">
                    <button id='add-compare-product'>+</button>
                </div>
            </div>
        </div>
        <div class="header">
            <p><?php echo __("Thông tin chung", 'compare-product') ?></p>
        </div>
        <div class="rows">
            <div class="column column-1">
                <?php echo __("Tên sản phẩm", 'compare-product') ?>
            </div>
            <div class="column column-2">
                <a target="_blank" class="product-compare-name" href="<?php echo get_permalink( $product_1->get_id()) ?>"><?php echo $product_1->get_name() ?></a>
            </div>
            <div class="column column-2">
                <a target="_blank" class="product-compare-name" href="<?php echo get_permalink( $product_2->get_id()) ?>"><?php echo $product_2->get_name() ?></a>
            </div>
            <div class="column column-3"></div>
        </div>
        <div class="rows">
            <div class="column column-1">
                <?php echo __("Giá bán", 'compare-product') ?>
            </div>
            <div class="column column-2">
                <span><?php echo wc_price($product_1->get_price()) ?></span>
            </div>
            <div class="column column-2">
                <span><?php echo wc_price($product_2->get_price()) ?></span>
            </div>
            <div class="column column-3"></div>
        </div>

        <div class="rows">
            <div class="column column-1">
            </div>
            <div class="column column-2">
                <?php if ( $product_1->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_1->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <div class="group-btns">
                                <button <?php if ( $product_1->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_1->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="column column-2">
                <?php if ( $product_2->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_2->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <div class="group-btns">
                                <button <?php if ( $product_2->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_2->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="column column-3"></div>
        </div>

        <?php
            // get data
            for($i = 0; $i < count($group_attributes_1); $i++) { ?>
                <div class="header">
                    <p><?php echo $group_attributes_1[$i]['group_name'] ?></p>
                </div>
                <?php for ($k = 0; $k < count($group_attributes_1[$i]['attribute']); $k++) { ?>
                    <div class="rows">
                        <div class="column column-1">
                            <?php echo $group_attributes_1[$i]['attribute'][$k]['name'] ?>
                        </div>
                        <div class="column column-2">
                            <div class="sub-name">
                                <strong><?php echo $group_attributes_1[$i]['attribute'][$k]['name'] ?></strong>
                            </div>
                            <?php 
                                if ($group_attributes_1[$i]['attribute'][$k]['type'] == 'Image') {
                                    $image = wp_get_attachment_image( $group_attributes_1[$i]['attribute'][$k]['value'], 'medium' );
                                    echo $image;
                                } elseif ($group_attributes_1[$i]['attribute'][$k]['type'] == 'Slider') {
                                    // code here
                                    $images = $group_attributes_1[$i]['attribute'][$k]['value'];
                                    $images_ = null;
                                    if ($images != null) {
                                        $images_ = explode(',', $images);
                                    }
                                    if ( ! empty( $images_ ) ) {
                                        echo '<div class="slider-images">';
                                        foreach ( $images_ as $image_id ) {
                                            $image = wp_get_attachment_image( $image_id, 'medium' );
                                            if ( empty( $image ) ) {
                                                continue;
                                            } else {
                                                echo $image;
                                            }
                                        }
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<span>'. $group_attributes_1[$i]['attribute'][$k]['value'] .'</span>';
                                }
                            ?>
                        </div>
                        <div class="column column-2">
                            <div class="sub-name"> </div>
                            <?php
                                if ($group_attributes_2[$i]['attribute'][$k]['type'] == 'Image') {
                                    $image = wp_get_attachment_image( $group_attributes_2[$i]['attribute'][$k]['value'], 'medium' );
                                    echo $image;
                                } elseif ($group_attributes_2[$i]['attribute'][$k]['type'] == 'Slider') {
                                    $images = $group_attributes_2[$i]['attribute'][$k]['value'];
                                    $images_ = null;
                                    if ($images != null) {
                                        $images_ = explode(',', $images);
                                    }
                                    if ( ! empty( $images_ ) ) {
                                        echo '<div class="slider-images">';
                                        foreach ( $images_ as $image_id ) {
                                            $image = wp_get_attachment_image( $image_id, 'medium' );
                                            if ( empty( $image ) ) {
                                                continue;
                                            } else {
                                                echo $image;
                                            }
                                        }
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<span>'. $group_attributes_2[$i]['attribute'][$k]['value'] .'</span>';
                                }
                            ?>
                        </div>
                        <div class="column column-3"></div>
                    </div>
                <?php } ?>
            <?php }
        ?>

        <div class="header">
            <p><?php echo __("Mua sản phẩm", 'compare-product') ?></p>
        </div>
        <div class="rows">
            <div class="column column-1">
            </div>
            <div class="column column-2">
                <?php if ( $product_1->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_1->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <div class="group-btns">
                                <button <?php if ( $product_1->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_1->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="column column-2">
                <?php if ( $product_2->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_2->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <div class="group-btns">
                                <button <?php if ( $product_2->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_2->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="column column-3"></div>
        </div>
    </div>

    <div class="modal fade modal-delete-address" id="modalAddCompareProduct" tabindex="-1" role="dialog" aria-labelledby="modalAddCompareProduct" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                   
                    <h3 id="compare-title" class="text-center d-block"><?php echo __('Chọn sản phẩm để thêm vào so sánh', 'compare-product'); ?></h3>
                    <i class="far fa-times-circle"></i>
                    <p class="error">
                        <span id="compare-error"></span>
                    </p>
                    <div class="compare-contents">
                        <div id="compare-search-lst">
                            <input type="text" id="search-compare-product" placeholder="<?php echo __('Nhập tên sản phẩm để tìm kiếm', 'compare-product') ?>"/>
                            <div id="input-search-loading" ></div>
                        </div>
                        <div id="compare-lst-products"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

    get_footer();
    echo '<script> document.title = "' .$custom_title. '"; 
        const product_type_id = "' .$product_type_id. '";  
        const product_id = "' .$product_1->get_id(). '";  
        var product_id_exclude = "' .$product_2->get_id(). '";  
        const compare_product_ajax = "' .admin_url('admin-ajax.php'). '"; 
        </script>';
} else {
    header("Location:" . home_url());
    exit;
}

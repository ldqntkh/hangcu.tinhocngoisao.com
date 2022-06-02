<?php 
// load product compare exclude ids
add_action('wp_ajax_loadproductcompareexcludeids', 'load_product_compare_exclude_ids' );
add_action('wp_ajax_nopriv_loadproductcompareexcludeids', 'load_product_compare_exclude_ids' );
function load_product_compare_exclude_ids() {
    $search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : -1;
    $exclude_product_ids = isset($_POST['exclude_product_ids']) ? $_POST['exclude_product_ids'] : '';
    if ( $exclude_product_ids !== '' ) {
        $exclude_product_ids = explode( ',', $exclude_product_ids );
    }
    $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : -1;
    
    if ($product_id == -1 || $product_type_id == -1) wp_send_json_error();
    
    $product = wc_get_product($product_id);
    if ($product) {
        require_once COMPARE_PRODUCT_PLUGIN_DIR . '/product_compare/api/productType.php';
        $productIds = ProductTypeApi::getListProductsMappingByProductIdAndProductType($product_id, $product_type_id, $search_name);
        if ($productIds && count($productIds) > 0) {
            $results = array();
            foreach ($productIds as $key=>$item) {
                if ( in_array ( $item, $exclude_product_ids ) ) continue;
                $product_ = wc_get_product($item);
                if ($product_) {
                    $prdc = array(
                        "name" => $product_->get_name(),
                        "price" => $product_->get_price_html(),
                        "id" => $item,
                        "image" => wp_get_attachment_image_src( get_post_thumbnail_id( $product_->get_id() ), 'full', true )[0],
                        "slug" => $product_->get_slug()
                    );
                    array_push($results, $prdc);
                }
                if ( count( $results ) >= 6 ) break;
            }
            wp_send_json_success($results);
            die;
        }
        else wp_send_json_error();
    } else wp_send_json_error();
    
    die();
}


add_action('wp_ajax_loadproductcomparetemplate', 'load_product_compare_template' );
add_action('wp_ajax_nopriv_loadproductcomparetemplate', 'load_product_compare_template' );
function load_product_compare_template() {
    $ids_str = $_POST['product_id_1'];
    $classTag = 'column column-2';


    if ( isset( $_POST['product_id_1'] )) {
        $product_id_1 = $_POST['product_id_1'];
        $product_id_2 = $_POST['product_id_2'];
        $product_id_3 = $_POST['product_id_3'];


        if (isset($_POST['product_id_3'])) {
            $classTag = 'column column-2-2';
        }
    
        $_pf = new WC_Product_Factory();  
        $product_1 = $_pf->get_product( $product_id_1 );

        if ($product_id_2) {
            $ids_str .= ',' . $product_id_2;

            $product_2 = $_pf->get_product( $product_id_2 );
        }

        if ($product_id_3) {
            $ids_str .= ',' . $product_id_3;
            $product_3 = $_pf->get_product( $product_id_3 );
        }

        include_once WP_PLUGIN_DIR. '/compare-products/product_compare/api/productType.php';
        include_once WP_PLUGIN_DIR. '/compare-products/product_compare/api/groupAttributes.php';
        // check variant product and product type
        $group_id_1 = ProductTypeApi::getGroupProductMappingByProductId($product_1->get_id());
        if (isset($product_2)) {
            $group_id_2 = ProductTypeApi::getGroupProductMappingByProductId($product_2->get_id());
        } else {
            $productIds = ProductTypeApi::getListProductsMappingByProductIdAndProductType($product_1->get_id(), $group_id_1);

            $product_default = [];

            $max_compare = 0;
            foreach ($productIds as $key=>$item) {
                $product_default[] = wc_get_product($item);

                if (++$max_compare == 3 ) break;
            }
        }

        if (isset($product_3)) {
            $group_id_3 = ProductTypeApi::getGroupProductMappingByProductId($product_3->get_id());
        }

        // if ($group_id_1 != $group_id_2 || $group_id_1 != $group_id_3 || $group_id_2 != $group_id_3 ) {
        //     wp_send_json_error(array("errMsg" => "Product data not valid!"));
        //     die;
        // }
    
        // $custom_title = __('So sánh chi tiết ', 'compare-product') . $product_1->get_name() . ' & ' . $product_2->get_name() . __('|TinHocNgoiSao', 'compare-product');
    
        $group_attributes_1 = GroupAttributesApi::getGroupAttributes($group_id_1, $product_1->get_id(), 'json');

        if (isset($product_2)) {
            $group_attributes_2 = GroupAttributesApi::getGroupAttributes($group_id_2, $product_2->get_id(), 'json');
        }
        
        if (isset($product_3)) {
            $group_attributes_3 = GroupAttributesApi::getGroupAttributes($group_id_3, $product_3->get_id(), 'json');
        }
        
        // $product_type_id = ProductTypeApi::getGroupProductMappingByProductId($product_1->get_id());

        ob_start();
    ?>
        <?php if (isset($product_2)) : ?>
        <script type="text/javascript">
            product_id_exclude = "<?php echo $product_2->get_id() ?>";
        </script>
        <?php endif; ?>
        <input class="d-none list-id-compare" type="hidden" value="<?php echo $ids_str; ?>"/>
        <div class="header">
            <p>
                <?= __('So sánh chi tiết sản phẩm', 'compare-product') ?>
            </p>
        </div>
        <div class="rows">
            <div class="column column-1"></div>
            <div class="<?php echo $classTag; ?>">
                <div class="product-data">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_1->get_id() ), 'full', true )[0] ?>" alt="" />
                </div>
            </div>
            <div class="<?php echo $classTag; ?>">
                <?php if (isset($product_2)) : ?>
                <div class="product-data">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_2->get_id() ), 'full', true )[0] ?>" alt="" />
                </div>
                <button class='remove-compare' data-id="<?php echo $product_2->get_id(); ?>">-</button>
                <?php elseif (isset($product_default)):
                    echo '<div class="product-compare-default-wrapper">';
                    echo '<div class="product-compare-default">';
                    echo '<h4>' . __('Chọn sản phẩm để so sánh', 'compare-product') . '</h4>';
                    echo '<ul class="list-product">';
                    foreach($product_default as $key => $product) {
                        echo '<li data-slug="' . $product->get_slug() . '">';
                        echo '<div class="image-wrapper">';
                        echo '<img src="'.wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full', true )[0].'"/>';
                        echo '</div>';
                        echo '<div class="name">';
                        echo $product->get_name();
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '<div><a class="view-more-product-compare">'.__('Xem nhiều hơn để so sánh', 'compare-product').'</a></div>';
                    echo '</div>';
                    echo '</div>';
                ?>
                <?php endif; ?>
            </div>
            <div class="<?php echo isset($product_3) ? $classTag : 'column column-3'; ?>">
                <?php if (isset($product_3)) : ?>
                <div class="product-data">
                    <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_3->get_id() ), 'full', true )[0] ?>" alt="" />
                </div>
                <button class='remove-compare' data-id="<?php echo $product_3->get_id(); ?>">-</button>
                <?php elseif (isset($product_2)) : ?>
                    <button id='add-compare-product'>+</button>
                <?php endif; ?>
            </div>
        </div>
        <div class="header">
            <p><?php echo __("Thông tin chung", 'compare-product') ?></p>
        </div>
        <div class="rows">
            <div class="column column-1">
                <?php echo __("Tên sản phẩm", 'compare-product') ?>
            </div>
            <div class="<?php echo $classTag; ?>">
                <a target="_blank" class="product-compare-name" href="<?php echo get_permalink( $product_1->get_id()) ?>"><?php echo $product_1->get_name() ?></a>
            </div>
            <div class="<?php echo $classTag; ?>">
                <?php if (isset($product_2)) : ?>
                    <a target="_blank" class="product-compare-name" href="<?php echo get_permalink( $product_2->get_id()) ?>"><?php echo $product_2->get_name() ?></a>
                <?php endif; ?>
            </div>
            <div class="<?php echo isset($product_3) ? $classTag : "column column-3";?>">
                <?php if (isset($product_3)) : ?>
                    <a target="_blank" class="product-compare-name" href="<?php echo get_permalink( $product_3->get_id()) ?>"><?php echo $product_3->get_name() ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="rows">
            <div class="column column-1">
                <?php echo __("Giá bán", 'compare-product') ?>
            </div>
            <div class="<?php echo $classTag; ?>">
                <span><?php echo wc_price($product_1->get_price()) ?></span>
            </div>
            <div class="<?php echo $classTag; ?>">
                <?php if (isset($product_2)) : ?>
                    <span><?php echo wc_price($product_2->get_price()) ?></span>
                <?php endif; ?>
            </div>
            <div class="<?php echo isset($product_3) ? $classTag : "column column-3";?>">
                <?php if (isset($product_3)) : ?>
                    <span><?php echo wc_price($product_3->get_price()) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="rows">
            <div class="column column-1">
            </div>
            <div class="<?php echo $classTag; ?>">
                <?php if ( $product_1->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_1->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_1->get_min_purchase_quantity(), $product_1 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_1->get_max_purchase_quantity(), $product_1 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_1->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_1->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_1->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="<?php echo $classTag; ?>">
                <?php if ( isset($product_2) && $product_2->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_2->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_2->get_min_purchase_quantity(), $product_2 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_2->get_max_purchase_quantity(), $product_2 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_2->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_2->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_2->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="<?php echo isset($product_3) ? $classTag : "column column-3";?>">
                <?php if ( isset($product_3) && $product_3->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_3->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_3->get_min_purchase_quantity(), $product_3 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_3->get_max_purchase_quantity(), $product_3 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_3->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_3->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_3->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
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
                        <div class="<?php echo $classTag; ?>">
                            <div class="sub-name">
                                <strong><?php echo $group_attributes_1[$i]['attribute'][$k]['name'] ?></strong>
                            </div>
                            <?php 
                                if ($group_attributes_1[$i]['attribute'][$k]['type'] == 'Image') {
                                    $image = wp_get_attachment_image( $group_attributes_1[$i]['attribute'][$k]['value'], 'full' );
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
                                            $image = wp_get_attachment_image( $image_id, 'full' );
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
                        <div class="<?php echo $classTag; ?>">
                            <div class="sub-name"> </div>
                            <?php
                                if (isset($group_attributes_2)) {
                                    if ($group_attributes_2[$i]['attribute'][$k]['type'] == 'Image') {
                                        $image = wp_get_attachment_image( $group_attributes_2[$i]['attribute'][$k]['value'], 'full' );
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
                                                $image = wp_get_attachment_image( $image_id, 'full' );
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
                                }
                            ?>
                        </div>
                        <div class="<?php echo isset($product_3) ? $classTag : "column column-3";?>">
                            <div class="sub-name"> </div>
                            <?php
                                if (isset($group_attributes_3)) {
                                    if ($group_attributes_3[$i]['attribute'][$k]['type'] == 'Image') {
                                        $image = wp_get_attachment_image( $group_attributes_3[$i]['attribute'][$k]['value'], 'full' );
                                        echo $image;
                                    } elseif ($group_attributes_3[$i]['attribute'][$k]['type'] == 'Slider') {
                                        $images = $group_attributes_3[$i]['attribute'][$k]['value'];
                                        $images_ = null;
                                        if ($images != null) {
                                            $images_ = explode(',', $images);
                                        }
                                        if ( ! empty( $images_ ) ) {
                                            echo '<div class="slider-images">';
                                            foreach ( $images_ as $image_id ) {
                                                $image = wp_get_attachment_image( $image_id, 'full' );
                                                if ( empty( $image ) ) {
                                                    continue;
                                                } else {
                                                    echo $image;
                                                }
                                            }
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<span>'. $group_attributes_3[$i]['attribute'][$k]['value'] .'</span>';
                                    }
                                }
                            ?>
                        </div>
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
            <div class="<?php echo $classTag; ?>">
                <?php if ( $product_1->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_1->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_1->get_min_purchase_quantity(), $product_1 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_1->get_max_purchase_quantity(), $product_1 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_1->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_1->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_1->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="<?php echo $classTag; ?>">
                <?php if ( isset($product_2) && $product_2->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_2->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_2->get_min_purchase_quantity(), $product_2 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_2->get_max_purchase_quantity(), $product_2 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_2->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_2->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_2->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="<?php echo isset($product_3) ? $classTag : "column column-3";?>">
                <?php if ( isset($product_3) && $product_3->is_in_stock() ) : ?>
                    <div class="bottom_order ">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_3->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <?php
                            // woocommerce_quantity_input( array(
                            //     'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product_3->get_min_purchase_quantity(), $product_3 ),
                            //     'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product_3->get_max_purchase_quantity(), $product_3 ),
                            //     'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product_3->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            // ) );
                            ?>
                            <div class="group-btns">
                                <button <?php if ( $product_3->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_3->get_id() ); ?>" class="single_add_to_cart_button button alt">
                                    <b>Mua ngay</b>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php
        echo wp_send_json_success(ob_get_clean());
        die;
    } else {
        wp_send_json_error(array("errMsg" => "Product data not valid!"));
        die;
    }
}
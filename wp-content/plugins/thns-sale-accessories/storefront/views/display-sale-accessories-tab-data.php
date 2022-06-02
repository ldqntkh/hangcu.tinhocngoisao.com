<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$stop_selling = get_field('stop_selling', $product->get_id());

if ( !$stop_selling ) {

    $campaign_class = new GEARVNSaleAccessoriesCampaign();
    $_se_group_values = $campaign_class->getInfoCampaignByProductId( $product->get_id() );
    // $_se_group_values = get_post_meta($product->get_id() , '_se_group_values', true);

    if ( !empty($_se_group_values) ) {
        // $_se_group_values = json_decode(base64_decode( $_se_group_values ), true);
        
        if ( sizeof( $_se_group_values ) !== 0  && $product->is_type( array( 'simple', 'variable' ) ) ) {

            include_once THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/helpers/class-get-data-product-accessories.php';

            $productSaleAccessories = GetDataProductAccessories::getProductSaleAccessories( $_se_group_values );

            if ( $productSaleAccessories && count(  $productSaleAccessories ) > 0 ) : 
                $total_price = $product->get_price();
                $total_discount = 0;
                $product_count = 1;

                // array witrh key is product id and value is quantity
                $productSelected = [];
            ?>

            <div class="accessories">
                <div class="electro-wc-message">
                    <p><?php echo __("Ưu đãi khi mua cùng ", THNS_SALE_ACCESSORIES_PLUGIN) ?><strong><?php echo $product->get_name() ?></strong></p>
                    <?php 
                        $success_msg = sprintf( '<div class="woocommerce-message">%s <a class="button wc-forward" href="%s">%s</a></div>', esc_html__( 'Sản phẩm đã được thêm vào giỏ hàng.', THNS_SALE_ACCESSORIES_PLUGIN ), wc_get_cart_url(), esc_html__( 'Xem giỏ hàng', THNS_SALE_ACCESSORIES_PLUGIN ) );
                        $error_msg = sprintf( '<ul class="woocommerce-error" role="alert"><li>%s</li></ul>', esc_html__( 'Không thể thêm được sản phẩm. Vui lòng thử lại.', THNS_SALE_ACCESSORIES_PLUGIN ) );

                        if ( !empty($_SESSION['add_all_to_cart']) ) {
                            if ( $_SESSION['add_all_to_cart'] ) {
                                echo $success_msg;
                            } else {
                                echo $error_msg;
                            }
                            unset( $_SESSION['add_all_to_cart'] );
                        }
                    ?>
                </div>
                <div class="gearvn-wc-message"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-9 col-left">
                                <ul id='sale-accessories-lst-product' data-view="grid" data-toggle="regular-products" class="products columns-3 columns__wide--4">
                                    <li <?php wc_product_class( '', $product ); ?>>
                                        <div class="product-outer product-item__outer custom-product-item-sale">
                                            <div class="product-inner product-item__inner">
                                                <?php //GetDataProductAccessories::gearvn_template_loop_categories( $product->get_id() ) ?>
                                                <aside  class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <?php 
                                                        $productSelected[] = [
                                                            'product_id'=> $product->get_id(),
                                                            'quantity'  => 1,
                                                            'price'     => $product->get_price(),
                                                            'price_discount'    => 0
                                                        ];

                                                        $images = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
                                                        if ( $images && count( $images ) > 0 ) {
                                                            echo '<div class="product-thumbnail product-item__thumbnail">';
                                                            echo '<img src="'.$images[0].'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" />';
                                                            echo '</div>';
                                                        }
                                                        //echo electro_template_loop_product_thumbnail(); 
                                                    ?>
                                                    <div class="product-details">
                                                        <?php
                                                            echo '<div class="title-product-check">';
                                                            
                                                            echo '<a target="_blank" href="' . get_permalink($product->get_id()) . '">';
                                                            echo '<h2 class="woocommerce-loop-product__title">' . $product->get_name() . '</h2>';
                                                            echo '</a>';

                                                            echo '</div>';
                                                        ?>

                                                        <div class="product-loop-footer product-item__footer">
                                                            <div class="price-add-to-cart">
                                                                <?php echo $product->get_price_html() ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </aside>
                                            </div>
                                        </div>
                                    </li>

                                    <?php foreach( $productSaleAccessories as $product_item ) : 
                                        if ( count($product_item['products']) <= 0 ) continue;  
                                        $total_price += $product_item['products'][0]['product_price'];
                                        $total_discount += $product_item['products'][0]['product_price_discount'];
                                        $product_count++;
                                        $productSelected[] = [
                                            'product_id'=> $product_item['products'][0]['product_id'],
                                            'quantity'  => 1,
                                            'price'     => $product_item['products'][0]['product_price'],
                                            'price_discount'    => $product_item['products'][0]['product_price_discount']
                                        ];
                                    ?>
                                        <li <?php wc_product_class( '', wc_get_product( $product_item['products'][0]['product_id'] ) ); ?>>
                                            <div class="product-outer product-item__outer custom-product-item-sale">
                                                <div class="product-inner product-item__inner">
                                                    <?php //GetDataProductAccessories::gearvn_template_loop_categories( $product_item['products'][0]['product_id'] ) ?>
                                                    <aside  class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                        <?php 
                                                            echo '<div class="product-thumbnail product-item__thumbnail">';
                                                            echo '<img src="'. $product_item['products'][0]['image'] .'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" />';
                                                            echo '</div>';
                                                        ?>
                                                        <div class="product-details">
                                                            <?php
                                                                echo '<div class="checkbox accessory-checkbox">
                                                                    <label>
                                                                        <input checked type="checkbox" class="product-check"  />
                                                                        <span>Chọn mua</span>
                                                                    </label></div>';
                                                                echo '<div class="title-product-check">';
                                                                
                                                                echo '<a target="_blank" href="' . $product_item['products'][0]['product_link']  . '">';
                                                                echo '<h2 class="woocommerce-loop-product__title">' . $product_item['products'][0]['product_name'] . '</h2>';
                                                                echo '</a>';

                                                                echo '</div>';
                                                            ?>

                                                            <div class="product-loop-footer product-item__footer">
                                                                <div class="price-add-to-cart">
                                                                    <span class="accessories-price">
                                                                        <ins>
                                                                            <span class="woocommerce-Price-amount amount"><?php echo $product_item['products'][0]['product_price_display'] ?></span>
                                                                        </ins>
                                                                        <span style="display: flex; justify-content: flex-start; align-items: center;">
                                                                            <del>
                                                                                <span class="woocommerce-Price-amount amount"><?php echo $product_item['products'][0]['product_sale_price_display'] ?></span>
                                                                            </del>
                                                                            <span style="font-size: 12px;">&nbsp;(- <?php echo $product_item['products'][0]['product_percent_discount'] ?>%)</span>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                                <a class="choose-sale-product" data-name="<?php echo $product_item['name'] ?>" style="height: 30px;display: block;" href="#"><?php echo __( sprintf( 'Chọn %s khác', strtolower( $product_item['name'] ) ) ) ?></a>
                                                            </div>
                                                        </div>
                                                    </aside>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div id="total-add-all-to-cart" class="col-xs-12 col-sm-3 col-right">
                                <?php if( $total_price > 0 ) : ?>
                                    <h6><?php echo __("Tổng tiền", THNS_SALE_ACCESSORIES_PLUGIN) ?></h6>
                                    <div class="total-price">
                                        <?php
                                            $total_price_html = '<span class="total-price-html">' . wc_price( $total_price ) . '</span>';
                                            $total_products_html = '<span class="total-products">' . $product_count . '</span>';
                                            $total_price = sprintf( __( '%s cho %s sản phẩm', THNS_SALE_ACCESSORIES_PLUGIN ), $total_price_html, $total_products_html );
                                            echo wp_kses_post( $total_price );
                                        ?>
                                    </div>
                                    
                                    <div class="accessories-add-all-to-cart">
                                        <button type="button" class="single_add_to_cart_button button btn btn-primary add-all-to-cart">
                                            <?php echo __( sprintf( 'Mua %s sản phẩm',  $product_count ), THNS_SALE_ACCESSORIES_PLUGIN ); ?>
                                            <br/>
                                            <span><?php echo __( sprintf( 'Tiết kiệm %s', wc_price($total_discount) ), THNS_SALE_ACCESSORIES_PLUGIN ); ?></span>
                                        </button>
                                    </div>
                                    
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade " id="modalShowAccessories" tabindex="-1" role="dialog" aria-labelledby="modalShowAccessories" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <span id="close-modal" class="icon-delete"></span>
                            <h3 id="accessories-title" class="text-center d-block"></h3>
                            <div class="accessories-contents">
                                <div id="accessories-lst-products"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                const alert_message_success = '<?php echo $success_msg; ?>';
                const alert_message_error = '<?php echo $error_msg; ?>';
                const productSaleAccessories = <?php echo json_encode( $productSaleAccessories ) ?>;
                const productSelected = <?php echo json_encode( $productSelected ) ?>;
                const gearvn_accessries_ajax = '<?php echo admin_url( 'admin-ajax.php') ?>';
            </script>

            <?php 
            endif;
        }
    }
}
?>

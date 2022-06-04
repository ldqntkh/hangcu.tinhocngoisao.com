<h3 class="cart-title"><?= __("Giỏ hàng ", 'hangcu') ?><span>(<?= WC()->cart->get_cart_contents_count() . ' ' . __('sản phẩm', 'hangcu') ?> )</span></h3>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<div class="cart-items">
        
        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            $check_stock = true;
            
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                <div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                    <div class="product-thumbnail">
                    <?php
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                    if ( ! $product_permalink ) {
                        echo $thumbnail; // PHPCS: XSS ok.
                    } else {
                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                    }
                    ?>
                    </div>

                    <div class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                    <?php
                        if ( ! $product_permalink ) {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', esc_html( $_product->get_name() ), $cart_item, $cart_item_key ) . '&nbsp;' );
                        } else {
                            // echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $_product->get_name() ) ), $cart_item, $cart_item_key ) );
                        }
                        echo apply_filters('hc_sale_accessories', $_product->get_name(), $cart_item, $cart_item_key);
                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                        // Meta data.
                        echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                        // Backorder notification.
                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                        }
                    ?>
                        <span class="remove-product">
                            <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">Xóa</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
                            ?>
                        </span>
                    </div>

                    <div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                        <?php
                            // echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            $pr = wc_get_product($product_id);
                            $price = $pr->get_price();
                            $regular_price = $pr->get_regular_price();
                            
                            if( $regular_price > $price ) : 
                                $percent = 100 - ($price/$regular_price * 100);
                                $percent = round($percent);
                            ?>
                                <div class="price">
                                    <p>
                                        <bdi><?= wc_price($price) ?></bdi>
                                    </p>
                                    <p>
                                        <del><?= wc_price($regular_price) ?></del>
                                        <span>-<?= $percent ?>%</span>
                                    </p>
                                </div>
                                
                            <?php else :
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            endif;
                        ?>
                    </div>

                    <div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                    <?php
                    if( $check_stock ) {
                        if ( $_product->is_sold_individually() ) {
                            $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                        } else {
                            $product_quantity = woocommerce_quantity_input(
                                array(
                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                    'input_value'  => $cart_item['quantity'],
                                    'max_value'    => $_product->get_max_purchase_quantity(),
                                    'min_value'    => '0',
                                    'product_name' => $_product->get_name(),
                                ),
                                $_product,
                                false
                            );
                        }
    
                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                    }
                    
                    ?>
                    </div>

                    <!-- <div class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                        ?>
                    </div> -->
                </div>
                <?php
            }
        }
        ?>

        <?php do_action( 'woocommerce_cart_contents' ); ?>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</div>
	<div class="cart-info">
        <?php
            // hiển thị địa chỉ giao hàng mặc định
            $currentUser = wp_get_current_user();
            if( $currentUser->ID !== 0 ) :
                $otherAddr = [];
                $otherAddr = get_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', true );
                
                $address_key_selected = WC()->session->get('address_key_selected');
                if( empty($address_key_selected) ) {
                    foreach ($otherAddr as $key => $value) {
                        if( $otherAddr[$key]['address_is_default'] == 'on' ) {
                            $address_key_selected = $key;
                            WC()->session->set('address_key_selected', $key);
                            break;
                        }
                    }
                }
                global $addressSelected;
                $addressSelected = $otherAddr[$address_key_selected];

                if( $addressSelected ) : 
                ?>
                    <div class="order-address">
                        <div class="head">
                            <span><?php echo __('Địa chỉ nhận hàng', 'hangcu'); ?></span>
                            <a class="edit-order" href="<?php echo add_query_arg( 'step', 'shipping', wc_get_checkout_url() ); ?>"><?= __('Thay đổi', 'hangcu') ?></a>
                        </div>
                        <div class="address-content">
                            <div class="info">
                                <p>
                                    <strong><?php echo esc_html($addressSelected['billing_last_name']) ?></strong>
                                    <strong><?php echo esc_html($addressSelected['billing_phone']) ?></strong>
                                </p>
                                <p><span><?php echo isset($addressSelected['full_address']) ? esc_html($addressSelected['full_address']) : ''; ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php endif;
            endif;
        ?>

		<?php if ( wc_coupons_enabled() ) { ?>
			<div class="coupon">
				<label for="coupon_code"><?php esc_html_e( 'Mã khuyến mãi', 'hangcu' ); ?></label> 
                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Mã khuyến mãi', 'hangcu' ); ?>" /> 
                <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Áp dụng', 'hangcu' ); ?>">
                    <?php esc_attr_e( 'Áp dụng', 'hangcu' ); ?>
                </button>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		<?php } ?>
        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
		<div class="cart-collaterals">
			<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>
		<div class="cart-btns">
			<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		</div>
	</div>
</form>

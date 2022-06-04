
<?php 
    $currentUser = wp_get_current_user();
    $otherAddr = [];
    
    if ($currentUser->ID !== 0) {
        $otherAddr = get_user_meta( $currentUser->ID, 'hangcu_multiple_shipping_addresses', true );
    } else {
        WC()->session->set( 'checkoutstep', 2 );
        header('Location: '.$_SERVER['REQUEST_URI']);
        die;
    }
   

    $addressSelected = null;
    $placeOrderText = __( 'Place order', 'woocommerce' );

    $address_key_selected = WC()->session->get('address_key_selected');
    $addressSelected = $otherAddr[$address_key_selected];
    

    if (!$addressSelected || empty($addressSelected)) {
        WC()->session->set( 'checkoutstep', 2 );
        WC()->session->__unset('address_key_selected');
        header('Location: '.$_SERVER['REQUEST_URI']);
        die;
    }
    
    get_header('checkout');
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    if ($chosen_methods && count($chosen_methods) > 0) {
        $chosen_method = reset($chosen_methods);
    } else $chosen_method = null;

    WC()->session->set('billing_state', $addressSelected['billing_state']);
    WC()->session->set('billing_address_2', $addressSelected['billing_address_2']);

    WC()->customer->set_billing_first_name(wc_clean( '' )); 
    WC()->customer->set_billing_last_name(wc_clean( $addressSelected['billing_last_name'] )); 
    // WC()->customer->set_billing_company(wc_clean( $billing_company ));  
    WC()->customer->set_billing_address_1(wc_clean( $addressSelected['billing_address_1'] )); 
    WC()->customer->set_billing_address_2(wc_clean( $addressSelected['billing_address_2'] )); 
    WC()->customer->set_billing_city(wc_clean( $addressSelected['billing_city'] )); 
    WC()->customer->set_billing_state(wc_clean( $addressSelected['billing_state'] )); 
    // WC()->customer->set_billing_postcode(wc_clean( $addressSelected['billing_address_1'] )); 
    // WC()->customer->set_billing_country(wc_clean( $addressSelected['billing_address_1'] ));

    // WC()->customer->set_shipping_first_name(wc_clean( $shipping_first_name )); 
    WC()->customer->set_shipping_last_name(wc_clean( $addressSelected['billing_last_name'] )); 
    // WC()->customer->set_shipping_company(wc_clean( $shipping_company ));    
    WC()->customer->set_shipping_address_1(wc_clean( $addressSelected['billing_address_1'] )); 
    WC()->customer->set_shipping_address_2(wc_clean( $addressSelected['billing_address_2'] )); 
    WC()->customer->set_shipping_city(wc_clean( $addressSelected['billing_city'] )); 
    WC()->customer->set_shipping_state(wc_clean( $addressSelected['billing_state'] )); 
    // WC()->customer->set_shipping_postcode(wc_clean( $shipping_postcode )); 
    // WC()->customer->set_shipping_country(wc_clean( $shipping_country )); 
    
    do_action( 'hangcu_checkout_update_order_review', $addressSelected );
    WC()->cart->calculate_shipping();
    WC()->cart->calculate_totals();
?>


<div class="checkout-payment">
    <h2><?php echo __('Chọn hình thức thanh toán', 'hangcu') ?></h2>
    <div class="payment-details">
        <?php 
            if ($currentUser->ID === 0) {
                echo '<p>Bạn đã có tài khoản? <a href="' .get_permalink( get_page_by_path( 'checkout' ) ).'?type=login'. '">Đăng nhập</a> hoặc <a href="' .get_permalink( get_page_by_path( 'checkout' ) ).'?type=register'. '">Đăng ký</a></p>';
            }
        ?>
        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
            <input type="hidden" name="billing_last_name" value="<?php echo $addressSelected['billing_last_name']; ?>" />
            <input type="hidden" name="billing_country" value="VN" />
            <input type="hidden" name="billing_address_1" value="<?php echo $addressSelected['billing_address_1']; ?>" />
            <input type="hidden" name="billing_address_2" value="<?php echo $addressSelected['billing_address_2']; ?>" />
            <input type="hidden" name="billing_city" value="<?php echo $addressSelected['billing_city']; ?>" />
            <input type="hidden" name="billing_state" value="<?php echo $addressSelected['billing_state']; ?>" />
            <input type="hidden" name="billing_phone" value="<?php echo $addressSelected['billing_phone']; ?>" />
            
            <?php if ($currentUser->ID === 0) : ?>
                <input type="hidden" name="billing_email" value="<?php echo $addressSelected['billing_email']; ?>" />
            <?php else :?>
                <input type="hidden" name="billing_email" value="<?php echo $currentUser->user_email; ?>"/>
            <?php endif; ?>
            
            <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

            <div class="payment">
                <div id="payment" class="woocommerce-checkout-payment">
                    <?php if ( WC()->cart->needs_payment() ) : ?>
                        <ul class="wc_payment_methods payment_methods methods">
                            <?php
                            if ( ! empty( $available_gateways ) ) {
                                foreach ( $available_gateways as $gateway ) {
                                    wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                                }
                            }
                            ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <div class="order-details">
        <div class="order-address">
            <div class="head">
                <span><?php echo __('Giao hàng đến địa chỉ', 'hangcu'); ?></span>
                <a class="edit-order" href="<?php echo add_query_arg( 'step', 'shipping', get_permalink('checkout') ); ?>">Sửa</a>
            </div>
            <div class="address-content">
                <div class="info">
                    <p><strong><?php echo __('Họ tên:' , 'hangcu') ?></strong> <span><?php echo esc_html($addressSelected['billing_last_name']); ?></span></p>
                    <p><strong><?php echo __('Số điện thoại:' , 'hangcu') ?></strong> <span><?php echo esc_html($addressSelected['billing_phone']); ?></span></p>
                    <p><strong><?php echo __('Địa chỉ:' , 'hangcu') ?></strong> <span><?php echo isset($addressSelected['full_address']) ? esc_html($addressSelected['full_address']) : ''; ?></span></p>
                </div>
            </div>
        </div>
        <div class="order-items">
            <div class="head">
                <span><?php echo __('Giỏ hàng', 'hangcu'); ?></span>
                <a  class="edit-order" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo __('Sửa', 'hangcu'); ?></a>
            </div>
            <div class="items">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
                <?php
                    $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                ?>
                <div class="product-item">
                    <span class="d-block product-name">
                        <strong><?php echo $cart_item['quantity']; ?> x </strong>
                        <a href="<?php echo get_permalink( $_product->get_id() ); ?>" title="<?php echo $_product->get_name(); ?>"><?php echo $_product->get_name(); ?></a>
                    </span>
                    <!-- <span><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></span> -->
                    <?php
                        //echo $_product->get_price_html();
                        echo '<span class="electro-price"><span class="woocommerce-Price-amount amount">' . wc_price( wc_get_product($_product->get_id())->get_price() ) . '</span></span>';
                        do_action( 'woocommerce_render_sale_accessories', $_product->get_id() );
                    ?>
                </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="foot">
                <div class="order-totals">
                    <div class="line">
                        <span>Tạm tính:</span>
                        <strong><?php wc_cart_totals_subtotal_html(); ?></strong>
                    </div>
                    <div class="line total">
                        <span>Tổng cộng:</span>
                        <strong><?php wc_cart_totals_order_total_html(); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

function hc_header_mini_cart_icon() {
    if( true === electro_get_shop_catalog_mode() ) {
        return;
    }
    $disable_header_cart_dropdown = true;

    $cart_link = '';

    if( apply_filters( 'electro_off_canvas_cart', true ) ) {
        $cart_link = '#off-canvas-cart-summary';
    } else {
        $cart_link = wc_get_cart_url();
    }

    ?><div class="header-icon header-icon__cart <?php if ( ! $disable_header_cart_dropdown ): ?>animate-dropdown dropdown<?php endif; ?>">
        <a href="<?php echo esc_url( $cart_link ); ?>" <?php if ( ! $disable_header_cart_dropdown ): ?>data-toggle="dropdown"<?php endif; ?>>
            <i class="ec ec-shopping-bag"></i>
            <span class="cart-title">
                <?php 
                    if( !electro_detect_is_mobile() ) echo __("Giỏ hàng", 'hangcu');
                ?>
                <?php echo WC()->cart->get_cart_contents_count(); ?> 
            </span>
        </a>
        
        
        <?php if ( ! $disable_header_cart_dropdown && false ) {
            if ( is_wc_gateway_ppec() ) {
                if ( is_cart() == false && is_checkout() == false ) {
                    ?>
                    <ul class="dropdown-menu dropdown-menu-mini-cart">
                        <li>
                            <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                        </li>
                    </ul>
                <?php }
                } else { ?>
                <ul class="dropdown-menu dropdown-menu-mini-cart">
                    <li>
                        <div class="widget_shopping_cart_content">
                          <?php woocommerce_mini_cart();?>
                        </div>
                    </li>
                </ul><?php
            }
        } ?>
    </div><?php
}
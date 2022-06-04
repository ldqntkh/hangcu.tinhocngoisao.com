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
            <span class="cart-items-count count header-icon-counter hangcu_loading">
                <!-- <?php //echo WC()->cart->get_cart_contents_count(); ?> -->
            </span>
            <span class="cart-title"><?= __("Giỏ hàng") ?></span>
        </a>
        <?php 
            if( wp_is_mobile() && !is_product() ) : ?>
                <aside id="tooltip-minicart" class="tooltip-minicart-pc">
                    <span class="electro-close-icon"></span>
                    <p>
                        <span class="electro-checked-icon"></span>
                        Thêm vào giỏ hàng thành công!
                    </p>
                    <a href="<?php echo esc_url( $cart_link ); ?>">Xem giỏ hàng và thanh toán</a>
                </aside>
            <?php else : ?>
                <aside id="tooltip-minicart" class="tooltip-minicart-pc">
                    <span class="electro-close-icon"></span>
                    <p>
                        <span class="electro-checked-icon"></span>
                        Thêm vào giỏ hàng thành công!
                    </p>
                    <a href="<?php echo esc_url( $cart_link ); ?>">Xem giỏ hàng và thanh toán</a>
                </aside>
            <?php endif;
        
        ?>
        
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
<?php
if( !function_exists('hc_checkout_step') ) {
    function hc_checkout_step() {  
        $checkout_step = WC()->session->get('checkoutstep');
    ?>
        <div class="checkout-step">
            <div class="progress-checkout">
                <div class="row bs-wizard">
                    <div class="bs-wizard-step bs-wizard-step-1 <?php if ($checkout_step == 1) echo 'active'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span>Đăng nhập</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-right"></div>
                            <span class="bs-wizard-dot">1</span>
                        </div>
                    </div>

                    <div class="bs-wizard-step bs-wizard-step-2 <?php if ($checkout_step == 2) echo 'active'; else if ($checkout_step < 2) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class=""><?= __('Địa chỉ giao hàng', 'hangcu') ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <div class="progress-bar-right"></div>
                            <span class="bs-wizard-dot">2</span>
                        </div>
                    </div>

                    <div class="bs-wizard-step bs-wizard-step-3 <?php if ($checkout_step == 3 || $checkout_step == 4) echo 'active'; else if ($checkout_step < 3) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class=""><?= __('Thanh toán &amp; đặt mua', 'hangcu') ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <!-- <div class="progress-bar-right"></div> -->
                            <span class="bs-wizard-dot">3</span>
                        </div>
                    </div>

                    <!-- <div class="bs-wizard-step bs-wizard-step-4 <?php if ($checkout_step == 4) echo 'active'; else if ($checkout_step < 4) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class="">Hoàn tất đặt hàng</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <span class="bs-wizard-dot">4</span>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    <?php }
}

if( !function_exists('hc_mb_checkout_step') ) {
    function hc_mb_checkout_step() { 
        $checkout_step = WC()->session->get('checkoutstep');
        if( empty($checkout_step) ) $checkout_step = 2;
        $title = '';
        switch ($checkout_step) {
            case 2: 
                $title = __("Địa chỉ nhận hàng", 'hangcu');
                break;
            case 3: 
                $title = __("Thanh toán đơn hàng", 'hangcu');
                break;
            case 4: 
                $title = __("Hoàn tất đặt hàng", 'hangcu');
                // WC()->session->unset('checkoutstep');
                break;
        }
    ?>
        <?php if( $checkout_step < 4 ) : ?>
        <div class="mb-nav-cart">
            <?php if( $checkout_step != 4 ) echo '<i class="icon-back"></i>'  ?>
            <h3><?= $title ?></h3>
        </div>
        <?php endif; ?>
    <?php }
}
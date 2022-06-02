<?php 
    function renderMetaViewport() {
?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php    }
    add_action('wp_head', 'renderMetaViewport');
    wp_head();
?>
<body <?php body_class(); ?>>
<?php martfury_body_open(); ?>

<div class="custom-checkout">
<header id="masthead">
    <div class="wrapper clearfix">
        <div class="header-logo col-lg-3 col-md-6 col-sm-6 col-xs-6 ">
            <div class="d-logo">
                <?php get_template_part( 'template-parts/logo' ); ?>
            </div>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-6 checkout-step">
            <div class="progress-checkout">
                <div class="row bs-wizard">
                    <div class="bs-wizard-step bs-wizard-step-1 <?php if ($checkout_step == 1) echo 'active'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span>Đặt hàng</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-right"></div>
                            <span class="bs-wizard-dot">1</span>
                        </div>
                    </div>

                    <div class="bs-wizard-step bs-wizard-step-2 <?php if ($checkout_step == 2) echo 'active'; else if ($checkout_step < 2) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class="">Địa Chỉ Giao Hàng</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <div class="progress-bar-right"></div>
                            <span class="bs-wizard-dot">2</span>
                        </div>
                    </div>

                    <div class="bs-wizard-step bs-wizard-step-3 <?php if ($checkout_step == 3) echo 'active'; else if ($checkout_step < 3) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class="">Thanh Toán &amp; Đặt Mua</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <div class="progress-bar-right"></div>
                            <span class="bs-wizard-dot">3</span>
                        </div>
                    </div>

                    <div class="bs-wizard-step bs-wizard-step-4 <?php if ($checkout_step == 4) echo 'active'; else if ($checkout_step < 4) echo 'disabled'; ?>">
                        <div class="text-center bs-wizard-stepnum">
                            <span class="">Hoàn tất đặt hàng</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-left"></div>
                            <span class="bs-wizard-dot">4</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="hot-line"></div>
    </div>
</header>
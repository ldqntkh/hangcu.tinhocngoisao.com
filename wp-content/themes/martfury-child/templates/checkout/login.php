<div class="checkout-account">
    <h2><?php _e("1. Đăng nhập hoặc đăng ký", 'thns') ?></h2>
    
    <div class="error_msg woocommerce-notices-wrapper">
        <?php wc_print_notices(); ?>
    </div>
    <div class="account-order">
        <div class="form-account">
            <div class="tab">
                <button class="tablinks" id="checkout_login" onclick="openTabAccount(event, 'login')">
                    <span><?php _e("Đăng nhập", 'thns') ?></span>
                    <br/>
                    <i><?php _e('Dành cho thành viên của STAR', 'thns') ?></i>
                </button>
                <button class="tablinks" id="checkout_register" onclick="openTabAccount(event, 'register')">
                <span><?php _e("Đăng kí", 'thns') ?></span>
                    <br/>
                    <i><?php _e('Dành cho khách hàng mới', 'thns') ?></i>
                </button>
            </div>

            <div id="login" class="tabcontent">
                <form method="post">
                    <div class="input-form">
                        <label for="username"><?php esc_html_e( 'Tên tài khoản hoặc email', 'thns' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" value="<?php echo !empty($_POST['username']) ? $_POST['username'] : '' ?>" name="username" autocomplete="username" id="username" required>
                    </div>
                    <div class="input-form">
                        <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" value="" name="password" autocomplete="current-password" id="password" required>
                    </div>
                    <div class="group-button">
                        <p><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?> <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Tại đây', 'woocommerce' ); ?></a></p> 
                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                        <input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'checkout' ) ) ?>" />
                        <input type="hidden" name="currentTab" value="login"/>
                        <button type="submit" class="btn-login button" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
                    </div>
                </form>
            </div>

            <div id="register" class="tabcontent" style="display: none">
                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php if (false): //( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                            <input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        </p>

                    <?php endif; ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_first_name"><?php esc_html_e( 'Họ tên', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" required class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                        <input required type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                            <input required type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                        </p>

                    <?php else : ?>

                        <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

                    <?php endif; ?>

                    <?php do_action( 'woocommerce_register_form' ); ?>

                    <p class="woocommerce-FormRow form-row">
                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <div class="order-items">
            <div class="head">
                <span><?php echo __('Giỏ hàng', 'thns'); ?></span>
                <a class="edit-order" href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ) ?>"><?php echo __('Sửa', 'thns'); ?></a>
            </div>
            <div class="items">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product = $cart_item['data'];
                    $quantity = $cart_item['quantity'];
                    $product_id = $cart_item['product_id'];
                    $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                    $price = WC()->cart->get_product_price($_product);
                ?>
                    <div class="product-item">
                        <span>
                            <strong><?php echo $quantity;?> x </strong>
                            <?php echo sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ); ?>
                        </span>
                        <?php
                            echo $_product->get_price_html()
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="foot">
                <div class="order-totals">
                    <div class="line">
                        <span><?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>: </span>
                        <strong><?php wc_cart_totals_subtotal_html(); ?></strong>
                    </div>
                    <div class="line total">
                        <span><?php _e( 'Total', 'woocommerce' ); ?>: </span>
                        <strong><?php wc_cart_totals_order_total_html(); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openTabAccount(evt, tabname) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName('tabcontent');
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = 'none';
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName('tablinks');
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(' active', '');
            }

            // Show the current tab, and add an "active" class to the link that opened the tab
            document.getElementById(tabname).style.display = 'block';
            evt.currentTarget.className += ' active';
        }
        var currentTab = '<?php echo !empty( $_POST['login'] ) ? 'checkout_login' : (! empty( $_POST['register'] ) ? 'checkout_register' : 'checkout_login'); ?>';
        <?php if (isset($_GET['type']) && $_GET['type']==='register') : ?>
            currentTab = 'checkout_register';
        <?php endif; ?>
        document.getElementById(currentTab).click();
    </script>
</div>
<?php
if ( ! function_exists( 'hc_header_user_account' ) ) {
    function hc_header_user_account() {
        if ( ! apply_filters( 'electro_enable_header_user_account', false ) ) {
            return;
        }

        $my_account_page_url     = get_permalink( get_option('woocommerce_myaccount_page_id') );
        $is_registration_enabled = false;
        if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
            $is_registration_enabled = true;
        }
        $user_account_nav_menu      = apply_filters( 'electro_user_account_nav_menu_ID', 0 );
        $user_account_nav_menu_args = apply_filters( 'electro_user_account_nav_menu_args', array(
            'container'   => false,
            'menu'        => $user_account_nav_menu,
            'menu_class'  => 'dropdown-menu dropdown-menu-user-account',
            'depth'       => 1,
            'items_wrap'  => '%3$s'
        ) );

        $header_user_icon = apply_filters( 'electro_header_user_account_icon', 'ec ec-user' );
        $login_text       = apply_filters( 'electro_header_user_account_login_text', esc_html__( 'Returning Customer ?', 'electro' ) ) ;
        $register_text    = apply_filters( 'electro_header_user_account_register_text', esc_html__( 'Don\'t have an account ?', 'electro' ) ) ;

        $header_tooltip_placement = apply_filters( 'electro_header_tooltip_placement', 'bottom' );

        ?>
        <div class="header-icon">
        <?php if( is_user_logged_in() && (is_cart() || is_checkout() || is_account_page())  ) : 
                $current_user = wp_get_current_user();
                $user_name = $current_user->display_name;
            ?>
                
                <div id="user-account" data-login="true">
                    <a href="<?= wc_get_page_permalink( 'myaccount' ) ?>">
                        <!-- <i class="icon-user"></i> -->
                        <i class="ec ec-user"></i>
                        <span>
                            <span><?= $user_name ?></span></br>
                            <span class="icon-down"><?= __("Tài khoản", "hangcu") ?>
                                <!-- <i class="arrow-down"></i> -->
                            </span>
                        </span>
                    </a>
                </div>
                
            <?php else : ?>
                <div id="user-account-false" data-login="false">
                    <a href="<?= get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>" type="login" id="login">
                    <aside >
                        <!-- <i class="icon-user"></i> -->
                        <i class="ec ec-user"></i>
                        <span>
                            <span><?= __("Đăng nhập / Đăng ký", "hangcu") ?></span></br>
                            <span class="icon-down"><?= __("Tài khoản", "hangcu") ?><i class="arrow-down"></i></span>
                        </span>
                    </aside>
                    </a>
                </div>
                <!-- login form area -->
            <?php endif; ?>
        </div><?php
    }
}
<?php
add_action( 'login_enqueue_scripts', 'hangcu_admin_login_logo' );

add_action( 'login_footer', 'change_social_login_buttons' );
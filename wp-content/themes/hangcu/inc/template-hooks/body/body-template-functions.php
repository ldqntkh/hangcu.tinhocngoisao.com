<?php
function hc_body_classes( $classes ) {
    $user_id = get_current_user_id();
    $cls = '';
    if( !$user_id ) $cls = ' not-login';
    if( !wp_is_mobile() ) {
        $classes[] = 'body-desktop' . $cls;
    } else {
        $classes[] = 'body-mobile' . $cls;
    }
    return $classes;
}
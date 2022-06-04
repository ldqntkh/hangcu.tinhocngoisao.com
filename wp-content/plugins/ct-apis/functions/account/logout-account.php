<?php

if( !function_exists('hc_logout_account') ) {
    function hc_logout_account() {
        wp_logout();
        wp_send_json_success();
        die;
    }
}
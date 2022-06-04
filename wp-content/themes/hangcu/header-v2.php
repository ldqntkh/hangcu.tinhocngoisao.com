<?php
if( wp_is_mobile() ) {
    require THEME_PATH . '/header-mobile.php';
} else {
    require THEME_PATH . '/header-desktop.php';
}
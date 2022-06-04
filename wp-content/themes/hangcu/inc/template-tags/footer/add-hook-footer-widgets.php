<?php

add_action( 'after_setup_theme', function() {
    add_action( 'electro_footer_v2',           'electro_footer_divider_v2',             10 );
    add_action( 'electro_footer_v2',           'electro_footer_widgets_v2',             20 );
    add_action( 'electro_footer_v2',           'electro_footer_bottom_widgets_v2',      30 );
    add_action( 'electro_footer_v2',           'electro_copyright_bar_v2',              40 );
    // add_action( 'electro_footer_v2',           'electro_footer_v2_wrap_close',          45 );
    // add_action( 'electro_footer_v2',           'electro_footer_v2_handheld_wrap_open',  50 );
    // add_action( 'electro_footer_v2',           'electro_footer_v2_handheld',            60 );
}, 2 );
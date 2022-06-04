<?php
add_action( 'after_setup_theme', function() {
    remove_action( 'electro_footer_v2',           'electro_footer_widgets_v2',             10 );
    remove_action( 'electro_footer_v2',           'electro_footer_divider_v2',             20 );
    remove_action( 'electro_footer_v2',           'electro_footer_bottom_widgets_v2',      30 );
    remove_action( 'electro_footer_v2',           'electro_copyright_bar_v2',              40 );
    remove_action( 'electro_footer_v2',           'electro_footer_v2_wrap_close',          45 );
    remove_action( 'electro_footer_v2',           'electro_footer_v2_handheld_wrap_open',  50 );
    remove_action( 'electro_footer_v2',           'electro_footer_v2_handheld',            60 );
}, 0);

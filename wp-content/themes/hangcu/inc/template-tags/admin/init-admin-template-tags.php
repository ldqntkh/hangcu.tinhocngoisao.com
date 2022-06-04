<?php
// customize clean search cache
add_action('admin_bar_menu', function($wp_admin_bar) {
    $args = array(
        'id' => 'clear_searchbox',
        'title' => 'Clean SearchBox Cache',
        'href' => '#',
    );
    $wp_admin_bar->add_node($args);
}, 999);

add_action('admin_footer', function() { ?>
   <script>
    let woos_data_clean = {
      "action": "clean_cache_rebuild_ajax"
    };
    jQuery('body').on('click', '#wp-admin-bar-clear_searchbox a', function() {
        jQuery("#wp-admin-bar-clear_searchbox a").text("Cleaning started...");
        jQuery.post(ajaxurl, woos_data_clean, function(response) {
            jQuery("#wp-admin-bar-clear_searchbox a").text("Clean SearchBox Cache");
        }).fail(function() {
            
        });
    });
        </script>
<?php });

add_action( 'wp_ajax_clean_cache_rebuild_ajax', 'clean_cache_rebuild_ajax' );

add_action( 'post_submitbox_minor_actions', 'show_btn_clean_post_cache', 30 );
add_action( 'wp_ajax_clean_post_cache_id', 'clean_post_cache_id' );
add_action('admin_footer', 'post_script_clean_cache');
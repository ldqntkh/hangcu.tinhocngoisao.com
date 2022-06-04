<?php

if( !function_exists('clean_cache_rebuild_ajax') ) {
    function clean_cache_rebuild_ajax() {
        $updir=wp_upload_dir();
        $cache_dir=$updir['basedir'].'/woos_search_engine_cache';
        $files = glob( $cache_dir . '/*'); 
        foreach($files as $file){
          if(is_file($file)) {
            unlink($file); 
          }
        }
    
        wp_send_json_success();
     
        die();
    }
}

if( !function_exists( 'show_btn_clean_post_cache' ) ) {
    function show_btn_clean_post_cache( $post ) { 
        if( function_exists( 'w3tc_pgcache_flush_post' ) ) :    
    ?>
        <div id="clean-post-cache">
            <a style="margin: 0 5px" class="button" href="#" data_post="<?= $post->ID ?>" id="clean-w3t">Clean cache</a>
        </div>
    <?php endif; }
}


if( !function_exists('clean_post_cache_id') ) {
    function clean_post_cache_id($request) {
        // if( function_exists( 'w3tc_pgcache_flush_post' ) ) {
        //     // $clean = w3tc_pgcache_flush_url( intval($_REQUEST['post_id']) );
        //     wp_send_json_success($url);
        // }

        if( function_exists( 'w3tc_pgcache_flush_url' ) ) {
            $url = get_permalink( intval($_REQUEST['post_id']) );
            w3tc_pgcache_flush_url( $url );
            wp_send_json_success($url);
        }
    
        wp_send_json_success();
     
        die();
    }
}

if( !function_exists( 'post_script_clean_cache' ) ) {
    function post_script_clean_cache(  ) { ?>
        <script>
            jQuery('body').on('click', '#clean-post-cache a', function(e) {
                e.preventDefault();
                jQuery("#clean-post-cache a").text("Cleaning started...");
                let post_id = jQuery("#clean-post-cache a").attr('data_post');
                let post_data_clean = {
                    "action": "clean_post_cache_id",
                    "post_id" : post_id
                };
                jQuery.post(ajaxurl, post_data_clean, function(response) {
                    jQuery("#clean-post-cache a").text("Clean cache");
                }).fail(function() {
                    
                });
            });
        </script>
    <?php }
}
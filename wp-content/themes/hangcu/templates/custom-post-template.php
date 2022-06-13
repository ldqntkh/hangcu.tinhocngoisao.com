<?php
/*
* Template Name: HC Sidebar blog template
* Template Post Type: post
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();

while ( have_posts() ) :
    // get left content
    $current_post_id = get_the_ID();
    $ids = get_field( 'danh_sach_bai_viet', $current_post_id );
    $post_ids = explode( ',', $ids );
    $content_sidebar = '<ul>';
    foreach( $post_ids as $post_id ) :
        $post = get_post( $post_id ); 
        if( $post && $post->post_status == 'publish' ) {
            $title = $post->post_title;
            $url = get_permalink($post_id);
            $content_sidebar .= '<li class="'. ($post_id == $current_post_id ? "active" : '') .'"><a href="'.$url.'">'.$title.'</a><i class="fa fa-angle-right"></i></li>';
        }
        wp_reset_query();
    endforeach;
    $content_sidebar .= '</ul>';
    // var_dump($ids);
    echo '<div class="hc-custom-post">';
    if( electro_detect_is_mobile() ) : ?>
        <div class="hc-post-content">
            <?php 
                echo '<h1>' .get_the_title($current_post_id). '</h1>';
                the_post();
                the_content();
            ?>
        </div>
        <div class="hc-post-sidebar">
            <?php 
                // render content sidebar
                echo $content_sidebar;
            ?>
        </div>
    <?php else : ?>
        <div class="hc-post-sidebar">
            <?php 
                // render content sidebar
                echo $content_sidebar;
            ?>
        </div>
        <div class="hc-post-content">
            <?php 
                echo '<h1>' .get_the_title($current_post_id). '</h1>';
                the_post();
                the_content();
            ?>
        </div>
    <?php endif;
    echo '</div>';
	
endwhile; // End of the loop.

get_footer();

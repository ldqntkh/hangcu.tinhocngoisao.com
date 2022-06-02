<?php
if( !function_exists('thns_homepage_post') ) {

    function thns_homepage_post($args) {
        if( empty( $args['category_id'] ) ) return;
        $posts = wp_get_recent_posts([
            "numberposts" => 5,
            "post_status" => "publish",
            "category"  => $args['category_id']
        ]);

        if( $posts && count($posts) > 0 ) :
            echo '<div class="post-component">';
            $index = 1;

            $first_post = $posts[0];
            $img_thumb = get_the_post_thumbnail($first_post["ID"]);
            $title = $first_post['post_title'];
            $url = get_post_permalink($first_post["ID"]);
            $post_type = get_post_format($first_post["ID"]);
        ?>
            <div class="first-item <?php if( $post_type == 'video' ) echo ' post-cpn-video' ?>" <?php if( $post_type == 'video' ) echo 'data-post-id="'.$first_post["ID"].'"' ?>>
                <a href="<?= $url ?>">
                    <div class="thumb">
                        <?= $img_thumb ?>
                    </div>
                    <div class="title">
                        <a href="<?= $url ?>"><?= $title ?></a>
                    </div>
                </a>
            </div>
            <div class="list-item">
        <?php
            for( $i = 1; $i < count($posts); $i++ ):
                $_post = $posts[$i];
                $img_thumb = get_the_post_thumbnail($_post["ID"]);
                $title = $_post['post_title'];
                $url = get_post_permalink($_post["ID"]);
                $post_type = get_post_format($_post["ID"]);
            ?>
                <div class="post-item" <?php if( $post_type == 'video' ) echo ' post-cpn-video' ?>" <?php if( $post_type == 'video' ) echo 'data-post-id="'.$_post["ID"].'"' ?>>
                    <a href="<?= $url ?>">
                        <div class="thumb">
                            <?= $img_thumb ?>
                        </div>
                        <div class="title">
                            <a href="<?= $url ?>"><?= $title ?></a>
                        </div>
                    </a>
                </div>
                
            <?php
            endfor;
            echo '</div>';
            echo '</div>';
        endif;
    }
    add_shortcode('thns_homepage_post', 'thns_homepage_post');
}

if( !function_exists('thns_get_post_content') ) {
    function thns_get_post_content() {
        $postId = $_GET['post_id'];

        if( empty( $postId ) ) {
            wp_send_json_error([
                "success"   => false,
                "errMsg"    => "Cannot find post"
            ]);
            die;
        } else {
            $post = get_post($postId);
            if( $post ) {
                $content = $post->post_content;
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);

                $video_url = get_field( 'youtube_video_url', $postId );
                wp_send_json_success([
                    "success"   => true,
                    "data"  => [
                        "video_url" => $video_url,
                        "content"   => $content
                    ],
                    "errMsg"    => ""
                ]);
                die;
            } else {
                wp_send_json_error([
                    "success"   => false,
                    "errMsg"    => "Cannot find post"
                ]);
                die;
            }
        }
    }

    add_action("wp_ajax_thns_get_post_content", "thns_get_post_content");
    add_action("wp_ajax_nopriv_thns_get_post_content", "thns_get_post_content");

}
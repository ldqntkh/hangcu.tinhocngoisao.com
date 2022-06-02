<?php
define ( 'THEME_PATH', get_stylesheet_directory() );
define( 'THEME_PATH_URI',  get_stylesheet_directory_uri());
define ( 'THEME_VERSION', '1.0.4.1' );
// add_action( 'wp_enqueue_scripts', 'martfury_child_enqueue_scripts', 20 );
// function martfury_child_enqueue_scripts() {
// 	wp_enqueue_style( 'martfury-child-style', get_stylesheet_uri() );
// 	if ( is_rtl() ) {
// 		wp_enqueue_style( 'martfury-rtl', get_template_directory_uri() . '/rtl.css', array(), '20180105' );
// 	}
// }

// cache file

if ( !function_exists('get_cache_by_key') ) {
    
    function get_cache_by_key( $key , $filename = 'json-cache.txt') {
        // return null;
        $cache_file_path = get_transient( $filename );
        // if ( file_exists ( $cache_file_path )  ) {
            $json = json_decode($cache_file_path,TRUE);
            if (isset($json)) {
                if (isset($json[$key])) {
                    return $json[$key];
                } else return null;
            }
        // }
        
        return null;
    }
}

if ( !function_exists('set_cache_by_key') ) {
    function set_cache_by_key ($key, $content, $filename = 'json-cache.txt') {
        // $cache_file_path = plugin_dir_path( __FILE__ ) . '/custom-cache/' .$filename;
        // $json[$key] = $content;
        // file_put_contents($cache_file_path, json_encode($json));
    }
}

add_action( 'after_setup_theme', function() {
    register_nav_menus( array(
        'special-menu' => esc_html__( 'Special Menu ( Display Beside Primary Menu)', 'online-shop' ),
    ) );
} );


add_action( 'wp_footer', function(){
    if ( !empty( get_option( 'custom_preferences_options' )['render_chatbox'] ) ) :
        echo get_option( 'custom_preferences_options' )['render_chatbox'];
    endif;
    if ( !empty( get_option( 'custom_preferences_options' )['render_footer_script'] ) ) :
        echo get_option( 'custom_preferences_options' )['render_footer_script'];
    endif;

    // render function zalo
    if ( !empty( get_option( 'custom_preferences_zalo_options' )['zalo_enable'] ) && get_option( 'custom_preferences_zalo_options' )['zalo_enable'] === "true" ) :
        if ( !empty( get_option( 'custom_preferences_zalo_options' )['zalo_script_url'] ) && get_option( 'custom_preferences_zalo_options' )['zalo_script_url'] !== "" ) :
            echo '<script src="'. get_option( 'custom_preferences_zalo_options' )['zalo_script_url'] .'"></script>';
        endif;
    endif;
}, 100 );

add_action( 'wp_footer', function() {
    wp_enqueue_script('buildpc_script', get_stylesheet_directory_uri() . '/assets/js/bundle.js', array('jquery'), THEME_VERSION);
});

include_once (THEME_PATH . '/inc/api/functions.php');
include_once (THEME_PATH . '/inc/register-style.php');
include_once (THEME_PATH . '/inc/custom-mobile-menu.php');
include_once (THEME_PATH . '/inc/post-component/post-shortcode.php');


include_once (THEME_PATH . '/inc/filter/search-order.php');
include_once (THEME_PATH . '/inc/print_order/print_order.php');

include_once (THEME_PATH . '/inc/functions/hooks.php');

//webhook
include_once (THEME_PATH . '/inc/webhooks/new-order.php');

add_action( 'martfury_woo_after_shop_loop_item_title', 'woocommerce_template_loop_saletotal', 30 );
function woocommerce_template_loop_saletotal() { 
    global $product, $post;    
    $units_sold = $product->get_total_sales();
    $stock = $product->get_total_stock();
    if( !$units_sold ) $units_sold = 0;
    $total_stock = $units_sold + $stock;
    $percent = round($units_sold/$total_stock * 100);
?>
    <div class="fsale-stock">
        <span class="total-100">
            <span class="sale"><?php echo $units_sold; ?>/<?php echo $total_stock; ?> Đã bán</span>
            <span class="percent" style="width: <?php echo $percent ?>%"></span>
        </span>
    </div>
<?php }

// add_filter( 'woocommerce_product_query_meta_query', 'show_only_products_with_specific_metakey', 10, 2 );
function show_only_products_with_specific_metakey( $meta_query, $query ) {
    $meta_query[] = array(
        'key'     => '_stock_status',
        'value'   => 'instock',
        'compare' => '=',
    );
    return $meta_query;
}

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'wp_head', function() { ?>
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '476182570133494');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=476182570133494&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
<?php }, 10000 );


add_action( 'wp_body_open', function() { ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MDFX3P9"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php } );

// add comment tags
add_filter( 'woocommerce_product_review_comment_form_args', 'change_comment_form_defaults');
function change_comment_form_defaults( $default ) {
    $commenter = wp_get_current_commenter();
    global $product;
    
    $flag_tag = false;
    $out = '<fieldset class="comment-form-tags">      
            <legend>Tags: </legend>';

    $cat = get_the_terms( $product->ID, 'product_cat' );
    foreach ($cat as $categoria) {
        $cat = get_term_by( 'slug', $categoria->slug, 'product_cat' );
        $comment_tags = get_field('comment_tags', $categoria);
        if( $comment_tags ) {
            $comment_tags = explode(';', $comment_tags);
            foreach( $comment_tags as $cmt ) {
                $out .= '<div class="form-check">
                        <label class="form-check-label" for="tag_'.$cmt.'">
                        <input type="checkbox" id="tag_'.$cmt.'" name="comment_tags[]" value="'. $cmt .'">
                        '.$cmt.'
                        </label>
                    </div>';
            }
            $flag_tag = true;
            break;
        }
       
    }
    
    $out .= '</fieldset>';
    if( $flag_tag ) {
        $default[ 'comment_field' ] .= $out;
    }
    

    // init comment video/image
    $out_media = '<div id="media-fields">
                    <input type="hidden" id="media_video_url" name="media_video_url" >
                    <input type="hidden" id="media_image_urls" name="media_image_urls" >
                </div>';
                
    $default[ 'comment_field' ] .= $out_media;
    
    return $default;
}
add_action('comment_post', 'add_tags_to_comment', 10, 2);
function add_tags_to_comment( $comment_ID, $comment_approved ) {
    
    for( $i = 0; $i < count( $_POST["comment_tags"] ); $i++ ) {
        add_comment_meta( $comment_ID, "comment_tag", $_POST["comment_tags"][$i] );
    }

    if( isset( $_POST["media_video_url"] ) ) {
        add_comment_meta( $comment_ID, "media_video_url", $_POST["media_video_url"] );
    } 
    
    if( isset( $_POST["media_image_urls"] ) ) {
        $images = $_POST["media_image_urls"];
        $string = str_replace( '\\', '', str_replace(']', '', str_replace('[', '',$images)) );

        $images = $array = explode(',', $string);
        
        $_images = [];
        for( $i = 0; $i < count($images); $i++ ) {
            if( !empty($images[$i]) && $images[$i] != null && $images[$i] != 'null' ) {
                
                array_push( $_images, str_replace( '"', '', $images[$i] ) );
            }
        }
        add_comment_meta( $comment_ID, "media_image_urls", json_encode($_images) );
    }
}

add_filter( 'comments_template_query_args', function( $comment_args )
{
    if(isset( $_GET['tag_id'])) {
        $tag_id = base64_decode($_GET['tag_id']);
        // Our modifications
        $comment_args['meta_query'] = [
            [
                'key' => 'comment_tag',
                'value' => [$tag_id],
                'compare' => 'IN'
            ]
        ];  
    }
          

    return $comment_args;
} );

// show comment 
add_action( 'woocommerce_review_meta', 'show_comment_meta_media', 10, 1 );
if( !function_exists( 'show_comment_meta_media' ) ) {
    function show_comment_meta_media( $comment ) {
        if( wp_get_comment_status( $comment->comment_ID ) == 'approved' ) {
            $comment_video = get_comment_meta( $comment->comment_ID, 'media_video_url', true );
            $comment_images = get_comment_meta( $comment->comment_ID, 'media_image_urls', true );
            ?>
                <div class="cmt-media">
                    <?php if( isset( $comment_video ) && !empty( $comment_video ) ) : ?>
                        <div class="video"><video src="<?= $comment_video ?>"></video></div>
                    <?php endif; ?>

                    <?php if( isset( $comment_images ) ) : 
                        $comment_images = json_decode( $comment_images );
                        foreach( $comment_images as $image ) : 
                            if( isset( $image ) && !empty( $image ) ) :
                        ?>
                            <img src="<?= $image ?>"/>
                        <?php endif; 
                        endforeach;
                    endif; ?>

                </div>
            <?php
        }
        
    }
}

add_filter( 'comment_text', 'show_media_and_tag_comment', 10, 3 );
if( !function_exists( 'show_media_and_tag_comment' ) ) {
    function show_media_and_tag_comment( $comment_text, $comment, $args ) {
        
        // $comment_text .= $comment->comment_ID;
        if( is_admin() ) {
            $comment_tags = get_comment_meta( $comment->comment_ID, 'comment_tag', true );
            $comment_video = get_comment_meta( $comment->comment_ID, 'media_video_url', true );
            $comment_images = get_comment_meta( $comment->comment_ID, 'media_image_urls', true );
    
            if( isset( $comment_tags ) && !empty($comment_tags) ) {
                $comment_text .= '<br/><p><strong>Tags:</strong>' . $comment_tags . '</p>';
            }
    
            if( isset( $comment_video ) && !empty($comment_video) ) {
                $comment_text .= '<br/><p><strong>Video: </strong><a href="'.$comment_video.'" target="_blank">'.$comment_video.'</a></p>';
            }
    
            if( isset( $comment_images ) && !empty($comment_images) ) {
                $comment_text .= '<br/><p><strong>Hình ảnh: </strong>';
                $comment_images = json_decode( $comment_images );
                foreach( $comment_images as $image ) : 
                    if( isset( $image ) && !empty( $image ) ) : 
                        $comment_text .= '<br/><a href="'.$image.'" target="_blank">'.$image.'</a>';
                    endif;
                endforeach;
                $comment_text .= '</p>';
            }
        }
        

        return $comment_text;
    }
}
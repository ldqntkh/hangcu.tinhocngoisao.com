<?php 
if (!defined( 'ABSPATH')) {
    die;
}

class CT_Widget_Display_StaticBlockCT_Widget_Brands_Slider extends WP_Widget {
 
    /**
     * Thiết lập widget: đặt tên, base ID
     */
    function __construct() {
        parent::__construct (
            'widget_display_static_block', // id của widget
            'CT Widget Display Static Block', // tên của widget
       
            array(
                'description' => 'Widget hiển thị nội dung static block' // mô tả
            )
        );
    }

    /**
     * Tạo form option cho widget
     */
    function form( $instance ) {
        parent::form( $instance );
 
        //Biến tạo các giá trị mặc định trong form
        $default = array(
            'static_block_id' => '',
            'custom_css_class' => '',
            'custom_css' => ''
        );
 
        //Gộp các giá trị trong mảng $default vào biến $instance để nó trở thành các giá trị mặc định
        $instance = wp_parse_args( (array) $instance, $default);
 
        //Tạo biến riêng cho giá trị mặc định trong mảng $default
        $static_block_id = esc_attr( $instance['static_block_id'] );
        $custom_css_class = esc_attr( $instance['custom_css_class'] );
        $custom_css = esc_attr( $instance['custom_css'] );
 
        //Hiển thị form trong option của widget
        echo "<p>Id Static block <input class='widefat' type='text' name='".$this->get_field_name('static_block_id')."' value='".$static_block_id."' /></p>";
        echo "<p>Css Class name <input class='widefat' type='text' name='".$this->get_field_name('custom_css_class')."' value='".$custom_css_class."' /></p>";
        echo "<p>Custom css <textarea class='widefat' type='text' name='".$this->get_field_name('custom_css')."'>" .$custom_css. "</textarea></p>";
    }

    /**
     * save widget form
     */

    function update( $new_instance, $old_instance ) {
        parent::update( $new_instance, $old_instance );
 
        $instance = $old_instance;
        $instance['static_block_id'] = strip_tags($new_instance['static_block_id']);
        $instance['custom_css_class'] = strip_tags($new_instance['custom_css_class']);
        $instance['custom_css'] = strip_tags($new_instance['custom_css']);
        return $instance;
    }

    /**
     * Show widget
     */

    function widget( $args, $instance ) {

        extract( $args );
        // $title = apply_filters( 'widget_title', $instance['title'] );
        $static_block_id = $instance['static_block_id'];
        $custom_css_class = $instance['custom_css_class'];
        $custom_css = $instance['custom_css'];
        echo $before_widget;
 
        //In tiêu đề widget
        // echo $before_title.$title.$after_title;
 
        // Nội dung trong widget
        if ( empty( $static_block_id ) ) return "";
        $content = get_post_field('post_content', $static_block_id);
        echo "<aside class='" . $custom_css_class .  "' style='" .$custom_css. "'>".do_shortcode($content)."</aside>";
 
        // Kết thúc nội dung trong widget
 
        echo $after_widget;
    }

}
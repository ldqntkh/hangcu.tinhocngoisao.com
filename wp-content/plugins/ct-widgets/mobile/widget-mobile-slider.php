<?php 
if (!defined( 'ABSPATH')) {
    die;
}

class CT_Widget_Mobile_HomePage_Slider extends WP_Widget {
 
    /**
     * Thiết lập widget: đặt tên, base ID
     */
    function __construct() {
        parent::__construct (
            'widget_mobile_homepage_slider', // id của widget
            'CT Widget Mobile Homepage Slider', // tên của widget
       
            array(
                'description' => 'Widget hiển thị slider cho trang chủ trên Mobile' // mô tả
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
            'slider_id' => '',
            'custom_css' => ''
        );
 
        //Gộp các giá trị trong mảng $default vào biến $instance để nó trở thành các giá trị mặc định
        $instance = wp_parse_args( (array) $instance, $default);
 
        //Tạo biến riêng cho giá trị mặc định trong mảng $default
        $slider_id = esc_attr( $instance['slider_id'] );
        $custom_css = esc_attr( $instance['custom_css'] );
 
        //Hiển thị form trong option của widget
        echo "<p>Id Slider <input class='widefat' type='text' name='".$this->get_field_name('slider_id')."' value='".$slider_id."' /></p>";
        echo "<p>Custom css <textarea class='widefat' type='text' name='".$this->get_field_name('custom_css')."'>" .$custom_css. "</textarea></p>";
    }

    /**
     * save widget form
     */

    function update( $new_instance, $old_instance ) {
        parent::update( $new_instance, $old_instance );
 
        $instance = $old_instance;
        $instance['slider_id'] = strip_tags($new_instance['slider_id']);
        $instance['custom_css'] = strip_tags($new_instance['custom_css']);
        return $instance;
    }

    /**
     * Show widget
     */

    function widget( $args, $instance ) {

        extract( $args );
        // $title = apply_filters( 'widget_title', $instance['title'] );
        $slider_id = $instance['slider_id'];
        $custom_css = $instance['custom_css'];
        
        // echo $before_widget;
 
        //In tiêu đề widget
        // echo $before_title.$title.$after_title;
 
        // Nội dung trong widget
        if ( empty( $slider_id ) ) return "";

        echo "<div id='mobile-home-slider' style='" .$custom_css. "'>".do_shortcode( '[smartslider3 slider='.$slider_id.']' )."</div>";
 
        // Kết thúc nội dung trong widget
 
        // echo $after_widget;
    }

}
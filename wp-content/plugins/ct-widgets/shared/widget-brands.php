<?php 
if (!defined( 'ABSPATH')) {
    die;
}

class CT_Widget_Brands_Slider extends WP_Widget {
 
    /**
     * Thiết lập widget: đặt tên, base ID
     */
    function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'scripts'));
        parent::__construct (
            'widget_hangcu_brands', // id của widget
            'CT Widget Brands Name', // tên của widget
       
            array(
                'description' => 'Widget hiển thị danh sách ngành hàng' // mô tả
            )
        );
    }

    public function scripts()
    {
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_media();
        wp_enqueue_script('media-select', plugins_url('../assets/js/media-select.js',__FILE__), array('jquery'));
    }

    /**
     * Tạo form option cho widget
     */
    function form( $instance ) {
        parent::form( $instance );
 
        //Biến tạo các giá trị mặc định trong form
        $default = array(
            'display_limit' => 16,
            'header_bg_image_url' => '',
            'header_title' => '',
            'viewmore_title' => 'Xem thêm',
            'viewmore_url' => '',
            'custom_css_class' => '',
            'custom_css' => ''
        );
 
        //Gộp các giá trị trong mảng $default vào biến $instance để nó trở thành các giá trị mặc định
        $instance = wp_parse_args( (array) $instance, $default);
 
        //Tạo biến riêng cho giá trị mặc định trong mảng $default
        $display_limit = esc_attr( $instance['display_limit'] );
        $custom_css = esc_attr( $instance['custom_css'] );
        $header_title = esc_attr( $instance['header_title'] );
        $viewmore_title = esc_attr( $instance['viewmore_title'] );
        $viewmore_url = esc_attr( $instance['viewmore_url'] );
        $custom_css_class = esc_attr( $instance['custom_css_class'] );
        $header_bg_image_url = ! empty( $instance['header_bg_image_url'] ) ? $instance['header_bg_image_url'] : '';
 
        //Hiển thị form trong option của widget
        echo "<p>Số nhãn hàng hiển thị <input class='widefat' type='number' name='".$this->get_field_name('display_limit')."' value='".$display_limit."' /></p>";
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'header_bg_image_url' ); ?>"><?php _e( 'Backgroud image header:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'header_bg_image_url' ); ?>" name="<?php echo $this->get_field_name( 'header_bg_image_url' ); ?>" type="text" 
                    value="<?php echo esc_url( $header_bg_image_url ); ?>" />
            <button class="upload_image_button button button-primary">Upload Image</button>
        </p>
        <?php
        echo "<p>Tiêu đề <input class='widefat' type='text' name='".$this->get_field_name('header_title')."' value='".$header_title."' /></p>";
        echo "<p>Tiêu đề xem thêm <input class='widefat' type='text' name='".$this->get_field_name('viewmore_title')."' value='".$viewmore_title."' /></p>";
        echo "<p>Url xem thêm <input class='widefat' type='text' name='".$this->get_field_name('viewmore_url')."' value='".$viewmore_url."' /></p>";
        echo "<p>Css Class name <input class='widefat' type='text' name='".$this->get_field_name('custom_css_class')."' value='".$custom_css_class."' /></p>";
        echo "<p>Custom css <textarea class='widefat' type='text' name='".$this->get_field_name('custom_css')."'>" .$custom_css. "</textarea></p>";
    }

    /**
     * save widget form
     */

    function update( $new_instance, $old_instance ) {
        parent::update( $new_instance, $old_instance );
 
        $instance = $old_instance;
        $instance['display_limit'] = strip_tags($new_instance['display_limit']);
        $instance['header_title'] = strip_tags($new_instance['header_title']);
        $instance['viewmore_title'] = strip_tags($new_instance['viewmore_title']);
        $instance['viewmore_url'] = strip_tags($new_instance['viewmore_url']);
        $instance['custom_css_class'] = strip_tags($new_instance['custom_css_class']);
        $instance['header_bg_image_url'] = strip_tags($new_instance['header_bg_image_url']);
        $instance['custom_css'] = strip_tags($new_instance['custom_css']);
        return $instance;
    }

    /**
     * Show widget
     */

    function widget( $args, $instance ) {

        extract( $args );
        // $title = apply_filters( 'widget_title', $instance['title'] );
        $display_limit = esc_attr( $instance['display_limit'] );
        $custom_css = esc_attr( $instance['custom_css'] );
        $header_title = esc_attr( $instance['header_title'] );
        $viewmore_title = esc_attr( $instance['viewmore_title'] );
        $viewmore_url = esc_attr( $instance['viewmore_url'] );
        $custom_css_class = esc_attr( $instance['custom_css_class'] );
        $header_bg_image_url = ! empty( $instance['header_bg_image_url'] ) ? $instance['header_bg_image_url'] : '';
        
        if( !$display_limit || $display_limit < 1 ) $display_limit = 16;

        // echo $before_widget;
 
        //In tiêu đề widget
        // echo $before_title.$title.$after_title;
 
        // Nội dung trong widget
        ?>

        <asside id='widget_hangcu_brands' class="<?php echo $custom_css_class ?>" style="<?php echo $custom_css ?>">
            <div class="brands">
                <?php if( !empty( $header_bg_image_url ) ) : ?>
                    <div class="brand-header has-bg"
                        style=" background: url( '<?= $header_bg_image_url ?>' ) center no-repeat "
                    >
                        <?php if( !empty( $viewmore_url ) && !empty( $viewmore_title ) ) : ?>
                            <a href="<?= $viewmore_url ?>"><?= $viewmore_title ?></a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="brand-header">
                        <h3><?= $header_title ?></h3>
                        <?php if( !empty( $viewmore_url ) && !empty( $viewmore_title ) ) : ?>
                            <a href="<?= $viewmore_url ?>"><?= $viewmore_title ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="brand-body">
                    <?= do_shortcode('[display_brands limit="'.$display_limit.'"]') ?>
                </div>
            </div>
        </asside>
 
        
 
        <?php
        // Kết thúc nội dung trong widget
        // echo $after_widget;
    }

}
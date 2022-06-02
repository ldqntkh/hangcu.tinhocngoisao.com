<?php
/**
 * Display primetime price shock
 *
 * @package Acme Themes
 * @subpackage Online Shop
 * @since 1.0.0
 * text domain: home-page-widget
 */

if ( ! class_exists( 'ShowListProductSale' ) ) {

    class ShowListProductSale extends WP_Widget {

        function __construct() {
            parent::__construct(
                /*Base ID of your widget*/
                'show_list_product_hot_deal_pc',
                /*Widget name will appear in UI*/
                esc_html__('Show list product is sale in primetime', 'home-page-widget'),
                /*Widget description*/
                array( 'description' => esc_html__( 'Show list product is sale in primetime', 'home-page-widget' ), )
            );

            // $this->register_footer_client_script();
        }

        // register footer client script
        // function register_footer_client_script() {
        //     function client_script() {
        //         wp_register_script( 'primetime_price_client_script', get_stylesheet_directory_uri() . '/assets/js/primetime.js', '', '', true );
        //         wp_enqueue_script( 'primetime_price_client_script' );
        //     }
        //     add_action( 'wp_enqueue_scripts', 'client_script' );
        // }

        public function form($instance) {
            $block_title = isset( $instance[ BLOCK_TITLE ] ) ? $instance[ BLOCK_TITLE ] : '';
            $block_catslug = isset( $instance[ BLOCK_CATSLUG ] ) ? $instance[ BLOCK_CATSLUG ] : '';
            $block_total = isset( $instance[ BLOCK_TOTAL ] ) ? $instance[ BLOCK_TOTAL ] : '10';
            $block_image = isset( $instance[ BLOCK_IMAGE ] ) ? $instance[ BLOCK_IMAGE ] : '';
            $block_type = isset( $instance[ BLOCK_TYPE ] ) ? $instance[ BLOCK_TYPE ] : 'desktop';
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( BLOCK_TITLE ) ?>"> <?php _e('Title', 'home-page-widget'); ?> </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( BLOCK_TITLE ) ?>" 
                    name="<?php echo $this->get_field_name( BLOCK_TITLE ) ?>" 
                    value="<?php echo $block_title; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( BLOCK_CATSLUG ) ?>"> <?php _e('Category slug', 'home-page-widget'); ?> </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( BLOCK_CATSLUG ) ?>" 
                    name="<?php echo $this->get_field_name( BLOCK_CATSLUG ) ?>" 
                    value="<?php echo $block_catslug; ?>"/>
                <i><?php _e('Category slug là chuỗi, nó sẽ giúp xác định được sản phẩm hotdeal được lấy nằm ở category nào!', 'home-page-widget'); ?></i>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( BLOCK_TOTAL ) ?>"> <?php _e('Total products display', 'home-page-widget'); ?> </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( BLOCK_TOTAL ) ?>" 
                    name="<?php echo $this->get_field_name( BLOCK_TOTAL ) ?>" 
                    value="<?php echo $block_total; ?>"/>
                <i><?php _e('Số lượng sản phẩm sẽ được hiển thị', 'home-page-widget'); ?></i>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( BLOCK_IMAGE ) ?>"> <?php _e('Image url', 'home-page-widget'); ?> </label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( BLOCK_IMAGE ) ?>" 
                    name="<?php echo $this->get_field_name( BLOCK_IMAGE ) ?>" 
                    value="<?php echo $block_image; ?>"/>
                <i><?php _e('Hình ảnh sẽ được hiển thị khi không có sản phẩm nào trong mục hotdeal', 'home-page-widget'); ?></i>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( BLOCK_TYPE ) ?>"> <?php _e('Display on pc/mobile', 'home-page-widget'); ?> </label>
                <br/>
                <select id="<?php echo $this->get_field_id( BLOCK_TYPE ) ?>" 
                        name="<?php echo $this->get_field_name( BLOCK_TYPE ) ?>">
                    <option value="desktop" <?php if ($block_type == 'desktop') echo 'selected'; ?>>Desktop</option>
                    <option value="mobile" <?php if ($block_type == 'mobile') echo 'selected'; ?>>Mobile</option>
                </select>
                <br/>
                <i><?php _e('Nếu kiểu hiện thị là Desktop thì sẽ hiển thị trên header bar, nếu là mobile sẽ hiển thị dưới header bar và chỉ hiển thị trên mobile', 'home-page-widget'); ?></i>
            </p>

        <?php }

        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            
            $instance[ BLOCK_TITLE ] = isset($new_instance[ BLOCK_TITLE ]) ? $new_instance[ BLOCK_TITLE ] : '';

            $instance[ BLOCK_CATSLUG ] = isset($new_instance[ BLOCK_CATSLUG ]) ? $new_instance[ BLOCK_CATSLUG ] : '';

            $instance[ BLOCK_TOTAL ] = isset($new_instance[ BLOCK_TOTAL ]) ? $new_instance[ BLOCK_TOTAL ] : '10';

            $instance[ BLOCK_IMAGE ] = isset($new_instance[ BLOCK_IMAGE ]) ? $new_instance[ BLOCK_IMAGE ] : '';

            $instance[ BLOCK_TYPE ] = isset( $new_instance[ BLOCK_TYPE ] ) ? $new_instance[ BLOCK_TYPE ] : 'desktop';
            
            return $instance;
        }

        
        public function widget($args, $instance) {
            $block_title = isset( $instance[ BLOCK_TITLE ] ) ? $instance[ BLOCK_TITLE ] : '';
            $block_catslug = isset( $instance[ BLOCK_CATSLUG ] ) ? $instance[ BLOCK_CATSLUG ] : '';
            $block_total = isset( $instance[ BLOCK_TOTAL ] ) ? $instance[ BLOCK_TOTAL ] : '';
            $block_image = isset( $instance[ BLOCK_IMAGE ] ) ? $instance[ BLOCK_IMAGE ] : '';
            $block_type = isset( $instance[ BLOCK_TYPE ] ) ? $instance[ BLOCK_TYPE ] : '';

            $id_name = $block_type == 'desktop' ? "dv-primetime-price-desktop" : "dv-primetime-price-mobile";
            $class_name = $block_type == 'desktop' ? "primetime-price-desktop" : "primetime-price-mobile";

            if ( function_exists( 'check_valid_cdn_hotdeal' ) ) {
                $valid_cdn =  check_valid_cdn_hotdeal();

                if ( $valid_cdn ) {
                    $block_image = str_replace( get_home_url(), $valid_cdn, $block_image );
                }
            }
        ?>
            <div class="<?php echo $class_name; ?>">
                <h2 class="widget-title"><?php _e($block_title, 'home-page-widget'); ?></h2>
                <div id="<?php echo $id_name; ?>"
                    data-cat_slug="<?php echo $block_catslug; ?>" 
                    data-total_products="<?php echo $block_total; ?>"
                    data-image_url="<?php echo $block_image; ?>"
                    data-display="<?php echo $block_type; ?>"></div>
            </div>
        <?php }
    }

}

?>
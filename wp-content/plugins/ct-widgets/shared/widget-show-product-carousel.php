<?php
/**
 * Custom columns of category with various options
 */
if ( ! class_exists( 'HC_Products_Carousel' ) ) {
    /**
     * Class for adding widget
     * @since 1.0.0
     */
    class HC_Products_Carousel extends WP_Widget {

        /*defaults values for fields*/
        private $thumb;

        private $defaults = array(
			'header_bg_image_url' => '',
			'header_bg_image_position' => 'left',
            'header_title' => '',
			'hc_widget_class' => '',
			'custom_css' => '',
			'bg_color' => '',
	        'wc_advanced_option' => 'recent',
            'hc_wc_product_cat' => -1,
            'hc_wc_product_tag' => -1,
            'post_number' => 5,
            'column_number' => 5,
            'display_type' => 'column',
	        'orderby' => 'date',
            'order' => 'DESC',
	        'view_all_option' => 'disable',
	        'all_link_text' => '',
	        'all_link_url' => '',
	        'enable_prev_next' => 1,
	        'hc_img_size' => 'shop_catalog',
			'categories_slug' => ''
        );

        function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'scripts'));
            parent::__construct(
                /*Base ID of your widget*/
                'hc_products_carousel',
                /*Widget name will appear in UI*/
                esc_html__('CT Product carousel', 'hangcu'),
                /*Widget description*/
                array( 'description' => esc_html__( 'Show WooCommerce Products with advanced options', 'hangcu' ), )
            );
        }

		public function scripts()
		{
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_media();
			wp_enqueue_script('media-select', plugins_url('../assets/js/media-select.js',__FILE__), array('jquery'));
			wp_enqueue_script('product-select', plugins_url('../assets/js/product-select.js',__FILE__), array('jquery'));
		}
        /*Widget Backend*/
        public function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, $this->defaults);

			// $hc_widget_title = esc_attr( $instance['hc_widget_title'] );
			// $hc_widget_description = esc_attr( $instance['hc_widget_description'] );
			// $hc_widget_class = esc_attr( $instance['hc_widget_class'] );
			// $hc_widget_icon_class = esc_attr( $instance['hc_widget_icon_class'] );

			$header_bg_image_url = ! empty( $instance['header_bg_image_url'] ) ? $instance['header_bg_image_url'] : '';
			$header_bg_image_position = esc_attr( $instance[ 'header_bg_image_position' ] );
			$header_title = esc_attr( $instance['header_title'] );
			$hc_widget_class = esc_attr( $instance['hc_widget_class'] );
			$custom_css = esc_attr( $instance['custom_css'] );
			$bg_color = esc_attr( $instance['bg_color'] );
	        $wc_advanced_option = esc_attr( $instance[ 'wc_advanced_option' ] );
	        $hc_wc_product_cat = esc_attr( $instance['hc_wc_product_cat'] );
	        $hc_wc_product_tag = esc_attr( $instance['hc_wc_product_tag'] );
	        $post_number = absint( $instance[ 'post_number' ] );
	        $column_number = absint( $instance[ 'column_number' ] );
	        $display_type = esc_attr( $instance[ 'display_type' ] );
	        $orderby = esc_attr( $instance[ 'orderby' ] );
	        $order = esc_attr( $instance[ 'order' ] );
	        $view_all_option = esc_attr( $instance[ 'view_all_option' ] );
	        $all_link_text =  $instance['all_link_text'];
	        $all_link_url = esc_url( $instance['all_link_url'] );
	        $enable_prev_next = esc_attr( $instance['enable_prev_next'] );
	        $hc_img_size = esc_attr( $instance['hc_img_size'] );
	        $carousel_auto_speed_period = isset($instance['carousel_autospeed']) ? esc_attr( $instance['carousel_autospeed'] ) : "";
			$categories_slug = esc_attr( $instance['categories_slug'] );

	        $choices = hc_get_image_sizes_options();

			echo "<p>Tiêu đề <input class='widefat' type='text' name='".$this->get_field_name('header_title')."' value='".$header_title."' /></p>";
			?>
            <p>
				<label for="<?php echo $this->get_field_id( 'header_bg_image_url' ); ?>"><?php _e( 'Backgroud image header:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'header_bg_image_url' ); ?>" name="<?php echo $this->get_field_name( 'header_bg_image_url' ); ?>" type="text"
						value="<?php echo esc_url( $header_bg_image_url ); ?>" />
				<button class="upload_image_button button button-primary">Upload Image</button>
			</p>
			<p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'header_bg_image_position' ) ); ?>"><?php esc_html_e( 'Background position', 'hangcu' ); ?></label>
                <select class="widefat at-wc-advanced-option" id="<?php echo esc_attr( $this->get_field_id( 'header_bg_image_position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'header_bg_image_position' ) ); ?>" >
			        <?php
			        $img_positions = [
						"left" => "Trái",
						"center" => "Giữa",
						"right" => "Phải"
					];
			        foreach ( $img_positions as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $header_bg_image_position ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>


			<?php
				echo "<p>Class name <input class='widefat' type='text' name='".$this->get_field_name('hc_widget_class')."' value='".$hc_widget_class."' /></p>";
				echo "<p>Custom css <textarea class='widefat' type='text' name='".$this->get_field_name('custom_css')."'>" .$custom_css. "</textarea></p>";
				echo "<p>Background Color <input class='widefat' type='text' name='".$this->get_field_name('bg_color')."' value='".$bg_color."' /></p>";
			?>

			<p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'wc_advanced_option' ) ); ?>"><?php esc_html_e( 'Show', 'hangcu' ); ?></label>
                <select class="widefat at-wc-advanced-option" id="<?php echo esc_attr( $this->get_field_id( 'wc_advanced_option' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wc_advanced_option' ) ); ?>" >
			        <?php
			        $wc_advanced_options = hc_wc_advanced_options();
			        foreach ( $wc_advanced_options as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $wc_advanced_option ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>

            <p class="wc-product-cat wc-select">
                <label for="<?php echo esc_attr( $this->get_field_id('hc_wc_product_cat') ); ?>">
                    <?php esc_html_e('Select Category', 'hangcu'); ?>
                </label>
                <?php
                $hc_dropown_cat = array(
                    'show_option_none'   => false,
                    'orderby'            => 'name',
                    'order'              => 'asc',
                    'show_count'         => 1,
                    'hide_empty'         => 1,
                    'echo'               => 1,
                    'selected'           => $hc_wc_product_cat,
                    'hierarchical'       => 1,
                    'name'               => $this->get_field_name('hc_wc_product_cat'),
                    'id'                 => $this->get_field_name('hc_wc_product_cat'),
                    'class'              => 'widefat',
                    'taxonomy'           => 'product_cat',
                    'hide_if_empty'      => false
                );
                wp_dropdown_categories( $hc_dropown_cat );
                ?>
            </p>
            <p class="wc-product-tag wc-select">
                <label for="<?php echo esc_attr( $this->get_field_id('hc_wc_product_tag') ); ?>">
			        <?php esc_html_e('Select Tag', 'hangcu'); ?>
                </label>
		        <?php
		        $hc_dropown_cat = array(
			        'show_option_none'   => false,
			        'orderby'            => 'name',
			        'order'              => 'asc',
			        'show_count'         => 1,
			        'hide_empty'         => 1,
			        'echo'               => 1,
			        'selected'           => $hc_wc_product_tag,
			        'hierarchical'       => 1,
			        'name'               => $this->get_field_name('hc_wc_product_tag'),
			        'id'                 => $this->get_field_name('hc_wc_product_tag'),
			        'class'              => 'widefat',
			        'taxonomy'           => 'product_tag',
			        'hide_if_empty'      => false
		        );
		        wp_dropdown_categories( $hc_dropown_cat );
		        ?>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>">
			        <?php esc_html_e( 'Number of posts to show', 'hangcu' ); ?>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="number" value="<?php echo $post_number; ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'column_number' ) ); ?>"><?php esc_html_e( 'Column Number', 'hangcu' ); ?>:</label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column_number' ) ); ?>" >
			        <?php
			        $hc_widget_column_numbers = hc_widget_column_number();
			        foreach ( $hc_widget_column_numbers as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $column_number ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>
			<p>
				<label>Danh mục sản phẩm (slug) muốn hiển thị (phân cách bằng dấu phẩy)</label>
				<input class='widefat' type='text' name='<?= $this->get_field_name('categories_slug') ?>' value='<?= $categories_slug ?>' />
			</p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>">
                    <?php esc_html_e( 'Display Type', 'hangcu' ); ?>
                </label>
                <select class="widefat at-display-select" id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>" >
			        <?php
			        $hc_widget_display_types = hc_widget_display_type();
			        foreach ( $hc_widget_display_types as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $display_type ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
			        <?php esc_html_e( 'Order by', 'hangcu' ); ?>
                </label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" >
			        <?php
			        $hc_wc_product_orderby = hc_wc_product_orderby();
			        foreach ( $hc_wc_product_orderby as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $orderby ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
			        <?php esc_html_e( 'Order by', 'hangcu' ); ?>
                </label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" >
			        <?php
			        $hc_post_order = hc_post_order();
			        foreach ( $hc_post_order as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $order ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>
            <hr /><!--view all link separate-->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'view_all_option' ) ); ?>">
			        <?php esc_html_e( 'View all options', 'hangcu' ); ?>
                </label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_all_option' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_option' ) ); ?>" >
			        <?php
			        $hc_adv_link_options = hc_adv_link_options();
			        foreach ( $hc_adv_link_options as $key => $value ){
				        ?>
                        <option value="<?php echo esc_attr( $key )?>" <?php selected( $key, $view_all_option ); ?>><?php echo esc_attr( $value );?></option>
				        <?php
			        }
			        ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'all_link_text' ) ); ?>">
			        <?php esc_html_e( 'All Link Text', 'hangcu' ); ?>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'all_link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'all_link_text' ) ); ?>" type="text" value="<?php echo $all_link_text; ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'all_link_url' ) ); ?>">
			        <?php esc_html_e( 'All Link Url', 'hangcu' ); ?>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'all_link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'all_link_url' ) ); ?>" type="text" value="<?php echo $all_link_url; ?>" />
            </p>
            <hr />

            <p class="at-enable-prev-next">
                <input id="<?php echo esc_attr( $this->get_field_id( 'enable_prev_next' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'enable_prev_next' ) ); ?>" type="checkbox" <?php checked( 1 == $enable_prev_next ? $instance['enable_prev_next'] : 0); ?> />
                <label for="<?php echo esc_attr( $this->get_field_id( 'enable_prev_next' ) ); ?>"><?php esc_html_e( 'Enable Prev - Next on Carousel Column', 'hangcu' ); ?></label>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hc_img_size' ) ); ?>">
			        <?php esc_html_e( 'Normal Featured Post Image', 'hangcu' ); ?>
                </label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hc_img_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hc_img_size' ) ); ?>">
			        <?php
			        foreach( $choices as $key => $hc_column_array ){
				        echo ' <option value="'.esc_attr( $key ).'" '.selected( $hc_img_size, $key, 0). '>'.esc_attr( $hc_column_array ) .'</option>';
			        }
			        ?>
                </select>
            </p>

            <p class="at-carousel-autospeed">
                <label for="<?php echo esc_attr( $this->get_field_id( 'carousel_autospeed' ) ); ?>"><?php esc_html_e( 'Carousel Auto Speed', 'hangcu' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'carousel_autospeed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel_autospeed' ) ); ?>" type="text" value="<?php echo !empty( $carousel_auto_speed_period ) ? $carousel_auto_speed_period : 5000; ?>" />
            </p>
            <p>
                <small><?php esc_html_e( 'Note: Some of the features only work in "Home main content area" due to minimum width in other areas.' ,'hangcu'); ?></small>
            </p>
            <?php
        }

        /**
         * Function to Updating widget replacing old instances with new
         *
         * @access public
         * @since 1.0.0
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         * @return array
         *
         */
        public function update( $new_instance, $old_instance ) {
			$instance = array();
			// $instance[ 'hc_widget_title' ] = ( isset( $new_instance['hc_widget_title'] ) ) ? sanitize_text_field( $new_instance['hc_widget_title'] ) : '';
			// $instance[ 'hc_widget_description' ] = ( isset( $new_instance['hc_widget_description'] ) ) ? sanitize_text_field( $new_instance['hc_widget_description'] ) : '';
			// $instance[ 'hc_widget_class' ] = ( isset( $new_instance['hc_widget_class'] ) ) ? sanitize_text_field( $new_instance['hc_widget_class'] ) : '';
			// $instance[ 'hc_widget_icon_class' ] = ( isset( $new_instance['hc_widget_icon_class'] ) ) ? sanitize_text_field( $new_instance['hc_widget_icon_class'] ) : '';

			$instance[ 'header_bg_image_url' ] = ( isset( $new_instance['header_bg_image_url'] ) ) ? sanitize_text_field( $new_instance['header_bg_image_url'] ) : '';
			$instance[ 'header_bg_image_position' ] = hc_sanitize_choice_options( $new_instance[ 'header_bg_image_position' ], [
				"left" => "Trái",
				"center" => "Giữa",
				"right" => "Phải"
			], 'left' );

			$instance[ 'header_title' ] = ( isset( $new_instance['header_title'] ) ) ? sanitize_text_field( $new_instance['header_title'] ) : '';
			$instance[ 'hc_widget_class' ] = ( isset( $new_instance['hc_widget_class'] ) ) ? sanitize_text_field( $new_instance['hc_widget_class'] ) : '';
			$instance['custom_css'] = strip_tags($new_instance['custom_css']);
			$instance['bg_color'] = strip_tags($new_instance['bg_color']);
			$instance['categories_slug'] = strip_tags($new_instance['categories_slug']);

	        $wc_advanced_options = hc_wc_advanced_options();
	        $instance[ 'wc_advanced_option' ] = hc_sanitize_choice_options( $new_instance[ 'wc_advanced_option' ], $wc_advanced_options, 'recent' );


	        $instance[ 'hc_wc_product_cat' ] = ( isset( $new_instance['hc_wc_product_cat'] ) ) ? absint( $new_instance['hc_wc_product_cat'] ) : '';
	        $instance[ 'hc_wc_product_tag' ] = ( isset( $new_instance['hc_wc_product_tag'] ) ) ? absint( $new_instance['hc_wc_product_tag'] ) : '';
	        $instance[ 'post_number' ] = absint( $new_instance[ 'post_number' ] );
	        $instance[ 'column_number' ] = absint( $new_instance[ 'column_number' ] );

	        $hc_widget_display_types = hc_widget_display_type();
	        $instance[ 'display_type' ] = hc_sanitize_choice_options( $new_instance[ 'display_type' ], $hc_widget_display_types, 'column' );

	        $hc_wc_product_orderby = hc_wc_product_orderby();
	        $instance[ 'orderby' ] = hc_sanitize_choice_options( $new_instance[ 'orderby' ], $hc_wc_product_orderby, 'date' );

	        $hc_post_order = hc_post_order();
	        $instance[ 'order' ] = hc_sanitize_choice_options( $new_instance[ 'order' ], $hc_post_order, 'DESC' );

	        $hc_link_options = hc_adv_link_options();
	        $instance[ 'view_all_option' ] = hc_sanitize_choice_options( $new_instance[ 'view_all_option' ], $hc_link_options, 'disable' );

	        $instance[ 'all_link_text' ] = $new_instance[ 'all_link_text' ];
	        $instance[ 'all_link_url' ] = esc_url_raw( $new_instance[ 'all_link_url' ] );
	        $instance[ 'enable_prev_next' ] = isset($new_instance['enable_prev_next'])? 1 : 0;
	        $instance['carousel_autospeed'] = isset( $new_instance['carousel_autospeed'] ) ? absint( $new_instance['carousel_autospeed'] ) : 5000;

	        $hc_img_size = hc_get_image_sizes_options();
	        $instance[ 'hc_img_size' ] = hc_sanitize_choice_options( $new_instance[ 'hc_img_size' ], $hc_img_size, 'large' );

            return $instance;
        }

        function single_product_archive_thumbnail_size(){
            return $this->thumb;
        }

        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         * @access public
         * @since 1.0.0
         *
         * @param array $args widget setting
         * @param array $instance saved values
         * @return void
         *
         */
        public function widget($args, $instance) {
            $instance = wp_parse_args( (array) $instance, $this->defaults);
	        $wc_advanced_option = esc_attr( $instance[ 'wc_advanced_option' ] );
	        $hc_wc_product_cat = esc_attr( $instance['hc_wc_product_cat'] );
	        $hc_wc_product_tag = esc_attr( $instance['hc_wc_product_tag'] );
	        $hc_widget_title = !empty( $instance['hc_widget_title'] ) ? esc_attr( $instance['hc_widget_title'] ) : '';
			$hc_widget_title = apply_filters( 'widget_title', $hc_widget_title, $instance, $this->id_base );
			$hc_widget_description = !empty( $instance['hc_widget_description'] ) ? esc_attr( $instance['hc_widget_description'] ) : '';
	        $post_number = absint( $instance[ 'post_number' ] );
	        $column_number = absint( $instance[ 'column_number' ] );
	        $display_type = esc_attr( $instance[ 'display_type' ] );
	        $orderby = esc_attr( $instance[ 'orderby' ] );
	        $order = esc_attr( $instance[ 'order' ] );
	        $view_all_option = esc_attr( $instance[ 'view_all_option' ] );
	        $all_link_text = $instance[ 'all_link_text' ];
	        $all_link_url = esc_url( $instance[ 'all_link_url' ] );
	        $enable_prev_next = esc_attr( $instance['enable_prev_next'] );
	        $carousel_auto_speed = isset($instance['carousel_autospeed']) ? absint( $instance['carousel_autospeed'] ) : 3000;
	        $this->thumb = $hc_img_size = esc_attr( $instance['hc_img_size'] );

			$header_title = esc_attr( $instance['header_title'] );
			$hc_widget_class = esc_attr( $instance['hc_widget_class'] );
			$custom_css = esc_attr( $instance['custom_css'] );
			$bg_color = esc_attr( $instance['bg_color'] );
			$header_bg_image_url = ! empty( $instance['header_bg_image_url'] ) ? $instance['header_bg_image_url'] : '';
			$header_bg_image_position = esc_attr( $instance[ 'header_bg_image_position' ] );
			$categories_slug = esc_attr( $instance['categories_slug'] );
	        $product_visibility_term_ids = wc_get_product_visibility_term_ids();

	        /**
             * Filter the arguments for the Recent Posts widget.
             *
             * @since 1.0.0
             *
             * @see WP_Query
             *
             */
	        $query_args = array(
		        'posts_per_page' => $post_number,
		        'post_status'    => 'publish',
		        'post_type'      => 'product',
		        'no_found_rows'  => 1,
		        'order'          => $order,
		        'meta_query' => array(
					array('relation' => 'AND'),
					// array(
					// 	'key'     => '_stock_status',
					// 	'value'   => 'outofstock',
					// 	'compare' => '!=',
					// ),
					// array(
					// 	'key'     => 'stop_selling',
					// 	'value'   => '0',
					// 	'compare' => '=',
					// )
			 	),
		        'tax_query'      => array(
			        'relation' => 'AND',
		        ),
	        );

	        switch ( $wc_advanced_option ) {

		        case 'featured' :
		            if( !empty( $product_visibility_term_ids['featured'] )){
			            $query_args['tax_query'][] = array(
				            'taxonomy' => 'product_visibility',
				            'field'    => 'term_taxonomy_id',
				            'terms'    => $product_visibility_term_ids['featured'],
			            );
                    }

			        break;

		        case 'onsale' :
			        $product_ids_on_sale    = wc_get_product_ids_on_sale();
			        if( !empty( $product_ids_on_sale ) ){
			            $query_args['post__in'] = $product_ids_on_sale;
                    }
			        break;

		        case 'cat' :
		            if( !empty( $hc_wc_product_cat )){
			            $query_args['tax_query'][] = array(
				            'taxonomy' => 'product_cat',
				            'field'    => 'term_id',
				            'terms'    => $hc_wc_product_cat,
			            );
                    }

			        break;

		        case 'tag' :
		            if( !empty( $hc_wc_product_tag )){
			            $query_args['tax_query'][] = array(
				            'taxonomy' => 'product_tag',
				            'field'    => 'term_id',
				            'terms'    => $hc_wc_product_tag,
			            );
                    }

			        break;
	        }

	        switch ( $orderby ) {

		        case 'price' :
			        $query_args['meta_key'] = '_price';
			        $query_args['orderby']  = 'meta_value_num';
			        break;

		        case 'sales' :
			        $query_args['meta_key'] = 'total_sales';
			        $query_args['orderby']  = 'meta_value_num';
			        break;

		        case 'ID' :
		        case 'author' :
		        case 'title' :
		        case 'date' :
		        case 'modified' :
		        case 'rand' :
		        case 'comment_count' :
		        case 'menu_order' :
		            $query_args['orderby']  = $orderby;
			        break;

		        default :
			        $query_args['orderby']  = 'date';
	        }

            $hc_featured_query = new WP_Query( $query_args );
			if( !empty( $bg_color ) ) {
				$custom_css = 'background-color: ' . $bg_color . ';' . $custom_css;
			}

			ob_start(); ?>
			<div class="header-links">
				<?php
					if( !empty( $categories_slug ) ) {
						$cats_slug = explode( ',', $categories_slug );
						for( $i = 0; $i < count( $cats_slug ); $i++ ) :
							$catObj = $category = get_term_by( 'slug', trim($cats_slug[$i]), 'product_cat' );
							$catName = $catObj->name;
							$catLink = get_category_link($catObj);
							if( !empty( $catLink ) && !empty( $catName ) ) : ?>
								<a href="<?= $catLink ?>"><?= $catName ?></a>
							<?php endif;
						endfor;
					}
				?>

				<?php if( !empty( $all_link_url ) && !empty( $all_link_text ) ) : ?>
					<a href="<?= $all_link_url ?>"><?= $all_link_text ?></a>
				<?php endif; ?>
			</div>

			<?php
			$output_header = ob_get_contents();
			ob_end_clean();

            if ($hc_featured_query->have_posts()) : ?>
				<asside id='widget_hangcu_list_product' class="<?= $hc_widget_class ?>" style="<?= $custom_css ?>">
					<div class="list_product">
						<?php if( !empty( $header_bg_image_url ) ) : ?>
							<div class="lst-product-header has-bg"
								style=" background: url( '<?= $header_bg_image_url ?>' ); background-position: <?= $header_bg_image_position ?>; background-repeat: no-repeat; "
							>
								<?= $output_header ?>
							</div>
						<?php else : ?>
							<div class="lst-product-header">
								<h3><?= $header_title ?></h3>
								<?= $output_header ?>
							</div>
						<?php endif; ?>

						<div class="lst-product-body <?php
							if( !empty($bg_color) ) echo ' has-bg';
							if( $display_type == 'carousel' ) echo ' owl-carousel';
							elseif( $display_type == 'scroll' ) echo ' vertical-scroll';
							else echo ' no-carousel';
						?>">
						<?php
							$hc_list_classes = 'single-list';
							if( 1 == $column_number ){
								$hc_list_classes .= " acme-col-1";
							}
							elseif( 2 == $column_number ){
								$hc_list_classes .= " acme-col-2";
							}
							elseif( 3 == $column_number ){
								$hc_list_classes .= " acme-col-3";
							}
							elseif( 4 == $column_number ){
								$hc_list_classes .= " acme-col-4";
							}
							else{
								$hc_list_classes .= " acme-col-5";
							}
							ob_start();
							$hc_featured_index = 1;
							while ( $hc_featured_query->have_posts() ) :$hc_featured_query->the_post();

								if( 'carousel' != $display_type ){
									// if( 1 != $hc_featured_index && $hc_featured_index % $column_number == 1 ){
									// 	echo "<!--<div class='clearfix'></div>-->";
									// }
								}
								?>
								<div class=" <?php echo esc_attr( $hc_list_classes ); ?>">
									<ul class="post-container products">
										<?php
											/*single_product_archive_thumbnail_size*/
											add_filter( 'single_product_archive_thumbnail_size', array( $this, 'single_product_archive_thumbnail_size' ) );

											wc_get_template_part( 'content', 'product' );

											remove_filter( 'single_product_archive_thumbnail_size', array( $this, 'single_product_archive_thumbnail_size' ) );
										?>
									</ul><!--.post-container-->
								</div><!--dynamic css-->
								<?php
								// $hc_featured_index++;
							endwhile;
							$output = ob_get_contents();
							ob_end_clean();
							echo $output;

							// $args['section_args']['products_html'] = $output ;

							// electro_products_carousel( $args['section_args'], $args['carousel_args'] );

						?>
						</div>
					</div>
				</asside>

			<?php
			endif;
	        wp_reset_postdata();
        }
    } // Class HC_Products_Carousel ends here
}
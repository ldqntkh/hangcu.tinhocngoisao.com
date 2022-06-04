<?php
	if ( !function_exists('hangcu_product_storage') ) {
		function hangcu_product_storage() {
			// checking product availabel at location
			// echo '<div class="product-storage"></div>';
		}
	}

	if ( !function_exists('hangcu_product_policy') ) {
		function hangcu_product_policy() {
			global $product;
			// check policy of category
			// $product_cats_ids = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
			// var_dump( $product_cats_ids );
			$cats = get_the_terms( $product->ID, 'product_cat' );
			$cat_policy = '';
			foreach ($cats as $cat) {
				if($cat->parent == 0){
					$cat_policy = get_field('cat_product_policy', $cat);
				}
			}
			// get content field product_policy
			$product_policy = get_field('product_policy', $product->get_ID());
			if ( $product_policy ) {
				$display_cat_policy = get_field('display_cat_policy', $product->get_ID(), true);
				echo '<div class="product-policy">';
				if( $display_cat_policy ) {
					echo $cat_policy;
				}
				
				echo $product_policy;
				echo '</div>';
			} else if ( $cat_policy ) {
				echo '<div class="product-policy">';
				echo $cat_policy;
				echo '</div>';
			}
		}
	}

	if ( !function_exists('hangcu_product_old') ) {
		function hangcu_product_old() {
			global $product;
			// get content field product_old
			$product_id_old = get_field('product_old', $product->get_ID());
			if ( $product_id_old ) {
				$product_old = wc_get_product( $product_id_old );
				if ( $product_old ) { ?>
					<div class="product-old">
						
						<div>
							<a href="<?php echo get_permalink( $product_id_old ) ?>">
								<?php echo __("Xem thêm sản phẩm ", 'hangcu'); echo $product_old->get_name(); ?>
							</a>
							<p>
								<?php echo __("Giá chỉ từ: ") ?>
								<strong><?php echo wc_price($product_old->get_price()) ?></strong>
							</p>
						</div>
						<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product_id_old ), 'medium', true )[0] ?>" />
					</div>
				<?php }
			}
		}
	}

	/**
	 * Single product add to cart
	 */
	if ( !function_exists('hangcu_after_add_to_cart_form') ) {
		function hangcu_after_add_to_cart_form() {
			global $product;
			$stop_selling = get_field('stop_selling', $product->get_ID());
			$enable_tra_gop = get_field('cho_phep_tra_gop', $product->get_ID());
			$enable_tra_gop = false;
			if ( $enable_tra_gop && !$stop_selling ) {
				$dich_vu_tra_gop = get_field('dich_vu_tra_gop', $product->get_ID());
				if ( $dich_vu_tra_gop && count($dich_vu_tra_gop) > 0 ) {
					echo '<div class="installment-payment">';
					// TODO: gắn thêm link đi đến trang sản phẩm trả góp
					foreach ( $dich_vu_tra_gop as $item ) {
						$arr_items = explode( ':', $item );
						echo '<a href="' . home_url('tragopsanpham/') . $product->get_ID() . '"><b>'.$arr_items[2].'</b><br/><small>'.$arr_items[3].'</small></a>';
					}
					echo '</div>';
				}
			}
		}
	}

	/**
	 * Single product add to cart compare
	 */
	if ( !function_exists('hangcu_after_add_to_cart_form_compare') ) {
		function hangcu_after_add_to_cart_form_compare($product) {
			$stop_selling = get_field('stop_selling', $product->get_ID());
			$enable_tra_gop = get_field('cho_phep_tra_gop', $product->get_ID());
			$enable_tra_gop = false;
			if ( $enable_tra_gop && !$stop_selling ) {
				$dich_vu_tra_gop = get_field('dich_vu_tra_gop', $product->get_ID());
				if ( count($dich_vu_tra_gop) > 0 ) {
					echo '<div class="installment-payment">';
					// TODO: gắn thêm link đi đến trang sản phẩm trả góp
					foreach ( $dich_vu_tra_gop as $item ) {
						$arr_items = explode( ':', $item );
						echo '<a href="' . home_url('tragopsanpham/') . $product->get_ID() . '"><b>'.$arr_items[2].'</b><br/><small>'.$arr_items[3].'</small></a>';
					}
					echo '</div>';
				}
			}
		}
	}

	/**
	 * Display installment
	 */
	if ( !function_exists('hangcu_after_price_display_installment') ) {
		function hangcu_after_price_display_installment() {
			global $product;
			$stop_selling = get_field('stop_selling', $product->get_id());
			$enable_tra_gop = get_field('cho_phep_tra_gop', $product->get_ID());
			$enable_tra_gop = false;
			if ( !$stop_selling && $enable_tra_gop ) {
				echo '<label class="installment">'.__('Trả góp 0%', 'hangcu').'</label>';
			}
		}
	}

	/**
	 * display product right content
	 */
	if ( !function_exists('hangcu_output_product_right_content') ) {
		function hangcu_output_product_right_content() {
			echo '<div class="product-right-content">';
			hangcu_product_short_specifications();
			echo '</div>';
		}
	}

	/**
	 * display product accessories
	 */
	if ( !function_exists('hangcu_output_product_accessories') ) {
		function hangcu_output_product_accessories() {
			$tabs = apply_filters( 'woocommerce_product_tabs', array() );
			if (isset( $tabs['accessories'] )) {
				$accessories = $tabs['accessories'];
				if ($accessories) {
					if ( isset( $accessories['callback'] ) ) { call_user_func( $accessories['callback'], 'accessories', $accessories ); }
				}
			}
			
		}
	}

	if ( !function_exists('show_compare_product_button') ) {
		function show_compare_product_button() {
			if (is_plugin_active('hangcu-compare-products/hangcu-compare-products.php' )) {
				global $product;
				include_once WP_PLUGIN_DIR. '/hangcu-compare-products/product_compare/api/productType.php';
				$product_type_id = ProductTypeApi::getGroupProductMappingByProductId($product->get_id());
				if ($product_type_id != null) { 
					$GLOBALS['product_type_id'] = $product_type_id;    
				?>
					<!-- <div class="col-6">
						<a href="#compare-products">
							<button type="button" class="btn btn-dark btn-lg btn-block mt-2">
								<p><?php echo __('So sánh ', 'hangcu') ?></p>
								<p><?php echo __('sản phẩm khác', 'hangcu') ?></p>
							</button>
						</a>
					</div> -->
					<script>
						const adminAjaxCompare = '<?php echo admin_url('admin-ajax.php'); ?>';
					</script>
				<?php }
			}
		}
	}

	if( !function_exists('show_add_to_cart_form') ) {
		function show_add_to_cart_form() {
			global $product;

			$is_show = true;

			if ($product->is_type('variable') || $product->is_type('woosb')) {
				$is_show = false;
			}
			$stop_selling = get_field('stop_selling', $product->get_id());
			if ( !$stop_selling  && $product->get_price() > 0 && $is_show ) : ?>
				<hr/>
				<div class="bottom_order">
					<form id="cart-bottom" class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
						<div class="pr-details">
							<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'medium', true )[0] ?>" alt="" />
							<div>
								<h4><?php echo $product->get_name() ?></h4>
								<p class="price">
									<?php 
										$price =  $product->get_price_html(); 
										if (strpos($price, 'del')) {
											$price = str_replace( '<del>', '<del><span class="price-label">Giá niêm yết: </span>', $price );
									
											$percent = floor(intval($product->get_sale_price())/intval($product->get_regular_price())*100);
									
											$price = str_replace( '</del>', '<strong>(-'. strval(100 - $percent) .'%)</strong></del>', $price );
											echo $price;
										} else  {
											echo $price;
										}
									
									?>
								</p>
							</div>
						</div>
						<?php
						woocommerce_quantity_input( array(
							'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
							'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
							'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
						) );
						?>
						<div class="group-btns">
							<button <?php if ( $product->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
								<b><?php echo esc_html( $product->single_add_to_cart_text() ); ?></b><br/>
								<span><?php echo __('Giao tận nơi hoặc nhận tại siêu thị', 'hangcu') ?></span>
							</button>
							<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
						</div>
					</form>
				</div>
				<hr />
			<?php else : echo '<hr />'; endif;
		}
	}

	function hangcu_after_setup_theme() {
		remove_action( 'woocommerce_after_single_product_summary',     'woocommerce_upsell_display', 15);
		remove_action( 'woocommerce_after_single_product_summary',     'electro_output_related_products', 20);
		remove_action( 'electro_shop_control_bar', 'electro_shop_view_switcher', 10 );
		add_action( 'electro_shop_control_bar', 'electro_shop_view_switcher', 30 );
		remove_action( 'electro_footer_v2',           'electro_footer_v2_handheld',            60 );
		remove_action( 'electro_page', 			'electro_page_header',		    10 );
		remove_action( 'electro_header_icons', 'electro_header_mini_cart_icon', 90 );
		remove_theme_support( 'wc-product-gallery-lightbox' );
		remove_action( 'woocommerce_review_after_comment_text',        'electro_wc_review_meta',                       10 );
	}

	function clear_session_after_add_to_cart() { ?>
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		</script>
	<?php }

	function display_product_tab_compare() {
		if (is_plugin_active('hangcu-compare-products/hangcu-compare-products.php' )) {
			wc_get_template( 'single-product/compare-product/compare-product-items.php' );
		}
	}

	/**
	 * variant product
	 */
	function shuffle_variable_product_elements(){
		if ( is_product() ) {
			global $product;
			if ($product->is_type( 'woosb' )) {
				remove_action( 'hangcu_single_product_summary',           'woocommerce_template_single_price',            5 );
				add_action( 'hangcu_single_product_summary', 'woocommerce_grouped_product_price', 5 );
				function woocommerce_grouped_product_price() {
					echo '<p class="price">
							<span class="electro-price">
								<span class="woosb-total"></span>
							</span>
						</p>';
				}
			}
		}
	}

	if ( !function_exists( 'hangcu_after_add_to_cart_display_configuration' ) ) {
		function hangcu_after_add_to_cart_display_configuration() {
			global $product;
			$nhom_cau_hinh = get_field('nhom_cau_hinh', $product->get_id());

			if ( !empty( $nhom_cau_hinh ) ) {
				// for now only support display 10 products
				$loop = 0;
				ob_start();
				for( $i = 1; $i < 10; $i++ ) {
					$product_id = $nhom_cau_hinh['id_product_'.$i];
					if ( !$product_id ) continue;

					$stop_selling = get_field('stop_selling', $product_id);
					if ( $stop_selling ) continue;
					
					$_product = wc_get_product( $product_id );
					if ( !empty( $_product ) && $_product->is_type( 'simple' ) && $_product->get_manage_stock() && $_product->get_stock_quantity() > 0 ) {
						$loop++;
						$product_name = $nhom_cau_hinh['label_product_'.$i];
						if ( empty( $product_name ) ) $product_name = $_product->get_name();
						$product_price = $_product->get_price_html();
						$url = get_permalink( $product_id ); ?>

						<a class="product-confi-item" href="<?php echo $url ?>">
							<h3><?php echo $product_name ?></h3>
							<?php echo $product_price ?>
						</a>
					<?php }
				}

				$content = ob_get_contents();
				ob_clean();
				ob_end_flush();
				if ( $loop > 0 ) { ?>
				
					<div class="product-configuation <?php if ( $loop > 2 ) echo 'less-content' ?>">
						<h3><?php printf("Có %u cấu hình tùy chọn.", $loop ); ?></h3>
						<?php 
							echo $content; 
							if ( $loop > 2 ) {
								echo '<button class="btn-show-more-content un-active"><span>' . __('Xem thêm', 'hangcu') . '</span><i class="fas fa-sort-down"></i></button>';
							}
						?>

					</div>
				
				<?php }
				
			}
			
		}
	}

	add_filter( 'woocommerce_output_related_products_args', function( $args ) {
		$args['columns'] 		= 4;
		$args['posts_per_page'] = 8;
	
		return $args;
	}, 100 );

	/**
	 * product review name
	 */
	if ( ! function_exists( 'hc_wc_review_meta' ) ) {
		/**
		 *
		 */
		function hc_wc_review_meta( $comment ) {
	
			if ( $comment->comment_approved == '0' ) : ?>
	
				<!-- <p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'electro' ); ?></em></p> -->
	
			<?php else : ?>
	
				
	
			<?php endif; ?>
				<p class="meta">
					<strong><?php comment_author(); ?></strong> <?php
					// 	if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
					// 		if ( isset( $verified ) && $verified )
					// 			echo '<em class="verified">(' . __( 'verified owner', 'electro' ) . ')</em> ';
					// ?>
					<!-- &ndash;  -->
					<time datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></time>
				</p>
			<?php
		}
	}

	/**
	 * product review approve
	 */
	if( !function_exists('hc_wc_review_meta_approve') ) {
		function hc_wc_review_meta_approve( $comment ) {
	
			if ( $comment->comment_approved == '0' ) : ?>
				<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'electro' ); ?></em></p>
			<?php endif;
		}
	}


	// define the pre_get_comments callback 
function action_pre_get_comments( $array ) { 
    // make action magic happen here... 
	$array->query_vars['order'] = 'DESC';
	return $array;
}; 
         
// add the action 
add_action( 'pre_get_comments', 'action_pre_get_comments', 10, 1 ); 

add_action('wp_ajax_nopriv_load_review_form', 'load_template_review_form' );
add_action('wp_ajax_load_review_form', 'load_template_review_form' );
function load_template_review_form() {
    $product_id = $_POST['product_id'];
    global $product;
    $product = wc_get_product($product_id);
    if( empty($product) ) {
        wp_send_json_success();
        die;
    }
    ob_start();
    include_once(get_stylesheet_directory() . '/templates/shop/review-form.php');
    $output = ob_get_contents();
    ob_end_clean(); 
    
    wp_send_json_success($output);
    die;
}

add_action('wp_ajax_nopriv_load_review_items', 'load_template_review_items' );
add_action('wp_ajax_load_review_items', 'load_template_review_items' );
function load_template_review_items() {
    $product_id = $_POST['product_id'];
    $page_comment = isset($_POST['page_comment']) ? $_POST['page_comment'] : 1;
    global $product;
    $product = wc_get_product($product_id);
    if( empty($product) ) {
        wp_send_json_success();
        die;
    }
    ob_start();
    include_once(get_stylesheet_directory() . '/templates/shop/review-item.php');
    $output = ob_get_contents();
    ob_end_clean(); 
    
    wp_send_json_success($output);
    die;
}

add_action('wp_ajax_nopriv_load_template_review_product', 'load_template_review_product' );
add_action('wp_ajax_load_template_review_product', 'load_template_review_product' );
if( !function_exists('load_template_review_product') ) {
	function load_template_review_product() {
		$product_id = $_POST['product_id'];
		$page_comment = isset($_POST['page_comment']) ? $_POST['page_comment'] : 1;
		global $product;
		$product = wc_get_product($product_id);

		if( empty($product) ) {
			wp_send_json_success();
			die;
		}

		ob_start();
		include_once(get_stylesheet_directory() . '/templates/shop/review-form.php');
		$output_review_form = ob_get_contents();
		ob_end_clean(); 

		ob_start();
		include_once(get_stylesheet_directory() . '/templates/shop/review-item.php');
		$output_review_item = ob_get_contents();
		ob_end_clean();

		wp_send_json_success([
			'output_review_form' => $output_review_form,
			'output_review_item' => $output_review_item
		]);
    	die;
	}
}

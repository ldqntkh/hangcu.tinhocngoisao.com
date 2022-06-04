<?php
/**
 * Template functions used for pages.
 *
 * @package electro
 */

if ( ! function_exists( 'hangcu_electro_page_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 * @since 1.0.0
	 * @return void
	 */
	function hangcu_electro_page_header() {
    if(is_account_page()){
      return null;
    }

		global $post;
		$page_meta_values = get_post_meta( $post->ID, '_electro_page_metabox', true );
		
		if ( isset( $page_meta_values['page_title'] ) && ! empty( $page_meta_values['page_title'] ) ) {
			$page_title = $page_meta_values['page_title'];
		} else {
			$page_title = get_the_title();
		}


		if( apply_filters( 'electro_show_page_header', true ) ) {
			$header_image_url = electro_get_page_header_image();
			if( $header_image_url != '' ) {
				?>
				<header class="entry-header header-with-cover-image" style="background-image: url(<?php echo esc_url( $header_image_url ) ?>);">
					<div class="caption">
						<h1 class="entry-title"><?php echo apply_filters( 'electro_page_title', wp_kses_post( $page_title ) ); ?></h1>
						<?php electro_page_subtitle(); ?>
					</div>
				</header><!-- .entry-header -->
				<?php
			} else {
				?>
				<header class="entry-header">
					<h1 class="entry-title"><?php echo apply_filters( 'electro_page_title', wp_kses_post( $page_title ) ); ?></h1>
					<?php electro_page_subtitle(); ?>
				</header><!-- .entry-header -->
				<?php
			}
		}
	}
}

function hangcu_myaccount_remove_download_item($items) {
	unset($items['downloads']);

  return $items;
}

function hangcu_alter_checkout_fields($fields) {

	$fields['billing']['billing_phone']['placeholder'] = 'Số điện thoại';
	$fields['billing']['billing_email']['placeholder'] = 'Địa chỉ email';

	return $fields;
}

function hangcu_update_order_action ( $actions, $order ) {
	$view = $actions['view'];

	unset( $actions['pay'] );
	
	if ( !empty( $view ) ) {
		unset( $actions['view'] );
		$actions = array( 'view' =>  $view ) + $actions;
	}

	// check hủy đơn hàng, chỉ áp dụng cho COD
	if ( $order->get_payment_method() == 'cod' && $order->get_status() == 'processing') {
		
		$actions["pending-cancel"] = array(
			'url'  => "#pending-cancel_" . $order->get_id(),
			'name' => __( 'Hủy đơn hàng', 'hangcu' )
		);
		
	}
	
	return $actions;
}

function hangcu_validate_form_registry( $errors, $username, $email ) {
	$keyMap = array(
		'first_name' => 'Họ tên',
		'customer_mobile_phone' => 'Số điện thoại',
		'verify_phone' => 'Mã xác thực'
	);

	$_POST['first_name'] = is_string($_POST['first_name']) ? trim($_POST['first_name']) : $_POST['first_name'];
	$_POST['verify_phone'] = is_string($_POST['verify_phone']) ? trim($_POST['verify_phone']) : $_POST['verify_phone'];
	$_POST['customer_mobile_phone'] = is_string($_POST['customer_mobile_phone']) ? trim($_POST['customer_mobile_phone']) : $_POST['customer_mobile_phone'];

	foreach($keyMap as $key => $value) {
		if (empty($_POST[$key])) {
			return new WP_Error( 'registration-error-'.$key, __( '<strong>'.$value.'</strong> không để trống', 'hangcu' ) );
		}
	
		if (!is_string($_POST[$key])) {
			return new WP_Error( 'registration-error-'.$key, __( '<strong>'.$value.'</strong> không hợp lệ', 'hangcu' ) );
		}
	}

	if (!is_string($_POST['verify_phone']) || strlen($_POST['verify_phone']) !== 6) {
		return new WP_Error( 'registration-error-verify_phone', __( '<strong>Mã xác thực</strong> không hợp lệ', 'hangcu' ) );
	}

	$args = array(
		'meta_key'     => 'customer_mobile_phone',
		'meta_value'   => $_POST['customer_mobile_phone'],
	);

	if (count(get_users($args)) > 0) {
		return new WP_Error( 'registration-error-mobile_phone', __( '<strong>Số điện thoại</strong> đã được sử dụng', 'hangcu' ) );
	}

	if (empty(preg_match('/(09|03|07|08|05)+([0-9]{8}$)/', $_POST['customer_mobile_phone']))) {
		return new WP_Error( 'registration-error-mobile_phone', __( '<strong>Số điện thoại</strong> không hợp lệ', 'hangcu' ) );
	}

	if (!validPhoneNumberWithOtpCode($_POST['customer_mobile_phone'], $_POST['verify_phone'])) {
		return new WP_Error( 'registration-error-verify_phone', __( '<strong>Mã xác thực</strong> không hợp lệ', 'hangcu' ) );
	}

  return $errors;
};

function hangcu_update_meta_user_after_create($customer_id, $new_customer_data, $password_generated) {
	$arr_split = explode(' ', $_POST['first_name']);

	$last_name = '';
	$first_name = '';

	if (count($arr_split) >= 2) {
		$last_name = $arr_split[count($arr_split) - 1];

		array_pop($arr_split);

		$first_name = implode(' ', $arr_split);
	} else {
		$last_name = implode(' ', $arr_split);
	}

	$meta =array(
		'ID' => $customer_id,
		'first_name' => $first_name,
		'last_name' => $last_name
	);

	wp_update_user($meta);

	update_field( 'customer_mobile_phone', $_POST['customer_mobile_phone'], 'user_'.$customer_id );
	wp_update_user( [
		'nickname' => $_POST['first_name'],
		'ID' => $customer_id,
		'display_name' => $_POST['first_name']
	]);
}

function hangcu_header_my_order() {
    $endpoint = WC()->query->get_current_endpoint();

    $wc_endpoints = ['view-order', 'edit-account', 'orders', 'edit-address', 'my-comments'];

    if ( $endpoint == '' ) {
        global $wp;
        if ( isset( $wp->query_vars['my-comments'] ) ) $endpoint = 'my-comments';
    }

	if (!in_array( $endpoint, $wc_endpoints ) ) { ?>
		<div class="hangcu-header-my-account center-text">
			<a href="#" class="go-back-page">
			</a>
			<span><?php _e('Quản lý tài khoản', 'hangcu') ?></span>
		</div>
	<?php 
		return null;
	}

	

	if ( in_array( $endpoint, $wc_endpoints ) ) :
		$endpoint_title = $endpoint ? WC()->query->get_endpoint_title( $endpoint ) : '';

		if ($endpoint === 'edit-address') {
			if (isset($_GET['add_new'])) {
				$endpoint_title = __('Thêm địa chỉ mới', 'hangcu');
			}

			if (isset($_GET['edit-saved-address'])) {
				$endpoint_title = __('Sửa địa chỉ', 'hangcu');
			}
		}

		if ($endpoint === 'view-order') {
			$endpoint_title = 'Chi tiết đơn hàng';
        }
        
        if ($endpoint === 'my-comments') {
			$endpoint_title = 'Bình luận của tôi';
		}
?>
	<div class="hangcu-header-my-account">
		<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="go-back-page">
			<i class="fas fa-long-arrow-alt-left"></i>
		</a>
		<span><?php echo $endpoint_title; ?></span>
	</div>
<?php
	endif;
}

function check_user_login_has_phone_number() {
 
    $user_id = get_current_user_id();
    if ( $user_id ) {
        if ( !get_field( 'customer_mobile_phone', 'user_'.$user_id ) ) {
            // logout current account
            wp_logout();
			$_SESSION[ 'current_userid' ] = $user_id;
			$_SESSION[ 'first_user_action' ] = true;
			// $_SESSION[ 'first_user_action' ] = true;
			// always go to home page
			wp_redirect( home_url() . '?t=' . uniqid($user_id . '-') );
			exit;
        } else {
			unset( $_SESSION[ 'first_user_action' ] );
			// mục đích là clean cache
			// global $wp;
			// wp_redirect( home_url( $wp->request ) . '?t=' . uniqid($user_id . '+') );
			// wp_redirect( home_url() . '?t=' . uniqid($user_id . '-') );
			// exit;
		}
    } else {
		$status = session_status();

		if ( PHP_SESSION_DISABLED === $status ) {
			// That's why you cannot rely on sessions!
			return;
		}

		if ( PHP_SESSION_NONE === $status ) {
			session_start();
		}
		
		$user_id = !empty( $_SESSION[ 'current_userid' ] ) ? $_SESSION[ 'current_userid' ] : null;
		$first_user_action = !empty( $_SESSION[ 'first_user_action' ] ) ? $_SESSION[ 'first_user_action' ] : null;

		if ( $user_id != null && $first_user_action != null ) {
			$_SESSION[ 'has_reload' ] = true;
			add_action('wp_footer', function() {
				include_once( get_stylesheet_directory() . '/inc/template-tags/account/verify-phone-number-popup.php' );
				unset( $_SESSION[ 'first_user_action' ] );
			});
		} else {
			unset( $_SESSION[ 'first_user_action' ] );
			if( !empty( $_SESSION['has_reload']) ) {
				unset( $_SESSION[ 'has_reload' ] );
				wp_redirect( home_url( ) );
				exit;
			}
		}
	}

}

function hangcu_update_user_avatar(  $customer_id, $customer ) {
	if(isset($_POST['save_account_details']))
    {
		if( ! empty( $_FILES ) ) 
		{
			$file=$_FILES['account_image'];
			$attachment_id = upload_user_file( $file );
            if ( $attachment_id !== false ) {
                $old_attachment_id = get_field('user_avatar_path', 'user_'.$customer_id);
                if ( $old_attachment_id ) {
                    wp_delete_file( $old_attachment_id  );
                }
                update_field( 'user_avatar_url', $attachment_id['url'], 'user_'.$customer_id );
                update_field( 'user_avatar_path', str_replace('\\', '/', $attachment_id['file']), 'user_'.$customer_id );
            }
		}
    }
}
function upload_user_file( $file = array() ) {

    require_once( ABSPATH . 'wp-admin/includes/admin.php' );

      $file_return = wp_handle_upload( $file, array('test_form' => false ) );

      if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
          return false;
      } else {
            return $file_return;
            // $filename = $file_return['file'];

            // $attachment = array(
            //     'post_mime_type' => $file_return['type'],
            //     'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            //     'post_content' => '',
            //     'post_status' => 'inherit',
            //     'guid' => $file_return['url']
            // );

          //$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );

        //   require_once(ABSPATH . 'wp-admin/includes/image.php');
        //   $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
          // wp_update_attachment_metadata( $attachment_id, $attachment_data );

            // if( 0 < intval( $attachment_id ) ) {
            //     return $attachment_id;
            // }
      }

      return false;
}

if ( !function_exists( 'hangcu_filter_menu_items' ) ) {
	function hangcu_filter_menu_items( $items, $endpoints ) {
		unset( $items['dashboard'] );
		$account = $items['edit-account'];
		unset( $items['edit-account'] );
		// unset( $items['customer-logout'] );
		
		$items = array( 'edit-account' => $account ) + $items;

		return $items;
	}
}

if( !function_exists('hangcu_my_account_my_orders_columns') ) {
	function hangcu_my_account_my_orders_columns($array) {
		return array(
			'order-number'  => __( 'Mã đơn hàng', 'hangcu' ),
			'order-date'    => __( 'Ngày mua', 'hangcu' ),
			'order-name'    => __( 'Sản phẩm', 'hangcu' ),
			'order-total'   => __( 'Tổng tiền', 'hangcu' ),
			'order-status'  => __( 'Trạng thái', 'hangcu' ),
		);
	}
}


if ( !function_exists( 'hangcu_comments_link' ) ) {
	function hangcu_comments_link( $menu_links ) {

        $customer_logout = $menu_links['customer-logout'];
        unset( $menu_links['customer-logout'] );

        $items = $menu_links + array( 'my-comments' => __('Nhận xét của tôi', 'hangcu') ) + array( 'customer-logout' => $customer_logout );

        return $items;
	}
}

if ( !function_exists( 'hangcu_add_endpoint_comment' ) ) {
    function hangcu_add_endpoint_comment() {
        add_rewrite_endpoint( 'my-comments', EP_PAGES );
    }
}

if ( !function_exists( 'hangcu_comments_content' ) ) {
    function hangcu_comments_content(  ) {
        wc_get_template(
			'myaccount/comments.php',
			array(
				'current_user' => get_user_by( 'id', get_current_user_id() )
			)
		);
    }
}

if ( !function_exists( 'hangcu_add_class_my_account' ) ) {
    function hangcu_add_class_my_account( $classes ) {
        global $wp;
        if ( isset( $wp->query_vars['my-comments'] ) ) {
            $classes[] = 'my-comments';
        }
        
        return $classes;
    }
}

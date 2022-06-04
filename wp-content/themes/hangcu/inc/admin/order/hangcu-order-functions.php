<?php
function hangcu_copy_order_for_admin() {
  if (empty($_POST['order_id'])) {
    wp_send_json_error(['message' => 'Error'], 400);
    die;
  }

  $new_order = new WC_Order();

  $order = wc_get_order($_POST['order_id']);

  if (empty($order)) {
    wp_send_json(['message' => 'Error'], 400);
    die;
  }

  $status = $order->get_status();

  if ($status !== 'pending' && $status !== 'processing' && $status !== 'on-hold') {
    wp_send_json(['message' => 'Hoá đơn không được copy. Vui lòng kiểm tra lại trạng thái hoá đơn'], 400);
    die;
  }

  $billing_fields = ['first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country'];

  foreach($billing_fields as $field) {
    $set_function_name = 'set_billing_'.$field;
    $get_function_name = 'get_billing_'.$field;

    $new_order->$set_function_name($order->$get_function_name());

    $set_function_name = 'set_shipping_'.$field;
    $get_function_name = 'get_shipping_'.$field;

    $new_order->$set_function_name($order->$get_function_name());
  }

  $new_order->set_billing_email($order->get_billing_email());
  $new_order->set_billing_phone($order->get_billing_phone());

  $new_order->set_customer_note($order->get_customer_note());

  $user = wp_get_current_user();

  $skip_update_meta = ['order_copy_by_employee', 'copy_to_order', 'copy_from_order', 'can_edit_order', 'has_sync_ban_hang'];

  foreach($order->get_meta_data() as $value)  {
    $meta = $value->get_data();
    if (!in_array($meta['key'], $skip_update_meta)) {
      $new_order->add_meta_data($meta['key'], $meta['value']);
    } else {
      switch($meta['key']) {
        case 'order_copy_by_employee':
          $new_order->add_meta_data($meta['key'], $user->user_login);
        break;
        case 'copy_from_order':
          $new_order->add_meta_data($meta['key'], $order->get_id());
        break;
      }
    }
  }

  foreach($order->get_items() as $key => $product) {
    $key = $new_order->add_product(wc_get_product($product->get_product_id()), $product->get_quantity());
    // $new_order->get_items()[$key]->set_total( $product->get_total() );
  }

  $new_order->set_payment_method($order->get_payment_method());
  $new_order->set_payment_method_title($order->get_payment_method_title());

  $new_order->set_status($order->get_status());

  $new_order->set_customer_id($order->get_customer_id());

  $new_order->calculate_totals();

  $new_order_id = $new_order->save();

  $new_order = wc_get_order($new_order_id);

  $new_order->add_order_note('Đơn hàng được copy từ đơn hàng '. $_POST['order_id']);

  if ($new_order_id) {
    $order->set_status('pendingcancel');
    update_post_meta($order->get_id(), 'copy_to_order', $new_order_id);
    update_post_meta($order->get_id(), 'order_copy_by_employee', $user->user_login);

    $order->save();

    $original_order_id = get_field('original_order_id', $order->get_id() );
    if ( empty( $original_order_id ) ) $original_order_id = $order->get_id();
    update_post_meta($new_order_id, 'original_order_id', $original_order_id );
    
    // if (function_exists('hangcu_send_order_to_bh')) {
    //   hangcu_send_order_to_bh($new_order_id, false);
    // }

    // if (function_exists('hangcu_send_pending_cancel_order')) {
    //   hangcu_send_pending_cancel_order($order->get_id(), 'Huỷ do copy đơn hàng');
    // }

    wp_send_json(['new_order_id' => $new_order_id, 'link' => get_edit_post_link($new_order_id, 'edit')], 200);
    die;
  }

  wp_send_json(['message' => 'Error'], 400);
  die;
}

function hangcu_add_btn_copy_order_admin($id) {
  $status_allow_copy = ['pending', 'processing', 'hold'];
  $order = wc_get_order($id);
  $status = $order->get_status();

  $number_edit = intval(get_field('can_edit_order', $post_ID));

    if ((in_array($status, $status_allow_copy)) && $number_edit !== 1) :
?>
  <li class="copy-order-wrapper">
    <button data-order-id="<?php echo $id; ?>"class="button btn-copy-order">Sao chép đơn hàng <?php echo $id; ?></button>
  </li>
<?php
    endif;
}

function hangcu_allow_edit_order( $is_editable, $order ) {
  if (intval(get_field('can_edit_order', $order->get_id())) === 1) {
      $is_editable = true;
  }

  return $is_editable;
}

function hangcu_update_allow_edit_order($post_ID) {
  $number_edit = intval(get_field('can_edit_order', $post_ID));

  if ($number_edit > 2) {
    return;
  }

  // ToDO: Send Đồng bộ sau khi sửa

  update_post_meta($post_ID, 'can_edit_order', $number_edit + 1);
}


function hc_admin_show_content_table_order( $column ) {
  global $post;

  if ( 'sync_with_ban_hang' === $column ) {
      $order_id = wc_get_order($post->ID)->get_id();
      
      if (get_field('has_sync_ban_hang', $order_id) != 3) {
          echo "<button class='button btn-sync-with-ban-hang' data-order-id='".$order_id."'>".__('Đồng bộ với Bán Hàng', 'hangcu')."<span></span></button>";
      }
  }

  if ( 'status_sync_with_ban_hang' === $column ) {
      $order_id = wc_get_order($post->ID)->get_id();
      $has_sync_ban_hang = get_field('has_sync_ban_hang', $order_id);
      // $ban_hang_order_id = get_field('ban_hang_order_id', $order_id);
      /**
       * 1: chưa đồng bộ
       * 2: đang đồng bộ
       * 3: đã hoàn thành
       */
      // $flag = 1;
      $flag = empty($has_sync_ban_hang) ? 1 : $has_sync_ban_hang;
      
      // if ( $has_sync_ban_hang ) {
      //     if ( !$ban_hang_order_id ) {
      //         $flag = 2;
      //     } else $flag = 3;
      // } else {
      //     if ( $ban_hang_order_id ) {
      //         update_post_meta($order_id, 'has_sync_ban_hang', true);
      //         $flag = 3;
      //     }
      // }

      echo '<div style="display:flex;justify-content: flex-start;align-items: center">';
      for( $i = 1; $i <= $flag; $i++ ) {
          $text = '';
          $style = '';
          if ( $i == 1 ) {
              $style .= 'background-color: red;';
              $text = __('Chưa đồng bộ', 'hangcu');
          } elseif ( $i == 2 ) {
              $style .= 'background-color: orange;';
              $text = __('Đang đồng bộ', 'hangcu');
          } elseif ( $i == 3 ) {
              $style .= 'background-color: green;';
              $text = __('Đã đồng bộ', 'hangcu');
          }
          echo '<span class="sync-data-order" data-display="'.$text.'" style="'.$style.'"><span class="tooltip-data-order">'.$text.'</span></span>';
      }
      echo '</div>';
  }
}

function hc_admin_add_column_order_table_header( $columns ) {

  $new_columns = array();

  foreach ( $columns as $column_name => $column_info ) {

      $new_columns[ $column_name ] = $column_info;

      // if ( 'order_status' === $column_name ) {
          
      // }

      if ( 'order_total' === $column_name ) {
        $new_columns['status_sync_with_ban_hang'] = __( 'Trạng thái đồng bộ', 'hangcu' );
        $new_columns['sync_with_ban_hang'] = __( 'Đồng bộ với BH', 'hangcu' );
      }
  }

  return $new_columns;
}


function ajax_sync_order_banhang_id_func() {
  $orderID = $_POST['order_id'];
  if( empty( $orderID ) ) {
    wp_send_json_error('Order not found!');
  }

  $order = wc_get_order( $orderID );

  if( !is_object($order) ) {
    wp_send_json_error('Order not found!');
    die;
  }
  $payload = $order->get_data();
  $payload['billing']['state_name'] = $payload['billing']['state'];
  $payload['billing']['state'] = get_post_meta($orderID, '_billing_state', true);

  $payload['billing']['city_name'] = $payload['billing']['city'];
  $payload['billing']['city'] = get_post_meta($orderID, '_billing_city', true);

  $payload['billing']['address_2_name'] = $payload['billing']['address_2'];
  $payload['billing']['address_2'] = get_post_meta($orderID, '_billing_address_2', true);

  $payload['shipping']['state_name'] = $payload['shipping']['state'];
  $payload['shipping']['state'] = get_post_meta($orderID, '_shipping_state', true);

  $payload['shipping']['city_name'] = $payload['shipping']['city'];
  $payload['shipping']['city'] = get_post_meta($orderID, '_shipping_city', true);

  $payload['shipping']['address_2_name'] = $payload['shipping']['address_2'];
  $payload['shipping']['address_2'] = get_post_meta($orderID, '_shipping_address_2', true);

  // vat
  if ( !empty( get_field('vat_company_name', $orderID) ) ) {
    $payload['vat']['company'] = get_field('vat_company_name', $orderID);
    $payload['vat']['tax_code'] = get_field('vat_tax_code', $orderID);
    $payload['vat']['address'] = get_field('vat_address', $orderID);
    $payload['vat']['email'] = get_field('vat_email', $orderID);
  }

  // at
  if ( !empty( get_field('tracking_at', $orderID) ) ) {
    $payload['tracking_at'] = json_decode( get_field('tracking_at', $orderID) );
  }

  // line items
  $line_items = $payload['line_items'];
  $rs = [];
  foreach ( $order->get_items() as  $item_key => $item_values ) {
    $item_data = $item_values->get_data();
    array_push( $rs, $item_data  );
  }


  $payload['line_items'] = $rs;

  for( $i = 0; $i < count( $payload['line_items'] ); $i++ ) {

    $product_id = $payload['line_items'][$i]['product_id'];
    $product_integrated_id = get_field( 'product_integrated_id', $product_id );
    $product = wc_get_product( $product_id );
    if( is_object($product) ) {
      $payload['line_items'][$i]['sku'] = $product->get_sku();
      // lấy price từ order 
      $price_total = intval( $payload['line_items'][$i]['total'] );
      $payload['line_items'][$i]['price'] = $price_total / $payload['line_items'][$i]['quantity'];
    }
    if( $product_integrated_id ) {
      $payload['line_items'][$i]['product_integrated_id'] = $product_integrated_id;
    }
  }

  global $wpdb;
  $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wc_webhooks WHERE `status`='active' AND `topic`='order.created'" );
  foreach($results as $result)
  {
    $delivery_url = $result->delivery_url;
    update_post_meta($orderID, 'has_sync_ban_hang', 3);
    $http_args = array(
      'method'      => 'POST',
      'timeout'     => MINUTE_IN_SECONDS,
      'redirection' => 0,
      'httpversion' => '1.0',
      'blocking'    => true,
      'body'        => trim( wp_json_encode( $payload ) ),
      'headers'     => array(
        'Content-Type' => 'application/json',
      ),
      'cookies'     => array(),
    );
  
    // Add custom headers.
    $http_args['headers']['X-WC-Webhook-Source']      = home_url( '/' ); // Since 2.6.0.

    // Webhook away!
    $response = wp_safe_remote_request( $delivery_url, $http_args );
    if( !$response->errors ) {
      update_post_meta($orderID, 'has_sync_ban_hang', 3);
    }
    wp_send_json_success( $response );
    die;
  }
  wp_send_json_error('Config not found!');
  die;
}
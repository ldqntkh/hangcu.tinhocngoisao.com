<?php
$hc_custom_woo_rest_api = new HC_CUSTOM_WOO_REST_API();

$hc_custom_woo_rest_api->register_routes();
 
class HC_CUSTOM_WOO_REST_API extends WP_REST_Controller {
  public function register_routes() {
    add_action( 'rest_api_init', function () {
      register_rest_route( 'wc/v3', '/hc-get-product-details', array(
        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'hc_get_product_details' ),
          'args'                => array(),
          'permission_callback' => '__return_true'
        ))
      );
  
      register_rest_route( 'wc/v3', '/hc-get-order-details', array(
        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'hc_get_order_details' ),
          'args'                => array(),
          'permission_callback' => '__return_true'
        ))
      );
  
      register_rest_route( 'wc/v3', '/hc-delete-product', array(
        array(
          'methods'             => 'PUT',
          'callback'            => array( $this, 'hc_delete_product' ),
          'args'                => array(),
          'permission_callback' => '__return_true'
        ))
      );
  
      register_rest_route( 'wc/v3', '/hc-get-customer-info', array(
        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'hc_get_customer_info' ),
          'args'                => array(),
          'permission_callback' => '__return_true'
        ))
      );
    });
  }

  public function hc_get_order_details(WP_REST_Request $request) {
    try {
      $params_req = $request->get_params();

      if (!$params_req['ban_hang_order_id']) {
        return new WP_Error( 'message', 'Error Occurred' );
      }

      $orders = wc_get_orders( array(
        'limit'        => 1,
        'meta_value' => $params_req['ban_hang_order_id'],
        'meta_key'     => 'ban_hang_order_id',
        'meta_compare' => '='
      ));

      if (count($orders) > 0) {
        return new WP_REST_Response( ['ID' => $orders[0]->get_id()], 200 );
      }

      return new WP_REST_Response( [], 200 );
    }

    catch(Exception $e) {
      return new WP_Error( 'message', 'Error Occurred' );
    }
  }
 
  public function hc_get_product_details(WP_REST_Request $request ) {
    try {
      $params_req = $request->get_params();

      if (!$params_req['product_integrated_id']) {
        return new WP_Error( 'message', 'Error Occurred' );
      }

      if (isset($params_req['status'])) {
        $status = $params_req['status'];
      } else {
        $status = ['publish', 'private', 'password'];
      }

      $posts = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'product',
        'post_status' => $status,
        'meta_query'	=> array(
          array(
            'key'	  	=> 'product_integrated_id',
            'value'	  	=> $params_req['product_integrated_id'],
            'compare' 	=> '='
          )
        )
      ));

      if (count($posts) > 0) {
        return new WP_REST_Response( $posts[0], 200 );
      }

      return new WP_Error( 'message', 'Product not found' );
    }

    catch(Exception $e) {
      return new WP_Error( 'message', 'Error Occurred' );
    }
  }

  public function hc_delete_product(WP_REST_Request $request ) {
    try {
      $params_req = $request->get_params();

      if (!$params_req['product_integrated_id']) {
        return new WP_Error( 'message', 'Error Occurred' );
      }

      $posts = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'product',
        'post_status' => ['publish', 'private', 'password'],
        'meta_query'	=> array(
          array(
            'key'	  	=> 'product_integrated_id',
            'value'	  	=> $params_req['product_integrated_id'],
            'compare' 	=> '='
          )
        )
      ));

      if (count($posts) > 0) {
        wp_update_post(array(
          'ID'    =>  $posts[0]->ID,
          'post_status'   =>  'private'
        ));

        return new WP_REST_Response( ["id" => $posts[0]->ID], 200 );
      }

      return new WP_Error( 'message', 'Product not found' );
    }

    catch(Exception $e) {
      return new WP_Error( 'message', 'Error Occurred' );
    }
  }

  public function hc_get_customer_info($request) {
    try {
      $params_req = $request->get_params();

      if (!$params_req['phone']) {
        return new WP_Error( 'message', 'Error Occurred' );
      }

      $params = array(
        'meta_key'     => 'customer_mobile_phone',
        'meta_value'   => $params_req['phone']
      );

      $user = get_users($params);

      if (empty($user)) {
        return new WP_Error( 'message', 'User not found' );
      }

      return new WP_REST_Response( $user[0]->data, 200 );
    }

    catch(Exception $e) {
      return new WP_Error( 'message', 'Error Occurred' );
    }
  }
}
<?php
function hangcu_custom_order_meta_box() {
  add_meta_box(
    'hangcu_order_meta_box',
    'Custom order data',
    'hangcu_order_box_html',
    'shop_order'
  );
}
add_action('add_meta_boxes', 'hangcu_custom_order_meta_box');

function hangcu_order_box_html() {
  global $post;

  $order = wc_get_order($post->ID);

  echo '<div class="order-meta-box">';
  do_action("hangcu_order_box_content");
  do_action("hangcu_order_box_content_".$order->get_payment_method(), $order->get_id());
  echo '</div>';
}

add_action('hangcu_order_box_content', 'hangcu_show_vat_info_meta_box', 10);

function hangcu_show_vat_info_meta_box() {
  global $post;

  if ($post->post_type === 'shop_order' && !empty(get_option('hangcu_order_vat_info_company_'.$post->ID))) {
?>
    <div class="order-meta-box-column">
      <h4 class="title-box">VAT info</h4>
      <div class="wrapper-info">
        <div class="company">
          <strong>Tên công ty: </strong>
          <span><?php echo get_option('hangcu_order_vat_info_company_'.$post->ID); ?></span>
        </div>
        <div class="address">
          <strong>Địa chỉ: </strong>
          <span><?php echo get_option('hangcu_order_vat_info_address_'.$post->ID); ?></span>
        </div>
        <div class="company">
          <strong>Mã số thuế: </strong>
          <span><?php echo get_option('hangcu_order_vat_info_tax_code_'.$post->ID); ?></span>
        </div>
      </div>
      <hr/>
    </div>
<?php
  }

  return null;
}

function hangcu_electro_wp_wc_shortcode( $field ) {
	global $thepostid, $post;

	$thepostid			= empty( $thepostid ) ? $post->ID : $thepostid;
	$field['name']		= isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['default']	= isset( $field['default'] ) ? $field['default'] : '';
	$field['value'] 	= isset( $field['value'] ) ? $field['value'] : '';
	$field['fields']	= isset( $field['fields'] ) ? $field['fields'] : array( 'orderby' , 'order' );

	echo '<div class="electro-wc-shortcode">';

	electro_wp_select( array(
		'id'		=> $field['name'],
		'label'		=> $field['label'],
		'default'	=> $field['default'],
		'options'	=> array(
			'recent_products'		=> esc_html__( 'Recent Products', 'electro' ),
			'featured_products'		=> esc_html__( 'Featured Products', 'electro' ),
			'sale_products'			=> esc_html__( 'Sale Products', 'electro' ),
			'best_selling_products'	=> esc_html__( 'Best Selling Products', 'electro' ),
			'top_rated_products'	=> esc_html__( 'Top Rated Products', 'electro' ),
			'product_category'		=> esc_html__( 'Product Category', 'electro' ),
			'products'				=> esc_html__( 'Products', 'electro' ),
			'product_attribute'		=> esc_html__( 'Product Attribute', 'electro' ),
		),
		'class'		=> 'wc_shortcode_select show_hide_select',
		'name'		=> $field['name'] . '[shortcode]',
		'value'		=> isset( $field['value']['shortcode'] ) ? $field['value']['shortcode'] : $field['default'],
	) );
	
	electro_wp_select( array(
		'id'			  => $field['name'] . '_product_category_slug',
		'label'			=> esc_html__( 'Product Category Slug', 'electro' ),
    'class'			=>'wc_shortcode_product_category_slug',
    'options'	  => hangcu_get_list_categories(),
		'wrapper_class'	=> 'show_if_product_category hide',
		'name'			=> $field['name'] . '[product_category_slug]',
    'value'			=> isset( $field['value']['product_category_slug'] ) ? $field['value']['product_category_slug'] : '',
    
	) );

	electro_wp_text_input( array(
		'id'			=> $field['name'] . '_cat_operator',
		'label'			=> esc_html__( 'Product Category Operator', 'electro' ),
		'class'			=>'wc_shortcode_cat_operator',
		'placeholder'	=> esc_html__( 'Operator to compare categories. Possible values are \'IN\', \'NOT IN\', \'AND\'.', 'electro' ),
		'wrapper_class'	=> 'show_if_product_category hide',
		'name'			=> $field['name'] . '[cat_operator]',
		'value'			=> isset( $field['value']['cat_operator'] ) ? $field['value']['cat_operator'] : 'IN',
	) );

	electro_wp_select( array(
		'id'	=> $field['name'] . '_products_choice',
		'label'	=> esc_html__( 'Show Products by:', 'electro' ),
		'options'	=> array(  
			'ids'	=> esc_html__( 'IDs', 'electro' ),
			'skus'	=> esc_html__( 'SKUs', 'electro' )
		),
		'wrapper_class'	=> 'show_if_products hide',
		'class'			=> 'skus_or_ids',
		'name'			=> $field['name'] . '[products_choice]',
		'value'			=> isset( $field['value']['products_choice'] ) ? $field['value']['products_choice'] : 'ids',
	) );

	electro_wp_text_input( array(
		'id'			=> $field['name'] . '_products_ids_skus',
		'label'			=> esc_html__( 'Product IDs or SKUs', 'electro' ),
		'placeholder'	=> esc_html__( 'Enter IDs or SKUs separated by comma', 'electro' ),
		'wrapper_class'	=> 'show_if_products hide',
		'name'			=> $field['name'] . '[products_ids_skus]',
		'value'			=> isset( $field['value']['products_ids_skus'] ) ? $field['value']['products_ids_skus'] : '',
	) );

	electro_wp_text_input( array(
		'id'			=> $field['name'] . '_attribute',
		'label'			=> esc_html__( 'Attribute', 'electro' ),
		'class'			=>'wc_shortcode_attribute',
		'placeholder'	=> esc_html__( 'Enter single attribute slug.', 'electro' ),
		'wrapper_class'	=> 'show_if_product_attribute hide',
		'name'			=> $field['name'] . '[attribute]',
		'value'			=> isset( $field['value']['attribute'] ) ? $field['value']['attribute'] : '',
	) );

	electro_wp_text_input( array(
		'id'			=> $field['name'] . '_terms',
		'label'			=> esc_html__( 'Terms', 'electro' ),
		'class'			=>'wc_shortcode_terms',
		'placeholder'	=> esc_html__( 'Enter term slug spearate by comma(,).', 'electro' ),
		'wrapper_class'	=> 'show_if_product_attribute hide',
		'name'			=> $field['name'] . '[terms]',
		'value'			=> isset( $field['value']['terms'] ) ? $field['value']['terms'] : '',
	) );

	electro_wp_text_input( array(
		'id'			=> $field['name'] . '_terms_operator',
		'label'			=> esc_html__( 'Terms Operator', 'electro' ),
		'class'			=>'wc_shortcode_terms_operator',
		'placeholder'	=> esc_html__( 'Operator to compare terms. Possible values are \'IN\', \'NOT IN\', \'AND\'.', 'electro' ),
		'wrapper_class'	=> 'show_if_product_attribute hide',
		'name'			=> $field['name'] . '[terms_operator]',
		'value'			=> isset( $field['value']['terms_operator'] ) ? $field['value']['terms_operator'] : 'IN',
	) );

	echo '</div>';

	electro_wp_wc_shortcode_atts( array( 
		'id'			=> $field['name'] . '_shortcode_atts',
		'label'			=> esc_html__( 'Shortcode Atts', 'electro' ),
		'name'			=> $field['name'] . '[shortcode_atts]',
		'value'			=> isset( $field['value']['shortcode_atts'] ) ? $field['value']['shortcode_atts'] : '',
		'fields'		=> $field['fields']
	) );
}

// return list option category
if ( !function_exists( 'hangcu_get_list_categories' ) ) {
  function hangcu_get_list_categories() {
      $args = array(
          'hide_empty' => false,
          'taxonomy'  => 'product_cat',
      );
      $terms = get_terms( $args );
      $arrRS = array();
      foreach( $terms as $term ) {
        $arrRS[$term->slug] = $term->name;
      }
      return $arrRS;
  }
}

add_action('hangcu_order_box_content', 'hangcu_show_cancel_order', 20);

function hangcu_show_cancel_order() {
	global $post;

	if ($post->post_type === 'shop_order' && !empty(get_post_meta( $post->ID ,'order_cancel_value', true))) { ?>
		<div class="order-meta-box-column">
		<h4 class="title-box"><?php echo __("Lý do hủy đơn hàng",'hangcu') ?></h4>
		<div class="wrapper-info">
			<div class="company">
			<strong><?php echo __('Lý do: ','hangcu') ?></strong>
			<span><?php echo get_post_meta( $post->ID ,'order_cancel_value', true) ?></span>
			</div>
			<div class="address">
				<strong><?php echo __('Nội dung hủy: ','hangcu') ?></strong>
			<span><?php echo get_post_meta( $post->ID ,'order_cancel_note', true); ?></span>
			</div>
		</div>
		<hr/>
		</div>
	<?php }
}
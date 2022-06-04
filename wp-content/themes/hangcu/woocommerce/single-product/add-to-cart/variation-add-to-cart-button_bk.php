<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input( array(
		'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
		'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
		'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
	) );

	do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>
	
	<?php 
		$stop_selling = get_field('stop_selling', $product->get_id());

		if ( !$stop_selling ) :?>
			<button <?php if ( $product->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
				<b><?php echo esc_html( $product->single_add_to_cart_text() ); ?></b><br/>
				<span><?php echo __('Giao tận nơi hoặc nhận tại siêu thị', 'hangcu') ?></span>
			</button>
		<?php else : ?>
			<button type="button" class="single_add_to_cart_button stop_selling button alt">
				<b><?php echo __('Sản phẩm ngừng kinh doanh', 'hangcu'); ?></b><br/>
				<span><?php echo __(' ', 'hangcu') ?></span>
			</button>
		<?php endif;
	?>

    
    
	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>

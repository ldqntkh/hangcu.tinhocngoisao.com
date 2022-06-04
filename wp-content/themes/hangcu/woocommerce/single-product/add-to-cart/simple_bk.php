<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

// if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
	<?php $stop_selling = get_field('stop_selling', $product->get_id()); ?>
	<?php if( !$stop_selling ): 
		$check_stock = true;
		if( function_exists( 'get_stock_nhanhvn_inventories' ) ) {
			$stock = get_stock_nhanhvn_inventories( get_field('product_nhanhvn_id', $product->get_id()) );
			if( $stock > 0 ) $check_stock = true;
			else $check_stock = false;
		} else {
			$check_stock = false;
		}
	?>
		<?php if( $check_stock ) : ?>
			<p class="pd-stock-status"><strong>CÒN HÀNG</strong></p>
		<?php else : ?>
			<p class="pd-stock-status pre-order"><strong style="color: orange">TẠM HẾT HÀNG - CẦN ĐẶT TRƯỚC</strong></p>
		<?php endif; ?>
	<?php endif; ?>
	

	<form id="cart-top" class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
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

			<button <?php if ( $product->is_type('simple') ) echo 'id="simple-product-add-to-cart"' ?> type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
			<b><?php echo esc_html( $product->single_add_to_cart_text() ); ?></b><br/>
			</button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>
	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php //endif; ?>

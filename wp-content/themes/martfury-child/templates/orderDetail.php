<?php 

/*
    Template Name: Order detail
*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        wp_redirect(home_url());
        exit();
    }

    if ( ! $order = wc_get_order( $order_id ) ) {
        return;
    }

    $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
    $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
    
    get_header();
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
	<h2 class="title"><?php _e( 'Order details', 'woocommerce' ); ?></h2>
	<table class="order_details">
		<thead>
			<tr>
				<th><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();

				wc_get_template( 'order/order-details-item.php', array(
					'order'			     => $order,
					'item_id'		     => $item_id,
					'item'			     => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'	     => $product ? $product->get_purchase_note() : '',
					'product'	         => $product,
				) );
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<tr>
						<th scope="row"><?php echo $total['label']; ?></th>
						<td><?php echo $total['value']; ?></td>
					</tr>
					<?php
				}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<section class="woocommerce-customer-details">
	<section class="addresses">
		<div class="billing-address">

            <h2 class="title"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

            <address>
                <?php echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce' ) ) ); ?>

                <?php if ( $order->get_billing_phone() ) : ?>
                    <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
                <?php endif; ?>

                <?php if ( $order->get_billing_email() ) : ?>
                    <p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
                <?php endif; ?>
            </address>
        </div>
        <div class="shipping-address">
			<h2 class="title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
			<address>
				<?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) ); ?>
			</address>
		</div>
	</section>
	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</section>

<?php 
    get_footer();
?>
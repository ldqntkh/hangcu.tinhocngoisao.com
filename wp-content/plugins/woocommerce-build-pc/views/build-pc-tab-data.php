<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="buildpc_product_data" class="panel woocommerce_options_panel hidden">

	<div class="options_group">
		<p class="form-field">
			<label for="buildpc-type"><?php esc_html_e( 'Product type', 'woocommerce-buildpc' ); ?></label>
			<?php 
				$product_type = get_post_meta($product_object->get_id(), '_buildpc-type', true);
			?>
			<select name="buildpc-type" id="buildpc-type" class="select short">
				<?php foreach ( $product_types as $item ) : ?>
					<option value="<?php echo $item['value']; ?>" <?php echo $item['value'] == $product_type ? 'selected' : '' ?>>
						<?php echo $item['name']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
	</div>

	<!-- <div class="options_group">
		<p class="form-field">
			<label for="buildpc-ids"><?php esc_html_e( 'Linked products', 'woocommerce-buildpc' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="buildpc-ids" name="buildpc-ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
				$product_ids = get_post_meta($product_object->get_id(), '_linked_buildpc_ids', true);

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
					}
				}
				?>
			</select> 
            <?php echo wc_help_tip( __( 'Build PC: We will use product IDs to verify the products that can connect to each other!', 'woocommerce-buildpc' ) ); // WPCS: XSS ok. ?>
		</p>
	</div> -->
	<?php echo (__( 'Build PC: We will use product IDs to verify the products that can connect to each other!', 'woocommerce-buildpc' ) ); // WPCS: XSS ok. ?>
	<div id="select_product"><!-- Render App Select Product --></div>
	<?php 
		// render element input value 
	    $selected_product_value = get_post_meta($product_object->get_id(), '_selected_product_value', true);
	?>
	<input type="hidden" value="<?php echo $selected_product_value; ?>" id="selected_product_value" name="selected_product_value" />
	<script>
		var product_types = <?php echo json_encode($product_types); ?>;
	</script>
</div>
<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
unset($catalog_orderby_options['relevance']);
?>
<div class="woocommerce-ordering">
    <div class="input-radio padding-10-0">
        <span class="label-sort"><?php echo __('Sắp xếp theo:', 'hangcu') ?></span>
        <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
            <input id="input-orderby-<?php echo $id ?>" type="radio" name="input-orderby" value="<?php echo esc_attr( $id ); ?>" <?php checked( $orderby, $id ); ?> />
            <label for="input-orderby-<?php echo $id ?>"><?php echo $name; ?></label>
		<?php endforeach; ?>
    </div>
	<form method='get'>
		<select id="orderby" name="orderby" class="orderby custom-select" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
      <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
		<input type="hidden" name="paged" value="1" />
		
		<?php
			if( wp_is_mobile() ) {
				echo '<input type="hidden" name="_type" value="mb" />';
			} else {
				echo '<input type="hidden" name="_type" value="pc" />';
			}
		?>
		<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
	</form>
</div>
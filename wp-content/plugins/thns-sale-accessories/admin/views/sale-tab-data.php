<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product_object;
$se_campaign = new GEARVNSaleAccessoriesCampaign();
$campaign = $se_campaign->getCampaignByField( 'product_id', $product_object->get_id() );
if ( $campaign && count( $campaign ) > 0 ) $campaign = $campaign[0];
else $campaign = null;
?>
<div id="sale-accessories" class="panel woocommerce_options_panel hidden">
	<div class="description">
		<i><?php _e( 'Tại đây chúng tôi sẽ cung cấp cho bạn một chức năng giảm giá phụ kiện khi mua kèm với sản phẩm. Chúng tôi chỉ hỗ bán kèm 1:1 và có 2 loại giảm giá theo % hoặc theo tiền cố định. Đối với trường hợp bạn muốn FREE sản phầm kèm theo thì cấu hình giảm là 100%.'
						, THNS_SALE_ACCESSORIES_PLUGIN ) ?></i><br/>
		<i>
			<strong><?php _e('Lưu ý', THNS_SALE_ACCESSORIES_PLUGIN) ?></strong>
			<?php _e( 'Hiện tại chúng tôi chỉ hỗ trợ tối đa 3 nhóm, và mỗi nhóm tối đa 8 sản phẩm.'
						, THNS_SALE_ACCESSORIES_PLUGIN ) ?>
		</i>
	</div>
	<div class="container">
		<?php include THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/views/containers/group-sale-products.php'; ?>
		<?php include THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/views/containers/sale-products-assgined.php'; ?>
	</div>
	<script>
		const se_admin_ajax = '<?php echo admin_url('admin-ajax.php'); ?>';
		const current_product_id = <?php echo $product_object->get_id() ?>;
	</script>
</div>
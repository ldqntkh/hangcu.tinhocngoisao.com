<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bank = $bank[0];
?>
<h1><?php _e('Thiết lập trả góp', BANK_PLUGIN_NAME) ?></h1>
<div class="brands-container">
	
	<div class="left-container">
		<form class="form" id="installment-data" method="post">

			<div class="form-field form-required term-name-wrap">
				<label for="installment-month"><?php _e('Số tháng trả góp', BANK_PLUGIN_NAME) ?></label>
				<input name="installment-month" id="installment-month" type="text" value="" size="40" aria-required="true">
			</div>

            <div class="form-field form-required term-name-wrap">
				<label for="installment-minprice"><?php _e('Số tiền tối thiểu cho phép thực hiện trả góp', BANK_PLUGIN_NAME) ?></label>
				<input name="installment-minprice" id="installment-minprice" type="number" value="" size="40" aria-required="true">
			</div>

            <div class="form-field form-required term-name-wrap">
				<label for="installment-prepaid"><?php _e('Số tiền trả trước tối thiểu (%)', BANK_PLUGIN_NAME) ?></label>
				<input name="installment-prepaid" id="installment-prepaid" type="text" value="" size="40" aria-required="true">
			</div>

            <div class="form-field form-required term-name-wrap">
				<label for="installment-fee"><?php _e('Mức phí (%). Đối với thẻ tín dụng là PHÍ CHUYỂN ĐỔI, đối với tổ chức tài chính là LÃI SUẤT.', BANK_PLUGIN_NAME) ?></label>
				<input name="installment-fee" id="installment-fee" type="text" value="" size="40" aria-required="true">
			</div>
            
            <div class="form-field form-required term-name-wrap">
				<label for="installment-docs"><?php _e('Giấy tờ yêu cầu', BANK_PLUGIN_NAME) ?></label>
				<input name="installment-docs" id="installment-docs" type="text" value="" size="40" aria-required="true">
			</div>

            <input name="bank-id" id="bank-id" type="hidden" value="<?php echo $bank->ID ?>">

			<div class="form-field form-required term-name-wrap" style="margin-top: 20px">
				<button class="button" type="submit">
					<?php _e('Lưu', BANK_PLUGIN_NAME) ?>
					<span class="spinner is-active hide"></span>
				</button>
				<a class="button" href="<?php echo admin_url( 'admin.php?page=star_banks' ); ?>"><?php _e('Quay lại trang danh sách', BANK_PLUGIN_NAME) ?></a>
			</div>
		</form>
	</div>
	<div class="right-container" id="list-installment">
		<table class="wp-list-table widefat fixed striped tags ui-sortable">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Số tháng', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Số tiền tối thiểu', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('% trả trước', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('% phí', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Giấy tờ yêu cầu', BANK_PLUGIN_NAME) ?></strong></th>
					<th></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php 
					$installment = new Installment();
					echo $installment->getListInstallmentHtml( $bank->ID ); 
				?>
			</tbody>
		</table>
	</div>

	<script>
		const bank_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
	</script>
</div>
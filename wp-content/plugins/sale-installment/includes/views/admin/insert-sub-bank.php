<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bank = $bank[0];
$sub = $objbank->getSubBanks( $bank->ID );
?>

<h1><?php _e('Quản lý thẻ ngân hàng', BANK_PLUGIN_NAME) ?></h1>
<div class="brands-container">
    <div class="left-container">
		<form class="form" id="sub-bank-data" method="post">
            <?php 
                $visa = array_search('visa', array_column($sub, 'sub_bank_name'));
            ?>
            <div class="form-field form-required term-name-wrap">
				<input name="bank-visa" id="bank-visa" type="checkbox" <?php if ( $visa != false ) echo 'checked' ?> value="visa" size="40" aria-required="true">
                <label for="bank-visa"><?php _e('Thẻ Visa', BANK_PLUGIN_NAME) ?></label>
			</div>

            <?php 
                $mastercard = array_search('mastercard', array_column($sub, 'sub_bank_name'));
            ?>
            <div class="form-field form-required term-name-wrap">
				<input name="bank-mastercard" id="bank-mastercard" type="checkbox" <?php if ( $mastercard != false ) echo 'checked' ?> value="mastercard" size="40" aria-required="true">
                <label for="bank-mastercard"><?php _e('Thẻ Master Card', BANK_PLUGIN_NAME) ?></label>
			</div>

            <?php 
                $jcb = array_search('jcb', array_column($sub, 'sub_bank_name'));
            ?>
            <div class="form-field form-required term-name-wrap">
				<input name="bank-jcb" id="bank-jcb" type="checkbox" <?php if ( $jcb !== false ) echo 'checked' ?> value="jcb" size="40" aria-required="true">
                <label for="bank-jcb"><?php _e('Thẻ JCB', BANK_PLUGIN_NAME) ?></label>
			</div>

            <input name="bank-id" id="bank-id" type="hidden" value="<?php echo $bank->ID ?>">

            <div class="form-field form-required term-name-wrap">
				<button class="button" type="submit">
					<?php _e('Cập nhật', BANK_PLUGIN_NAME) ?>
					<span class="spinner is-active hide"></span>
				</button>
                <a class="button" href="<?php echo admin_url( 'admin.php?page=star_banks' ); ?>"><?php _e('Quay lại trang danh sách', BANK_PLUGIN_NAME) ?></a>
			</div>
        </form>
    </div>
</div>

<script>
    const bank_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
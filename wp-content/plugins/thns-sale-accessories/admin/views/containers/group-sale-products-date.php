<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
    
?>

<div class="form-group">
    <label><?php _e('Tên chiến dịch:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
    <input type="text" name="campaign-name" id="campaign-name" placeholder="<?php _e('Tên chiến dịch', THNS_SALE_ACCESSORIES_PLUGIN) ?>" 
        value="<?php if ( $campaign ) echo $campaign->name ?>"/>
</div>

<div class="form-group">
    <label><?php _e('Thời gian bắt đầu:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
    <input type="text" name="campaign-start-date" id="campaign-start-date" placeholder="<?php _e('Từ ... YYYY-MM-DD', THNS_SALE_ACCESSORIES_PLUGIN) ?>" 
        value="<?php if ( $campaign ) echo $campaign->start_date ?>"/>
</div>

<div class="form-group">
    <label><?php _e('Thời gian kết thúc:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
    <input type="text" name="campaign-end-date" id="campaign-end-date" placeholder="<?php _e('Đến ... YYYY-MM-DD', THNS_SALE_ACCESSORIES_PLUGIN) ?>" 
        value="<?php if ( $campaign ) echo $campaign->end_date ?>"/>
</div>

<div class="form-group checkbox">
    <label><?php _e('Kích hoạt:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
    <input type="checkbox" name="campaign_enable" id="campaign_enable"  <?php if ( $campaign && $campaign->enable ) echo 'checked'  ?>
        value="1"/>
</div>

<div class="form-group">
    <button id="save-campaign" class="button" value="submit"><?php _e('Lưu', THNS_SALE_ACCESSORIES_PLUGIN) ?></button>
</div>

<script>
    const campaign_id = <?php if ( $campaign ) echo $campaign->ID; else echo '""'; ?>;
</script>

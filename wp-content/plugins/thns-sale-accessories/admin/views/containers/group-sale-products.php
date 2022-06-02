<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $group_items = [];
    if ( $campaign != null ) {
        $group = new GEARVNGroupSaleAccessory();
        $group_items_rs = $group->getGroupsByCampaign( $campaign->ID );
        foreach( $group_items_rs as $item ) {
            $data = $group->getFormatData( $item );
            $products = $group->getProductIdsByGroup( $data['ID'] );
            $pids = [];
            foreach( $products as $pr ) {
                array_push( $pids, $pr->product_id );
            }
            $data['products'] = $pids;
            array_push( $group_items, $data );
        }
    }
?>

<div class="left-container">
    <?php include THNS_SALE_ACCESSORIES_PLUGIN_DIR . '/admin/views/containers/group-sale-products-date.php'; ?>
    <div id="se-groups">
        <div class="form-group">
            <label><?php _e('Tên nhóm:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
            <input type="text" name="se-group-name" id="se-group-name" />
        </div>
        <div class="form-group">
            <label><?php _e('Loại giảm giá:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
            <select name="se-group-type" id="se-group-type">
                <option value="price"><?php _e('Giảm tiền', THNS_SALE_ACCESSORIES_PLUGIN) ?></option>
                <option value="percent"><?php _e('Giảm theo %', THNS_SALE_ACCESSORIES_PLUGIN) ?></option>
                <option value="gift"><?php _e('Quà tặng', THNS_SALE_ACCESSORIES_PLUGIN) ?></option>
            </select>
        </div>
        <div class="form-group">
            <label><?php _e('Giảm:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
            <input type="number" name="se-down-value" id="se-down-value" value = "0"/>
        </div>
        <div class="form-group">
            <label><?php _e('Thứ tự hiển thị:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
            <input type="number" name="se-display-index" id="se-display-index" value = "0"/>
        </div>
        <div class="form-group">
            <button id="save-se-group" class="button" value="submit"><?php _e('Lưu', THNS_SALE_ACCESSORIES_PLUGIN) ?></button>
        </div>
    </div>
    <div id="se-group-items">
    </div>

    <script>
        const group_items = <?php echo json_encode( $group_items ) ?>;
    </script>
</div>
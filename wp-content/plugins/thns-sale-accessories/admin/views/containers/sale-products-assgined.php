<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="right-container" style="display: none">
    <div class="search_group">
        <div class="form-group">
            <strong>Thêm sản phẩm cho <span id="group-name-display"></span></strong><br/>
            <label><?php _e('Tìm sản phẩm:', THNS_SALE_ACCESSORIES_PLUGIN) ?></label>
            <input type="text" id="se-search-group-name" />
            <span class="spinner"></span>
        </div>
    </div>
    <div id="btns-update">
        <button id="update-lst-product" class="button" type="button">Lưu danh sách sản phẩm</button>
    </div>
    <div id="list-product-selected"></div>
</div>
<div id="custom-grouped-product">
    <?php $this->custom_quantity_field() ?>
    <p class="form-field">
        <label for=""></label>
        <button type="button" class="save_custom_product_group"><?php echo __( 'Thêm sản phẩm', 'woocommerce' ); ?></button>
    </p>
    <div class="form-field list-product-wrapper">
        <label class="list-product-wrapper__label"><?php echo __( 'Danh sách sản phẩm', 'woocommerce' ); ?></label>
        <table>
            <tr class="label">
                <td><?php echo __( 'Tên sản phẩm', 'woocommerce' ); ?></td>
                <td><?php echo __( 'Số lượng', 'woocommerce' ); ?></td>
                <td><?php echo __( 'Hành động', 'woocommerce' ); ?></td>
            </tr>
        </table>
    </div>
</div>


<div class="custom-checkout">
    
    <div class="checkout-address">
        
        <?php if ( !empty( $otherAddr ) && $user->ID !== 0): 
            echo '<h3>Chọn địa chỉ giao hàng</h3>';
        ?>
            <div class="list-address">
                <?php foreach ( $otherAddr as $idx => $address ): ?>

                <?php 
                    $isAddressDefault = isset($address['address_is_default']) && $address['address_is_default'] === 'on'; 
                    $address_key_selected = WC()->session->get('address_key_selected');
                    $isSelected = false;
                    if(!empty( $address_key_selected ) && $address_key_selected == $idx) $isSelected = true;
                    else if(empty( $address_key_selected ) && $otherAddr[$idx]['address_is_default'] == 'on' ) $isSelected = true;
                ?>
                <div class="address-item <?= $isAddressDefault ? 'active' : ''; ?>">
                    <div class="select-address">
                        <input type='radio' name="select-address" id="<?php echo $idx?>" value="<?php echo $idx?>" <?php if( $isSelected ) echo 'checked ' ?>/>
                        <label for="<?php echo $idx?>"></label>
                    </div>
                    <div class="address-content">
                        <label for="<?php echo $idx?>">
                        <div class="info">
                            <p>
                                <strong><?php echo esc_html($otherAddr[$idx]['billing_last_name']) ?></strong>
                                <strong>&nbsp;-&nbsp;</strong>
                                <strong><?php echo esc_html($otherAddr[$idx]['billing_phone']) ?></strong>
                            </p>
                            <p><span><?php echo isset($otherAddr[$idx]['full_address']) ? esc_html($otherAddr[$idx]['full_address']) : ''; ?></span></p>
                        </div>
                        </label>
                        <?php 
                            if( $isAddressDefault ) : ?>
                                <p class="address-default">
                                    <i></i> <?= __('Địa chỉ mặc định', 'hangcu') ?>
                                </p>
                            <?php endif;
                        ?>
                    </div>
                    <div class="group-button">
                        <a href="<?php echo add_query_arg( array(
                            'step' => 'shipping',
                            'type' => 'edit',
                            'key'  => $idx
                        ), wc_get_checkout_url() ); ?>">Sửa</a>
                        <!-- <span class="update-address" data-key="<?php echo $idx; ?>" data-value="<?php echo esc_html(json_encode($address)); ?>"><?php echo __('Sửa', 'hangcu'); ?></span> -->
                        <?php if ( $user->ID !== 0 && !$isAddressDefault ): ?>
                            <span data-key="<?php echo $idx; ?>" class="remove-address" ><?php echo __('Xoá', 'hangcu'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-shipping-address">
            <div class="shipping_address_hidden"></div>
            <input type="hidden" name="shipping_address_key_selected" value="" />
            <input type="hidden" name="shipping_account_address_action" value="save" />
        </form>
    </div>
</div>

<div class="add-new-address">
    <?php if ( $user->ID !== 0 && count($otherAddr) < 10): ?>
        <a href="<?php echo add_query_arg( array(
                            'step' => 'shipping',
                            'type' => 'addnew'
                        ), wc_get_checkout_url() ); ?>" ><?php echo __('Thêm địa chỉ mới', 'hangcu'); ?>
            <i class="icon-next"></i>
        </a>
    <?php endif; ?> 
</div>

<div class="selected-address">
    <?php if ( $user->ID !== 0 && count($otherAddr) > 0): ?>
        <span class="deliver-address"><?php echo __('Giao đến địa chỉ này', 'hangcu'); ?>
    <?php endif; ?> 
</div>

<div class="modal-delete-address-wrapper">
    <div class="modal fade modal-delete-address" id="deleteAddress" tabindex="-1" role="dialog" aria-labelledby="deleteAddress" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body">
            <span class="icon-delete"></span>
            <span class="text-center d-block"><?php echo __('Bạn có chắc chắn muốn xoá địa chỉ?', 'hangcu'); ?></span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-sign-out-alt"></i>
                <span><?php echo __('Huỷ', 'hangcu'); ?></span>
            </button>
            <a data-href="<?php echo get_permalink( wc_get_page_id( 'checkout' ) ); ?>" class="btn btn-primary btn-delete-address">
                <i class="far fa-trash-alt"></i>
                <?php echo __('Xác nhận', 'hangcu'); ?>
            </a>
        </div>
        </div>
    </div>
    </div>
</div>
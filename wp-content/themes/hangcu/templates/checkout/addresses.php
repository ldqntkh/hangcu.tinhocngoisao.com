<?php
    get_header('checkout');
   $user     = wp_get_current_user();

   $otherAddr = null;
   if ($user->ID !== 0) {
       $otherAddr = get_user_meta( $user->ID, 'hangcu_multiple_shipping_addresses', true );
   }
    
?>
<div class="custom-checkout">
    
    <div class="checkout-address">
        <?php 
            if ($user->ID !== 0) {
                if ($otherAddr && count($otherAddr)) {
                    echo '<h2>Chọn địa chỉ giao hàng</h2>';
                    echo '<p class="description-header">Chọn địa chỉ giao hàng có sẵn</p>';
                } else {
                    echo '<h2>Tạo địa chỉ giao hàng</h2>';
                }
                
            } else {
                echo '<h2>Nhập địa chỉ giao hàng</h2>';
                echo '<p class="description-header">Bạn đã có tài khoản? <a href="' .get_permalink( get_page_by_path( 'checkout' ) ).'?type=login'. '">Đăng nhập</a> hoặc <a href="' .get_permalink( get_page_by_path( 'checkout' ) ).'?type=register'. '">Đăng ký</a></p>';
            }
        ?>
        
        <?php if ( !empty( $otherAddr ) && $user->ID !== 0): ?>
            <div class="list-address">
                <?php foreach ( $otherAddr as $idx => $address ): ?>

                <?php 
                    $isAddressDefault = isset($address['address_is_default']) && $address['address_is_default'] === 'on'; 
                ?>
                <div class="address-item <?= $isAddressDefault ? 'active' : ''; ?>">
                    <div class="address-content">
                        <!-- <input type="hidden" id="<?php echo $idx?>" value="<?php echo esc_html(json_encode($address)); ?>" /> -->
                        <div class="info">
                            <p><?php echo esc_html($address['billing_last_name']); ?>
                                <?php if ( $isAddressDefault ): ?>
                                <span class="default">(Mặc định)</span>
                                <?php endif;?>
                            </p>
                            <p><strong><?php echo __('Địa chỉ:' , 'hangcu') ?></strong> <span><?php echo isset($address['full_address']) ? esc_html($address['full_address']) : ''; ?></span></p>
                            <p><strong><?php echo __('Số điện thoại:' , 'hangcu') ?></strong> <span><?php echo esc_html($address['billing_phone']); ?></span></p>
                        </div>
                        <div class="group-button">
                            <span class="deliver-address" data-key="<?php echo $idx; ?>"><?php echo __('Giao đến địa chỉ này', 'hangcu'); ?></span>
                            <span class="update-address" data-key="<?php echo $idx; ?>" data-value="<?php echo esc_html(json_encode($address)); ?>"><?php echo __('Sửa', 'hangcu'); ?></span>
                            <?php if ( $user->ID !== 0 && !$isAddressDefault ): ?>
                                <span data-key="<?php echo $idx; ?>" class="remove-address" ><?php echo __('Xoá', 'hangcu'); ?></span>
                            <?php endif; ?>
                        </div>
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
        

        <?php if ( !empty( $otherAddr ) && $user->ID !== 0 && count($otherAddr) < 10): ?>
            <p><?php echo __('Bạn muốn giao đến địa chỉ mới?', 'hangcu'); ?> <a href="#" class="add-new-address"><?php echo __('Tạo địa chỉ mới.', 'hangcu'); ?></a></p>
        <?php endif; ?>

        <div class="address-form new-address <?php  
            if ($user->ID !== 0 && !empty( $otherAddr )) {
                echo 'hidden';
            }?>" style="<?php  
            if ($user->ID !== 0 && !empty( $otherAddr )) {
                echo 'display: none;';
            }?>">
            <form method="post" onsubmit="return false;">
                <?php if ($user->ID !== 0): ?>
                    <h2 class="title-block" data-text-add="<?php echo __('Tạo địa chỉ mới', 'hangcu'); ?>" data-text-edit="<?php echo __('Sửa địa chỉ', 'hangcu'); ?>"></h2>
                <?php endif; ?>
                <div id="addresses">
                    <div class="shipping_address address_block">
                        <?php
                            $checkout = WC()->checkout();
                            $fields = $checkout->get_checkout_fields( 'billing' );
                            $options = wp_parse_args(get_option('devvn_woo_district'));

                            $defaultAddress = $_POST;

                            if (empty( $otherAddr ) && $_SERVER['REQUEST_METHOD'] === 'GET') {
                                $username = $user->user_firstname . ' ' . $user->user_lastname;
                                if( trim( $username ) == '' ) {
                                    $username = $user->user_displayname;
                                }
                                $defaultAddress = array(
                                    'billing_last_name' => $username,
                                    'billing_email' => $user->user_email,
                                    'billing_phone' => get_field('customer_mobile_phone', 'user_'.$user->ID )
                                );
                            }

                            $defaultAddress['billing_state'] = isset($defaultAddress['billing_state']) ? $defaultAddress['billing_state'] : $options['tinhthanh_default'];

                            $address_field = [];

                            foreach ( $fields as $key => $field ) {
                                $defaultValue = isset($defaultAddress[$key]) ? $defaultAddress[$key] : '';
                                // if ( $key == 'billing_email' ) continue;
                                if ($key === 'billing_state' ||  $key === 'billing_city' || $key === 'billing_address_2' || $key === 'billing_address_1') {
                                    $field['return'] = true;

                                    $address_field[$key] = woocommerce_form_field( $key, $field, $defaultValue);
                                } else {
                                    woocommerce_form_field( $key, $field, $defaultValue);
                                }
                            }

                        ?>
                        <div class="group-address">
                            <?php echo $address_field['billing_state']; ?>
                            <?php echo $address_field['billing_city']; ?>
                        </div>
                        <div class="group-address">
                            <?php echo $address_field['billing_address_2']; ?>
                            <?php echo $address_field['billing_address_1']; ?>
                        </div>
                        <div class="input-form  <?php if ($user->ID === 0) echo 'd-none';?>">
                            <div class="hangcu-input-checkbox">
                                <input type="checkbox" name="address_is_default" class="choose_default_address"id="default_address" />
                                <label for="default_address" class=""><?php echo __('Sử dụng làm địa chỉ mặc định', 'hangcu'); ?></label>
                            </div>
                            <input type="hidden" name="shipping_address_is_default[]" value="" />
                            <input type="hidden" name="shipping_address_is_selected[]" value="true" />
                            <?php 
                                wp_nonce_field('add_new_saved_address', 'add_new_saved_address_field');
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="group-button">
                    <input type="hidden" name="shipping_account_address_action" value="save" />
                    <button data-text-add="<?php echo __('Giao đến địa chỉ này', 'hangcu'); ?>" data-text-edit="<?php echo __('Cập nhật', 'hangcu'); ?>" class="save-new-address" type="button">
                        <?php echo __('Giao đến địa chỉ này', 'hangcu'); ?>
                    </button>
                    <?php if ($user->ID !== 0 && !empty( $otherAddr )):?>
                        <a href="#" class="cancel"><?php echo __('Hủy', 'hangcu'); ?></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
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
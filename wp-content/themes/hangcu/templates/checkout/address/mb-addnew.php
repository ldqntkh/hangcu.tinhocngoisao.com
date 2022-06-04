<div class="address-form new-address <?php  
    if ($user->ID !== 0 && !empty( $otherAddr )) {
        // echo 'hidden';
    }?>" style="<?php  
    if ($user->ID !== 0 && !empty( $otherAddr )) {
        // echo 'display: none;';
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
        
    </form>
</div>
<div class="group-button">
    <input type="hidden" name="shipping_account_address_action" value="save" />
    <button data-text-add="<?php echo __('Giao đến địa chỉ này', 'hangcu'); ?>" data-text-edit="<?php echo __('Cập nhật', 'hangcu'); ?>" class="save-new-address" type="button">
        <?php echo __('Giao đến địa chỉ này', 'hangcu'); ?>
    </button>
</div>
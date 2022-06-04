<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

    $user     = wp_get_current_user();

    $otherAddr = null;
    if ($user->ID !== 0) {
        $otherAddr = get_user_meta( $user->ID, 'hangcu_multiple_shipping_addresses', true );
    }
?>

<div class="woocommerce-billing-fields">
    
    <?php if ($otherAddr && count ( $otherAddr ) > 0 ) : ?>

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#billing-form-details" role="tab" aria-controls="billing-form-details" aria-selected="true">
                    <?php esc_html_e( 'Billing details', 'woocommerce' ); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#saved-addresses" role="tab" aria-controls="saved-addresses" aria-selected="false">
                    <?php esc_html_e( 'Địa chỉ đã lưu', 'hangcu' ); ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="billing-form-details" role="tabpanel" aria-labelledby="billing-form-details-tab">
                <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

                <div class="woocommerce-billing-fields__field-wrapper">
                    <?php
                    $fields = $checkout->get_checkout_fields( 'billing' );

                    foreach ( $fields as $key => $field ) {
                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                    }
                    ?>
                </div>

                <?php if ( is_user_logged_in() ) : ?>
                <p class="form-row form-row-wide save-address">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="save-address" 
                            type="checkbox" name="save-address" value="1" checked/>
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline" for="save-address">
                        <span><?php esc_html_e( 'Lưu lại địa chỉ?', 'hangcu' ); ?></span>
                    </label>
                </p>
                <?php endif; ?>
                <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>

            </div>
            <div class="tab-pane" id="saved-addresses" role="tabpanel" aria-labelledby="saved-addresses-tab">
                <?php foreach($otherAddr as $key => $value) : ?>
                    <div class="address-item">
                        <input type="hidden" id="<?php echo $key?>" value='<?php echo json_encode($value) ?>' />
                        <div class="info">
                            <p><strong><?php echo __('Họ tên:' , 'hangcu') ?></strong> <span><?php echo esc_html($value['billing_last_name']); ?></span></p>
                            <p><strong><?php echo __('Số điện thoại:' , 'hangcu') ?></strong> <span><?php echo esc_html($value['billing_phone']); ?></span></p>
                            <p><strong><?php echo __('Email:' , 'hangcu') ?></strong> <span><?php echo esc_html($value['billing_email']); ?></span></p>
                            <p><strong><?php echo __('Địa chỉ:' , 'hangcu') ?></strong> <span><?php echo esc_html($value['billing_address_1']); ?></span></p>
                        </div>
                        <div class="group-btns">
                            <a class="select-saved-address" href="#" data-key='<?php echo $key ?>'><?php echo __('Giao đến địa chỉ này', 'hangcu') ?></a>
                            <a href="<?php echo wc_get_account_endpoint_url( 'edit-address' ).'?edit-saved-address='.$key ?>"><?php echo __('Sửa', 'hangcu') ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>
        
        <h3><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h3>

        <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

        <div class="woocommerce-billing-fields__field-wrapper">
            <?php
            $fields = $checkout->get_checkout_fields( 'billing' );

            foreach ( $fields as $key => $field ) {
                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
            }
            ?>
        </div>

        <?php if ( is_user_logged_in() ) : ?>
        <p class="form-row form-row-wide save-address">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="save-address" 
                    type="checkbox" name="save-address" value="1" checked/>
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline" for="save-address">
                <span><?php esc_html_e( 'Lưu lại địa chỉ?', 'hangcu' ); ?></span>
            </label>
        </p>
        <?php endif; ?>
        <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>

    <?php endif; ?>

</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>

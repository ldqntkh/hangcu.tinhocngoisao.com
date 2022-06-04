<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $gateway->has_fields() || $gateway->id == 'alepay' ) : ?>
    <li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> <?php if( isset($gateway->disable_method) && $gateway->disable_method ) echo 'disable-method' ?> " <?php if ($gateway->id == 'hangcu_zalo_payment') echo 'style="display:none"' ?> >
        <input <?php if( isset($gateway->disable_method) && $gateway->disable_method ) echo 'disabled' ?> id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

        <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
            <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
        </label>
    </li>
    <?php $gateway->payment_fields(); ?>
<?php else : ?>
    <li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> <?php if( isset($gateway->disable_method) && $gateway->disable_method ) echo 'disable-method' ?>">
        <input <?php if( isset($gateway->disable_method) && $gateway->disable_method ) echo 'disabled' ?> id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

        <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
            <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
        </label>
    </li>
<?php endif; ?>

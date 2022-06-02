'use strict';
const $ = jQuery;
var checkout = {
    init: function () {
        this.toggleVatForm();
        this.listenCartUpdate();

        $('body').on('change', 'input[name=iCheck]', function () {
            $('input[id="payment_method_gearvn_zalo_payment"]').prop('checked', true).trigger('change');
        });

        $('body').on('click', '#apply_coupon_ajax', function () {
            var valCoupon = $("#val_coupon_ajax").val();
            if (valCoupon.trim() !== '') {
                $('form.woocommerce-form-coupon #coupon_code').val(valCoupon);
                $('form.woocommerce-form-coupon').submit();
                $("#val_coupon_ajax").val('');
            }
        });
    },
    toggleVatForm: function () {
        jQuery(document).on('change', '#payment .checkbox-require-vat input[name="require-vat"]', function () {
            jQuery('#payment .vat-form .group-info').slideToggle('fast');
        });
    },
    listenCartUpdate: function () {
        jQuery(document).on('updated_wc_div', function () {
            if (!jQuery('.woocommerce-cart .woocommerce-cart-form .woocommerce-cart-form__cart-item').length) {
                jQuery('body').addClass('gearvn_loading');

                location.reload();
            }
        });
    }
}

module.exports = checkout;
'use strict';
const $ = jQuery;
var paymentCheckout = {
    init: function () {
        $('body').on('change', 'input[name=iCheck]', function () {
            $('input[id="payment_method_gearvn_zalo_payment"]').prop('checked', true).trigger('change');
        });

        $('body').on('change', 'input[name=payment_method]', function () {
            var valPayment = $(this).val();
            if (valPayment != 'gearvn_zalo_payment') {
                $('input[name=iCheck]').prop('checked', false);
            }
        });

        jQuery(document).on('click', '#place_order', function () {
            jQuery('body').addClass('gearvn_loading');
        });

        jQuery('body').on('checkout_error', function () {
            jQuery('body').removeClass('gearvn_loading');
        });
    }
}

module.exports = paymentCheckout;
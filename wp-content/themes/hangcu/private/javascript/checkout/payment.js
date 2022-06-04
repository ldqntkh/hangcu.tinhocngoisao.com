'use strict';
const $ = jQuery;
var paymentCheckout = {
    init: function () {
        $('body').on('change', 'input[name=iCheck]', function () {
            $('input[id="payment_method_hangcu_zalo_payment"]').prop('checked', true).trigger('change');
        });

        $('body').on('change', 'input[name=payment_method]', function () {
            var valPayment = $(this).val();
            if (valPayment != 'hangcu_zalo_payment') {
                $('input[name=iCheck]').prop('checked', false);
            }
        });

        jQuery(document).on('click', '#place_order', function () {
            jQuery('body').addClass('hangcu_loading');
            // clean session storage cart
            let skeys = Object.keys(sessionStorage);
                            
            for( let i = 0; i < skeys.length; i++ ) {
                if( skeys[i].indexOf('wc_fragments_') == 0 ) {
                    sessionStorage.removeItem( skeys[i] );
                    break;
                }
            }
        });

        jQuery('body').on('checkout_error', function () {
            jQuery('body').removeClass('hangcu_loading');
        });

        // check payment alepay
        $('body').on('change', 'li.payment_method_sub_alepay input', function() {
            $('#payment_method_alepay').prop('checked', true);
        })
        $('body').on('change', 'input[name="payment_method"]', function() {
            $('li.payment_method_sub_alepay input').prop('checked', false);
            $('#payment_method_alepay').prop('checked', false);
        });
    }
}

module.exports = paymentCheckout;
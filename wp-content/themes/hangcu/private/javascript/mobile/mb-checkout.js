const $ = jQuery;
const validateAddress = require('../helper/validate-address');
const hangcu_home_ajax= '/wp-admin/admin-ajax.php';
const mbCheckout = {
    init: function() {
        
        $('.deliver-address').off('click').on('click', function () {
            let key = $('input[name="select-address"]:checked').val();
            if( !key ) return false;

            jQuery('input[name=shipping_address_key_selected]').val(key);
            jQuery('body').addClass('hangcu_loading');
            $('.form-shipping-address').submit();
        });

        $(document).off('click', '.page-template-checkout .add-new-address');
        $('.add-new-address').off('click');

        this.saveAddress();
        
        setTimeout(function() {
            if( typeof edit_data_address != 'undefined' ) {
                mbCheckout.handleFillDataUpdate(edit_data_address);
                mbCheckout.updateAddress();
            }
        }, 1000)
    },

    handleFillDataUpdate: function (data) {
        let mapKey = {
            billing_last_name: 'billing_last_name',
            billing_phone: 'billing_phone',
            billing_state: 'billing_state',
            billing_city: 'billing_city',
            billing_address_1: 'billing_address_1',
            billing_address_2: 'billing_address_2',
            billing_email: 'billing_email',
            address_is_default: 'default_address'
        },
            selector = '';
        $('html,body').animate({ scrollTop: $('.address-form.update-address').offset().top }, 500, 'swing');

        for (let key in mapKey) {
            if (key === 'billing_city' || key === 'billing_state' || key === 'billing_address_2') {
                selector = `.address-form .shipping_address select[name=${key}]`;
            } else {
                selector = `.address-form .shipping_address input[name=${key}]`;
            }

            if (key == 'billing_state') {
                window.updateState = function (selector) {
                    delete window.updateState;
                    $(selector).val(data['billing_state']).trigger('change');
                    $(`${selector} option[value=${data['billing_state']}]`).attr('selected','selected');
                }.bind(window, selector);
                updateState();
            } else if (key == 'billing_city') {
                window.updateCity = function (selector) {
                    delete window.updateCity;
                    $(selector).val(data['billing_city']).trigger('change');
                    $(`${selector} option[value=${data['billing_city']}]`).attr('selected','selected');
                }.bind(window, selector);
                // updateCity();
            } else if (key == 'billing_address_2') {
                window.updateAddress2 = function (selector) {
                    delete window.updateAddress2;
                    $(selector).val(data['billing_address_2']).trigger('change');
                    $(`${selector} option[value=${data['billing_address_2']}]`).attr('selected','selected');
                }.bind(window, selector);
                // updateAddress2();
            } else {
                if( key == 'address_is_default' && data[key] == 'on' ) {
                    $(selector).prop('checked', true);
                } else {
                    $(selector).val(data[key]).trigger('change');
                }
            }
        }
    },

    saveAddress: function() {
        let btnActionSave = $('.group-button .save-new-address');

        btnActionSave.off('click').click(function() {
            var validation = validateAddress.validate('.address-form');
        
            $('.woocommerce-error').remove();
    
            if (validation && validation.errors) {
                return mbCheckout.showError(validation.errors, '.address-form');
            }
    
            $('body').addClass('hangcu_loading');
    
            validation.data.add_new_saved_address_field = $('#add_new_saved_address_field').val();
    
            validation.data.shipping_account_address_action = 'save';
    
    
            validation.data.full_address = mbCheckout.joinFullAddress();
    
            $.ajax({
                url: hangcu_home_ajax + '?action=addaddress',
                method: 'POST',
                data: validation.data,
                success: function (res) {
                    validation.data.key = res.key;
                    location.href = '/thanh-toan';
                },
                error: function (err) {
                    mbCheckout.showError(err.responseJSON, '.address-form');
                    $('body').removeClass('hangcu_loading');
                }
            });
        })
    },

    updateAddress: function () {
        let btnActionUpdate = $('.group-button .update-address');
        btnActionUpdate.off('click').click(function() {
            var validation = validateAddress.validate('.address-form');

            $('.woocommerce-error').remove();

            if (validation && validation.errors) {
                return mbCheckout.showError(validation.errors, '.address-form');
            }

            $('body').addClass('hangcu_loading');

            validation.data.add_new_saved_address_field = $('#add_new_saved_address_field').val();
            validation.data.key_edit_address = update_key;

            validation.data.full_address = mbCheckout.joinFullAddress();

            $.ajax({
                url: hangcu_home_ajax + '?action=update_address',
                method: 'POST',
                data: validation.data,
                success: function (res) {
                    location.href = '/thanh-toan/?step=shipping';
                },
                error: function (err) {
                    $('body').removeClass('hangcu_loading');

                    mbCheckout.showError(err.responseJSON, '.address-form');
                }
            });
        });
    },

    showError: function (errors, selectorScrollTo) {
        let template = '<ul class="woocommerce-error">';

        if (errors && errors.messages && typeof errors.messages === 'string') {
            template += ['<li>', '</li>'].join(errors.messages);
        } else {
            for (let key in errors) {
                if (errors[key] && Array.isArray(errors[key])) {
                    template += ['<li>', '</li>'].join(errors[key][0]);
                } else {
                    template += ['<li>', '</li>'].join(errors[key]);
                }
            }
        }

        template += '</ul>';

        $(template).insertBefore('.address-form form > h2');

        return $('html,body').animate({ scrollTop: $(selectorScrollTo).offset().top }, 500, 'swing');
    },

    joinFullAddress: function () {
        var mapFieldAddress = ['billing_address_1', 'billing_address_2', 'billing_city', 'billing_state'],
            arrayAddress = [];

        mapFieldAddress.forEach(function (item, index) {
            if (item === 'billing_address_1') {
                arrayAddress.push($('#' + item).val());
            } else {
                arrayAddress.push($('#' + item + ' option:selected').text());
            }
        });

        return arrayAddress.join(', ');
    }

}

module.exports = mbCheckout;
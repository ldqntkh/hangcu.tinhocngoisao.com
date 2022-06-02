'use strict';
const validateAddress = require('../helper/validate-address');
const $ = jQuery;
const gearvn_home_ajax= '/wp-admin/admin-ajax.php';
var checkoutAddress = {
    init: function () {
        this.initSelectAddressSaved();
        this.initVnAddressCheckout();
        this.initializeEvents();
        this.bindEventAddress();
    },
    initSelectAddressSaved: function () {
        $('body').on('click', '.select-saved-address', function (e) {
            e.preventDefault();
            var key = $(this).attr('data-key');
            var $value = JSON.parse($('#' + key).val());

            for (var index in $value) {
                if ($('#' + index).is('select')) {
                    if (index == 'billing_city') {
                        window.updateCity = function () {
                            $('#billing_city').val($value['billing_city']).trigger('change');
                        }
                    } else if (index == 'billing_address_2') {
                        window.updateAddress2 = function () {
                            $('#billing_address_2').val($value['billing_address_2']).trigger('change');
                        }
                    } else {
                        $('#' + index).val($value[index]).trigger('change');
                    }

                } else {
                    $('#' + index).val($value[index]);
                }
            }
            $('a[href=#billing-form-details]').trigger('click');
        });
    },

    initVnAddressCheckout: function () {
        if (typeof devvn_array === 'undefined') {
            return;
        }

        var $defaultSetting = {
            formatNoMatches: devvn_array.formatNoMatches,
        };
        var loading_billing = false;
        var billing_city_field = $('#my-address-details #billing_city_field');
        var billing_address_2_field = $('#my-address-details #billing_address_2_field');
        //billing
        $('#my-address-details #billing_state').select2($defaultSetting);
        $('#my-address-details #billing_city').select2($defaultSetting);
        $('#my-address-details #billing_address_2').select2($defaultSetting);

        $('#my-address-details #billing_state').on('change select2-selecting', function (e) {
            $("#my-address-details #billing_city option").val('');
            var matp = e.val;
            if (!matp) matp = $("#my-address-details #billing_state option:selected").val();
            if (matp && !loading_billing) {
                loading_billing = true;
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: devvn_array.admin_ajax,
                    data: { action: "load_diagioihanhchinh", matp: matp },
                    context: this,
                    beforeSend: function () {
                        jQuery('body').addClass('gearvn_loading');
                    },
                    success: function (response) {
                        loading_billing = false;
                        $("#my-address-details #billing_city,#my-address-details #billing_address_2").html('').select2();
                        if (response.success) {
                            var listQH = response.data;
                            var newState = new Option('', '');
                            $("#my-address-details #billing_city").append(newState);
                            $.each(listQH, function (index, value) {
                                var newState = new Option(value.name, value.maqh);
                                $("#my-address-details #billing_city").append(newState);
                            });
                            if (typeof window.updateUserCity !== 'undefined') {
                                window.updateUserCity();
                            }
                        }
                        jQuery('body').removeClass('gearvn_loading');
                    }
                });
            }
        });

        if ($('#my-address-details #billing_address_2').length > 0) {
            $('#my-address-details #billing_city').on('change select2-selecting', function (e) {
                var maqh = e.val;
                if (!maqh) maqh = $("#my-address-details #billing_city option:selected").val();
                if (maqh) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: devvn_array.admin_ajax,
                        data: { action: "load_diagioihanhchinh", maqh: maqh },
                        context: this,
                        beforeSend: function () {
                            jQuery('body').addClass('gearvn_loading');
                        },
                        success: function (response) {
                            $("#my-address-details #billing_address_2").html('').select2($defaultSetting);
                            if (response.success) {
                                var listQH = response.data;
                                var newState = new Option('', '');
                                $("#my-address-details #billing_address_2").append(newState);
                                $.each(listQH, function (index, value) {
                                    var newState = new Option(value.name, value.xaid);
                                    $("#my-address-details #billing_address_2").append(newState);
                                });
                                if (typeof window.updateUserAddress2 !== 'undefined') {
                                    window.updateUserAddress2();
                                }
                            }
                            jQuery('body').removeClass('gearvn_loading');
                        }
                    });
                }
            });
        }

        if (typeof address_edit !== 'undefined') {
            for (var index in address_edit) {
                if ($('#my-address-details #' + index).is('select')) {
                    if (index == 'billing_city') {
                        window.updateUserCity = function () {
                            $('#my-address-details #billing_city').val(address_edit['billing_city']).trigger('change');
                        }
                    } else if (index == 'billing_address_2') {
                        window.updateUserAddress2 = function () {
                            $('#my-address-details #billing_address_2').val(address_edit['billing_address_2']).trigger('change');
                        }
                    } else {
                        $('#my-address-details #' + index).val(address_edit[index]);
                    }

                } else {
                    $('#my-address-details #' + index).val(address_edit[index]);
                }
            }
        }
        $('#my-address-details #billing_state').trigger('change');
    },

    initializeEvents: function () {
        // $('.remove-address').off('click').on('click', function (e) {
        //     e.preventDefault();
        //     var removeAddressForm = $('.remove-address-form');
        //     var addressHiddenField = $(this).parents('.address-content').siblings();
        //     $('.list-address').find('.address-hidden-field').not($(addressHiddenField)).each(function (index) {
        //         removeAddressForm.find('.shipping_address_hidden_field').append('<div id="shipping_address_hidden_field_' + index + '"></div>');
        //         $(this).clone().appendTo('#shipping_address_hidden_field_' + index);
        //     });
        //     if (confirm('Bạn có chắc là muốn xóa?')) {
        //         $('.remove-address-form').submit();
        //     } else {
        //         $('.remove-address-form').find('.shipping_address_hidden_field').empty();
        //     }
        // });

        $('.add-new-address').off('click').on('click', function (e) {
            e.preventDefault();
            var address_form = document.getElementsByClassName('new-address');
            if (!$('.update-address-form').hasClass('hidden')) {
                $('.update-address-form').addClass('hidden');
            }
            if (address_form.length > 0) {
                if (address_form[0].className.indexOf('hidden') > 0) {
                    address_form[0].className = address_form[0].className.replace('hidden', '');
                } else {
                    address_form[0].className += " hidden";
                }
            }

            var addresses = $('.new-address').find('#addresses');
            $('.list-address').find('.address-hidden-field').each(function (index) {
                $('.shipping_address_hidden_field_' + index).remove();
                if ($('.shipping_address_hidden_field_' + index).length === 0) {
                    addresses.append('<div class="shipping_address_hidden_field_' + index + '"></div>');
                    $(this).clone().appendTo('.shipping_address_hidden_field_' + index);
                }
            });
        });

        $('.deliver-address').off('click').on('click', function () {
            var key = jQuery(this).attr('data-key');
            // var addressContent = $(this).parents('.address-content');
            // addressContent.siblings().find('input[name="shipping_address_is_selected[]"]').val('true');
            // $('.list-address').find('.address-hidden-field').each(function (index) {
            //     $('.form-shipping-address').find('.shipping_address_hidden').append('<div class="shipping_address_hidden_field_' + index + '"></div>');
            //     $(this).clone().appendTo('.shipping_address_hidden_field_' + index);
            // });
            jQuery('input[name=shipping_address_key_selected]').val(key);
            jQuery('body').addClass('gearvn_loading');
            $('.form-shipping-address').submit();
        });
    },
    bindingHiddenFieldToForm: function () {
        var addresses = $('.new-address').find('#addresses');
        $('.list-address').find('.address-hidden-field').each(function (index) {
            if ($('.shipping_address_hidden_field_' + index).length === 0) {
                addresses.append('<div class="shipping_address_hidden_field_' + index + '"></div>');
                $(this).clone().appendTo('.shipping_address_hidden_field_' + index);
            }
        });
    },
    bindEventAddress: function () {
        if (!jQuery('.page-template-checkout .checkout-address .list-address').length) {
            jQuery(document).on('click', '.address-form .save-new-address', function () {
                checkoutAddress.addAddress();
            });
        }

        jQuery(document).on('click', '.page-template-checkout .add-new-address', function (e) {
            var titleElement = jQuery('.address-form .title-block'),
                btnActionSave = jQuery('.address-form .save-new-address');

            e.preventDefault();

            if (btnActionSave.attr('data-key')) {
                jQuery('.address-form.new-address').show();
                jQuery('.address-form.new-address').removeClass('hidden');
            } 
            // else {
            //     jQuery('.address-form.new-address').toggle();
            // }

            module.exports.resetForm();

            titleElement.text(titleElement.attr('data-text-add'));

            btnActionSave.text(btnActionSave.attr('data-text-add')).attr('data-key', '');

            btnActionSave.off('click').click(function () {
                checkoutAddress.addAddress();
            });

            jQuery('html,body').animate({ scrollTop: jQuery('.address-form.new-address').offset().top }, 500, 'swing');
        });

        jQuery(document).on('click', '.list-address .update-address', function (e) {
            var data = jQuery(this).attr('data-value') || '',
                titleElement = jQuery('.address-form .title-block'),
                btnUpdateElement = jQuery('.address-form .save-new-address');

            if (!data) {
                return location.reload();
            }

            btnUpdateElement.attr('data-key', jQuery(this).attr('data-key'));

            jQuery('.woocommerce-error').remove();

            titleElement.text(titleElement.attr('data-text-edit'));

            btnUpdateElement.text(btnUpdateElement.attr('data-text-edit'));

            module.exports.resetForm();

            btnUpdateElement.off('click').click(function () {
                module.exports.updateAddress();
            });

            data = JSON.parse(data);

            setTimeout(function () {
                module.exports.handleFillDataUpdate(data);
            }, 0);
        });

        jQuery(document).on('click', '.checkout-address .group-button .cancel', function (e) {
            e.preventDefault();

            module.exports.resetForm();
            jQuery('.address-form .save-new-address').attr('data-key', '');

            jQuery('.address-form.new-address').toggle();

            jQuery('html,body').animate({ scrollTop: jQuery('#content').offset().top }, 500, 'swing');
        });

        jQuery(document).on('click', '.checkout-address .group-button .remove-address', function () {
            jQuery('#deleteAddress .modal-footer .btn-delete-address').attr('href', jQuery('#deleteAddress .modal-footer .btn-delete-address').attr('data-href') + '?delete-address=' + jQuery(this).attr('data-key'));

            jQuery('#deleteAddress').modal('show');
        });

        jQuery(document).on('click', '#deleteAddress .modal-footer .btn-delete-address', function () {
            jQuery('#deleteAddress').modal('hide');

            jQuery('body').addClass('gearvn_loading');
        });
    },
    handleFillDataUpdate: function (data) {
        let mapKey = {
            billing_first_name: 'billing_first_name',
            billing_last_name: 'billing_last_name',
            billing_phone: 'billing_phone',
            billing_state: 'billing_state',
            billing_city: 'billing_city',
            billing_address_1: 'billing_address_1',
            billing_address_2: 'billing_address_2'
        },
            selector = '';

        jQuery('.address-form.new-address').show();
        jQuery('.address-form.new-address').removeClass('hidden');
        jQuery('html,body').animate({ scrollTop: jQuery('.address-form.new-address').offset().top }, 500, 'swing');

        for (let key in mapKey) {
            if (key === 'billing_city' || key === 'billing_state' || key === 'billing_address_2') {
                selector = `.address-form .shipping_address select[name=${key}]`;
            } else {
                selector = `.address-form .shipping_address input[name=${key}]`;
            }

            if (key == 'billing_city') {
                window.updateCity = function (selector) {
                    delete window.updateCity;

                    jQuery(selector).val(data['billing_city']).trigger('change');
                }.bind(window, selector);
            } else if (key == 'billing_address_2') {
                window.updateAddress2 = function (selector) {
                    delete window.updateAddress2;

                    jQuery(selector).val(data['billing_address_2']).trigger('change');
                }.bind(window, selector);
            } else {
                jQuery(selector).val(data[key]).trigger('change');
            }
        }
    },
    addAddress: function () {
        var validation = validateAddress.validate('.custom-checkout');

        jQuery('.woocommerce-error').remove();

        if (validation && validation.errors) {
            return module.exports.showError(validation.errors, '.address-form');
        }

        jQuery('body').addClass('gearvn_loading');

        validation.data.add_new_saved_address_field = jQuery('#add_new_saved_address_field').val();

        validation.data.shipping_account_address_action = 'save';


        validation.data.full_address = module.exports.joinFullAddress();

        jQuery.ajax({
            url: gearvn_home_ajax + '?action=addaddress',
            method: 'POST',
            data: validation.data,
            success: function (res) {
                validation.data.key = res.key;

                checkoutAddress.fillNewAddress(validation.data);
            },
            error: function (err) {
                module.exports.showError(err.responseJSON, '.address-form');

                jQuery('body').removeClass('gearvn_loading');
            }
        });
    },
    fillNewAddress: function (data) {
        var template = '<input type="hidden" name="shipping_account_address_action" value="save"/>';

        template += `<input type="hidden" name="shipping_address_key_selected" value="${data.key}"/>`;
        template += `<input type="hidden" name="shipping_address_is_default" value="true"/>`;
        template += `<input type="hidden" name="shipping_address_is_selected" value="true"/>`;

        jQuery('.form-shipping-address').append(template);

        jQuery('.form-shipping-address').submit();
    },
    updateAddress: function () {
        var validation = validateAddress.validate('.custom-checkout');

        jQuery('.woocommerce-error').remove();

        if (validation && validation.errors) {
            return module.exports.showError(validation.errors, '.address-form');
        }

        jQuery('body').addClass('gearvn_loading');

        validation.data.add_new_saved_address_field = jQuery('#add_new_saved_address_field').val();
        validation.data.key_edit_address = jQuery('.address-form .save-new-address').attr('data-key');

        validation.data.full_address = module.exports.joinFullAddress();

        jQuery.ajax({
            url: gearvn_home_ajax + '?action=update_address',
            method: 'POST',
            data: validation.data,
            success: function (res) {
                location.reload();
            },
            error: function (err) {
                if (err && err.needReloadPage) {
                    location.reload();
                } else {
                    jQuery('body').removeClass('gearvn_loading');

                    module.exports.showError(err.responseJSON, '.address-form');
                }
            }
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

        jQuery(template).insertBefore('.address-form form > h2');

        return jQuery('html,body').animate({ scrollTop: jQuery(selectorScrollTo).offset().top }, 500, 'swing');
    },
    resetForm: function () {
        let mapKey = ['billing_first_name', 'billing_last_name', 'billing_phone', 'billing_state', 'billing_city', 'billing_address_1', 'billing_address_2'],
            selector = '';

        mapKey.forEach(function (item, index) {
            if (item === 'billing_state') {
                return null;
            }
            if (item === 'billing_city' || item === 'billing_address_2') {
                selector = `.address-form .shipping_address select[name=${item}]`;
            } else {
                selector = `.address-form .shipping_address input[name=${item}]`;
            }

            jQuery(selector).val('').trigger('change');
        });
    },
    joinFullAddress: function () {
        var mapFieldAddress = ['billing_address_1', 'billing_address_2', 'billing_city', 'billing_state'],
            arrayAddress = [];

        mapFieldAddress.forEach(function (item, index) {
            if (item === 'billing_address_1') {
                arrayAddress.push(jQuery('#' + item).val());
            } else {
                arrayAddress.push(jQuery('#' + item + ' option:selected').text());
            }
        });

        return arrayAddress.join(', ');
    }
}

module.exports = checkoutAddress;
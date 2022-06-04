const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
module.exports = {
  idSetInterval: null,
  isCountDown: false,
  init: function () {
    this.bindEventOTP();
    this.bindEventGoBackNavigation();
    this.bindEventSubmitFormRegister();
    this.bindEventValidPhonePopup();
    this.removePopupVerifyPhone();
  },
  bindEventOTP: function () {
    let phoneSchema = {
      phone: {
        presence: {
          message: '^<strong>Số điện thoại</strong> không để trống'
        },
        length: {
          minimum: 1,
          message: '^<strong>Số điện thoại</strong> không để trống'
        },
        format: {
          pattern: "(09|03|07|08|05)+([0-9]{8}$)",
          flags: "i",
          message: "^<strong>Số điện thoại</strong> không hợp lệ"
        }
      }
    },
      dataValidate = {},
      errors = null;

    jQuery(document).on('click', '.phone-wrapper > button.btn-send-opt-verify', function (e) {
      e.preventDefault();

      if (module.exports.isCountDown) {
        return null;
      }

      jQuery('.woocommerce-notices-wrapper .woocommerce-error').remove();

      dataValidate.phone = jQuery(this).closest('.phone-wrapper').find('input').val();

      errors = validate(dataValidate, phoneSchema);

      if (errors) {
        return module.exports.showError(errors, '.woocommerce-notices-wrapper');
      }

      module.exports.sendOTP(dataValidate.phone);
    });
  },
  sendOTP: function (phone) {
    jQuery('body').addClass('hangcu_loading');
    jQuery('.phone-wrapper .text-danger').remove();

    jQuery.ajax({
      url: hangcu_home_ajax,
      method: 'POST',
      data: {
        action: 'createotpcode',
        phone_number: phone
      },
      success: function (res) {
        var message = '';

        jQuery('body').removeClass('hangcu_loading');

        if (res.success) {
          return module.exports.countDownResend();
        }

        if (res.data && res.data.errMsg) {
          message = res.data.errMsg;
        } else {
          message = 'Đã có lỗi xảy ra';
        }

        jQuery('.phone-wrapper').append(`<span class="text-danger">${message}</span>`);
      },
      error: function (err) {
        jQuery('body').removeClass('hangcu_loading');

        module.exports.showError(err.responseJSON, '.woocommerce-notices-wrapper');
      }
    });
  },
  countDownResend: function () {
    let totalSecond = 30,
      btnElement = jQuery('.phone-wrapper button.btn-send-opt-verify'),
      template = (totalSecond) => ['<div class="time-wrapper"><p>', btnElement.attr('data-text-retry'), ' (', totalSecond, 's)</p><p>', btnElement.attr('data-text-retry-time'), '</p></div>'].join('');

    module.exports.isCountDown = true;

    if (this.idSetInterval) {
      clearInterval(this.idSetInterval);
    }

    btnElement.addClass('count-down');

    btnElement.html(template(totalSecond));

    this.idSetInterval = setInterval(() => {
      if (totalSecond) {
        totalSecond--;

        btnElement.html(template(totalSecond));
      } else {
        btnElement.text(btnElement.attr('data-text'));
        btnElement.removeClass('count-down');
        module.exports.isCountDown = false;

        clearInterval(this.idSetInterval);
      }
    }, 1000);
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

    jQuery('.woocommerce-notices-wrapper').append(template);
    if( jQuery(selectorScrollTo).length > 0 ){
      return jQuery('html,body').animate({ scrollTop: jQuery(selectorScrollTo).offset().top }, 500, 'swing');
    }
      
  },
  bindEventGoBackNavigation: function () {
    jQuery(document).on('click', '.woocommerce .hangcu-header-my-account .go-back-page', function (e) {
      e.preventDefault();

      history.back();
    });
  },
  bindEventSubmitFormRegister: function () {
    var customerPhone = '';

    jQuery(document).delegate('.woocommerce-form-register .woocommerce-form-register__submit', 'click', function () {
      customerPhone = jQuery('input[name=customer_mobile_phone]').val();

      jQuery('form.woocommerce-form-register').append('<input name="username" type="hidden" value="' + customerPhone + '"/>');
    });
  },
  bindEventValidPhonePopup: function() {
    var form_submit = jQuery('.popup-verify-account-phone-number .form-verify-phone');
    if ( form_submit.length > 0 ) {
      form_submit.on('submit', function(e) {
        e.preventDefault();

        var form = jQuery(form_submit[0]);
        var phone = form.find('#verify_customer_mobile_phone').val().trim();
        var otp_code = form.find('#otp_code').val().trim();

        if ( phone == '' || otp_code == '' ) return false;
        jQuery('body').addClass('hangcu_loading');
        jQuery('.phone-wrapper .text-danger').remove();
        jQuery('.form-row-otp .text-danger').remove();
        jQuery.ajax({
          url: hangcu_home_ajax,
          method: 'POST',
          data: {
            action: 'verifyphone',
            phone_number: phone,
            otp_code: otp_code
          },
          success: function (res) {
            jQuery('body').removeClass('hangcu_loading');
    
            if (res.success) {
              jQuery('.popup-verify-account-phone-number').remove();
              window.location.href = window.location.origin;
              return;
            } else {
              let err = "Mã xác thực không chính xác";
              if( res.data && res.data.errMsg ) {
                err = res.data.errMsg;
              }
              jQuery('.form-row-otp').append(`<span class="text-danger">${err}</span>`);
            }
          },
          error: function (err) {
            jQuery('body').removeClass('hangcu_loading');
    
            module.exports.showError(err.responseJSON, '.woocommerce-notices-wrapper');
          }
        });

      });
    }
  },

  removePopupVerifyPhone: function() {
    jQuery('body').on('click', '#close-popup-phone', function () {
      jQuery('.popup-verify-account-phone-number').remove();
    });
  }
};
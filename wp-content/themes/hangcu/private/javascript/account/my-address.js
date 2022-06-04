const Address = require('../checkout/address');

const schemaAddress = {
  fullname: {
    presence: {
      message: '^<strong>Họ tên</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Họ tên</strong> không để trống'
    }
  },
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
      message: "^Số điện thoại</strong> không hợp lệ"
    }
  },
  city: {
    presence: {
      message: '^<strong>Thành phố</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Thành phố</strong> không để trống'
    }
  },
  district: {
    presence: {
      message: '^<strong>Quận huyện</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Quận huyện</strong> không để trống'
    }
  },
  ward: {
    presence: {
      message: '^<strong>Phường xã</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Phường xã</strong> không để trống'
    }
  },
  address: {
    presence: {
      message: '^<strong>Địa chỉ</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Địa chỉ</strong> không để trống'
    }
  }
};

module.exports = {
  init: function () {
    module.exports.deleteAddress();
    module.exports.saveAddress();
  },
  deleteAddress: function () {
    let urlDeleteAddress = '';

    jQuery(document).on('click', '#modalDeleteAddress .modal-footer .btn-delete-address', function (e) {
      e.preventDefault();

      jQuery('body').addClass('hangcu_loading');

      jQuery('#modalDeleteAddress').modal('hide');

      urlDeleteAddress = jQuery('.woocommerce-MyAccount-content #saved-addresses .delete-address').attr('href');

      if (urlDeleteAddress) {
        return location.href = urlDeleteAddress;
      }

      return location.reload();
    });
  },
  saveAddress: function () {
    let mappingField = {
      'billing_last_name': 'fullname',
      'billing_phone': 'phone',
      'billing_state': 'city',
      'billing_city': 'district',
      'billing_address_2': 'ward',
      'billing_address_1': 'address'
    },
      objValidate = {},
      selector = '',
      errors = null,
      template = '';

    jQuery(document).on('click', '#my-address-details button[type=submit], .woocommerce-checkout button[type=submit]', function (e) {
      if (jQuery(this).attr('name') == 'login' || jQuery(this).attr('name') == 'register') {
        return;
      }

      jQuery('#full_address').val(Address.joinFullAddress());

      e.preventDefault();

      for (let key in mappingField) {
        if ((key === 'billing_state' || key === 'billing_city' || key === 'billing_address_2') && !jQuery(this).parents('.checkout').length) {
          selector = `select[name=${key}]`;
        } else {
          selector = `input[name=${key}]`;
        }
        objValidate[mappingField[key]] = jQuery(selector).val();
      }

      errors = validate(objValidate, schemaAddress);

      jQuery('.woocommerce-error').remove();

      if (errors) {
        template = '<ul class="woocommerce-error" role="alert">';
        for (let key in errors) {
          if (errors[key]) {
            template += `<li>${errors[key][0]}</li>`;
          }
        }

        template += '</ul>';

        jQuery(template).insertBefore(jQuery(this).parents('form'));

        jQuery('html, body').stop().animate({ scrollTop: jQuery(this).parents('.woocommerce').offset().top }, 500, 'swing');
        return;
      }

      if (!jQuery(this).parents('form').hasClass('checkout')) {
        jQuery('body').addClass('hangcu_loading');
      }

      jQuery(this).parents('form').submit();
    });
  }
};
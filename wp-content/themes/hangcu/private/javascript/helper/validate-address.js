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
  validate: function (selectorWrapper) {
    let mappingField = {
      'billing_last_name': 'fullname',
      'billing_phone': 'phone',
      'billing_email': 'email',
      'billing_state': 'city',
      'billing_city': 'district',
      'billing_address_2': 'ward',
      'billing_address_1': 'address',
      'address_is_default': 'default_address'
    },
    data = {},
    objValidate = {},
      selector = '',
      errors = null;

    for (let key in mappingField) {
      if ((key === 'billing_state' || key === 'billing_city' || key === 'billing_address_2')) {
        selector = `select[name=${key}]`;
      } else {
        selector = `input[name=${key}]`;
      }

      if (selectorWrapper) {
        selector = [selectorWrapper, selector].join(' ');
      }
      if( key == 'address_is_default' ) {
        objValidate[mappingField[key]] = jQuery(selector).is(":checked") ? 'on' : 'off';
        data[key] = jQuery(selector).is(":checked") ? 'on' : 'off';
      } else {
        objValidate[mappingField[key]] = jQuery(selector).val();
        data[key] = jQuery(selector).val();
      }
    }

    errors = validate(objValidate, schemaAddress);

    return {
      errors: errors,
      data: data
    };
  }
};
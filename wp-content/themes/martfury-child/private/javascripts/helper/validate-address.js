var validate = require("validate.js");
const schemaAddress = {
  first_name: {
    presence: {
      message: '^<strong>Tên</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Tên</strong> không để trống'
    }
  },
  last_name: {
    presence: {
      message: '^<strong>Họ</strong> không để trống'
    },
    length: {
      minimum: 1,
      message: '^<strong>Họ</strong> không để trống'
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
      'billing_first_name': 'first_name',
      'billing_last_name': 'last_name',
      'billing_phone': 'phone',
      'billing_state': 'city',
      'billing_city': 'district',
      'billing_address_2': 'ward',
      'billing_address_1': 'address'
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

      objValidate[mappingField[key]] = jQuery(selector).val();
      data[key] = jQuery(selector).val();
    }

    errors = validate(objValidate, schemaAddress);

    return {
      errors: errors,
      data: data
    };
  }
};
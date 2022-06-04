module.exports = {
  is_sync_order: false,
  isCopping: false,
  init: function () {
    this.syncWithBanHang();
    this.bindEventCopyOrder();
  },
  syncWithBanHang: function () {
    jQuery(document).on('click', '.btn-sync-with-ban-hang', function (e) {
      var orderId = jQuery(this).attr('data-order-id');

      e.preventDefault();

      if (module.exports.is_sync_order) {
        return;
      }

      module.exports.is_sync_order = true;

      jQuery(this).find('span').addClass('spinner').addClass('is-active');
      let that = this;
      let success = false;
      jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
          action: 'ajax_sync_order_banhang_id_func',
          order_id: orderId
        },
        success: function (res) {
          // if (res && res.message) {
          //   alert(res.message);
          // } else {
          //   // alert("Đã có lỗi xảy ra. Vui lòng liên hệ kỹ thuật để được xử lý");
          // }
          success = true;
        },
        error: function (err) {
          if (err && err.message) {
            alert(err.message);
          } else {
            alert("Đã có lỗi xảy ra. Vui lòng liên hệ kỹ thuật để được xử lý");
          }
        },
        complete: function () {
          if( success ) {
            location.reload();
          } else {
            jQuery(that).find('span').removeClass('spinner').removeClass('is-active');
            module.exports.is_sync_order = false;
          }
        }
      });
    });
  },
  bindEventCopyOrder: function () {
    jQuery(document).delegate('.order_actions .copy-order-wrapper .btn-copy-order', 'click', function (e) {
      e.preventDefault();

      if (module.exports.isCopping) {
        return;
      }

      module.exports.isCopping = true;

      jQuery('<span class="spinner is-active"></span>').insertBefore('li.copy-order-wrapper .btn-copy-order');

      jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
          action: 'copy_order_for_admin',
          order_id: jQuery(this).attr('data-order-id')
        },
        success: function (res) {
          if (res.link) {
            alert(`Đơn hàng đã được sao chép. Đơn hàng mới là ${res.new_order_id}. Hệ thống tự chuyển đến đơn hàng mới`);
            return location.href = res.link;
          }

          alert("Đã có lỗi xảy ra. Vui lòng liên hệ kỹ thuật để được xử lý");
          location.reload();
        },
        error: function (err) {
          if (err && err.message) {
            return alert(err.message);
          }

          alert("Đã có lỗi xảy ra. Vui lòng liên hệ kỹ thuật để được xử lý");

          location.reload();
        },
        complete: function () {
          module.exports.isCopping = false;
          jQuery('li.copy-order-wrapper .spinner.is-active').remove();
        }
      });
    });
  }
};
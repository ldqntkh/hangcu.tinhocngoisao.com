'use strict';

const myOrder = {
    ajaxCancel: null,
    init: function () {
        // jQuery(document).on('click', 'a.pending-cancel', function (e) {
        //     jQuery('#cancel-order-title').text("Hủy đơn hàng số " + jQuery(this).attr('href').split('_')[1]);
        //     jQuery('#order-cancel-id').val(jQuery(this).attr('href').split('_')[1]);
        //     jQuery('#modalCancelOrder').modal('show');
        // });

        // jQuery(document).on('click', '#btn-cancel-order', function () {
        //     if (myOrder.ajaxCancel !== null) return;
        //     var cancelValue = jQuery('#select-cancel-value').val().trim();
        //     var cancelNote = jQuery('#input-cancel-note').val().trim();
        //     var cancelError = jQuery('#cancel-order-error');
        //     if (cancelValue == "") {
        //         cancelError.text('Vui lòng chọn lý do hủy đơn!');
        //         return;
        //     }
        //     if (cancelNote == "") {
        //         cancelError.text('Vui lòng cho biết lý do của bạn!');
        //         return;
        //     }
        //     cancelError.text('');
        //     myOrder.ajaxCancel = jQuery.ajax({
        //         type: 'post',
        //         //dataType : 'json',
        //         url: order_request_cancel_url,
        //         data: {
        //             action: 'pendingcancelorder',
        //             order_id: jQuery('#order-cancel-id').val(),
        //             order_cancel_value: cancelValue,
        //             order_cancel_note: cancelNote
        //         },
        //         beforeSend: function () {
        //             jQuery('body').addClass('hangcu_loading');
        //         },
        //         success: function (response) {
        //             if (response.success) {
        //                 window.location.reload();
        //             } else {
        //                 cancelError.text(response.data.errMsg);
        //                 myOrder.ajaxCancel = null;
        //                 jQuery('body').removeClass('hangcu_loading');
        //             }
        //         },
        //         error: function (response, errorStatus, errorMsg) {
        //             jQuery('body').removeClass('hangcu_loading');
        //             myOrder.ajaxCancel = null;

        //             if (errorStatus) {
        //                 console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
        //             }

        //         }
        //     });
        // });
    }
}

export default myOrder;
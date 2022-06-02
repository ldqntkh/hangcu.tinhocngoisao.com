'use strict';
const $ = jQuery;
var productType = {
    productTypeData: null,

    insertProductType: function () {
        let value = $('#type_name').val();
        if (!value || value.trim() === '') return;

        jQuery('body').addClass('gearvn_loading');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'addproducttype', type_name: value.trim() },
            success: function (response) {
                if (response.success) {
                    location.search = location.search.replace(/paged=[^&$]*/i, 'paged=1');
                }

                jQuery('body').removeClass('gearvn_loading');
            },
            error: function (response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }

                jQuery('body').removeClass('gearvn_loading');
            }
        });
    },

    deleteProductType: function (e) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        productType.showPopupHandleProductType('delete');
        productType.productTypeData = JSON.parse($(this).attr('data-remove'));
    },

    acceptDeleteProductType: function () {
        if (!productType.productTypeData.id) return;

        jQuery('body').addClass('gearvn_loading');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'removeproducttype', type_id: productType.productTypeData.id },
            success: function (response) {
                if (response.success) {
                    return location.reload();
                }

                jQuery('body').removeClass('gearvn_loading');
            },
            error: function (response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }

                jQuery('body').removeClass('gearvn_loading');
            }
        });
    },

    editProductType: function () {
        productType.showPopupHandleProductType('edit');
        productType.productTypeData = JSON.parse($(this).attr('data-edit'));
        $('#type_id').val(productType.productTypeData.id);
        $('#type_name').val(productType.productTypeData.product_type_name);
        // update 
        $(document).off('click', '#save-product-type').on('click', '#save-product-type', productType.acceptEditProductType);
    },

    acceptEditProductType: function () {
        if (!productType.productTypeData.id) return;
        let value = $('#type_name').val();
        if (!value || value.trim() === '') return;
        productType.productTypeData.id = parseInt(productType.productTypeData.id);
        productType.productTypeData.product_type_name = value;
        jQuery('body').addClass('gearvn_loading');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'updateproducttype', product_type: productType.productTypeData },
            success: function (response) {
                if (response.success) {
                    return location.reload();
                }

                jQuery('body').removeClass('gearvn_loading');
            },
            error: function (response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }

                jQuery('body').removeClass('gearvn_loading');
            }
        });
    },

    closeModal: function () {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.removeClass('active');
        productType.productTypeData = null;
    },

    showPopupHandleProductType: function (type = 'add-new') {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.addClass('active');
        $('#modal-product-type h3').addClass('hide');
        $('#modal-product-type #delete-product-type').addClass('hide');
        $('#modal-product-type #save-product-type').addClass('hide');
        $('#modal-product-type .form-msg').addClass('hide');
        if (type === 'add-new') {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type h3.add-new-product-type').removeClass('hide');
            $('#modal-product-type #save-product-type').removeClass('hide');
            $('#product-type-id').addClass('hide');
            $('#type_id').val('');
            $('#type_name').val('');
        } else if (type === 'edit') {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type h3.edit-product-type').removeClass('hide');
            $('#modal-product-type #save-product-type').removeClass('hide');
            $('#product-type-id').removeClass('hide');
        } else {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type .form-group').addClass('hide');
            $('#modal-product-type h3.delete-product-type').removeClass('hide');
            $('#modal-product-type .form-msg').removeClass('hide');
            $('#modal-product-type #delete-product-type').removeClass('hide');
        }
    },

    init: function () {
        var that = this;
        $(document).on('click', '#add-new-product-type', function (e) {
            e.preventDefault();
            that.showPopupHandleProductType();
            // insert 
            $(document).off('click', '#save-product-type').on('click', '#save-product-type', productType.insertProductType);
        });

        // close modal
        $(document).on('click', '#close-modal-product-type, #cancel-product-type', that.closeModal);
        // delete popup 
        $(document).on('click', '.remove-product-type', that.deleteProductType);

        // delete
        $(document).on('click', '#delete-product-type', that.acceptDeleteProductType);

        // edit popup 
        $(document).on('click', '.edit-product-type', that.editProductType);
    }
}

module.exports = productType;
'use strict';
const $ = jQuery;
var group_attribute = {
    groupAttributeData: null,

    insertGroupAttribute: function () {
        let group_id = $('#group_id').val(),
            group_name = $('#group_name').val(),
            group_desc = $('#group_desc').val(),
            product_type = $('#product_type').val(),
            display_index = $('#display_index').val();
        if (!group_id || group_id.trim() === '' || !group_name || group_name.trim() === '' || !product_type) return;
        if (!display_index || display_index < 0) display_index = 0;
        else display_index = parseInt(display_index);
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'addgroupattribute', group_attribute: {
                    "group_id": group_id,
                    "group_name": group_name,
                    "group_desc": group_desc,
                    "product_type": product_type,
                    "display_index": display_index
                }
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
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

    deleteGroupAttribute: function (e) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        group_attribute.showPopupHandleGroupProduct('delete');
        group_attribute.groupAttributeData = JSON.parse($(this).attr('data-remove'));
    },

    acceptDeleteGroupAttribute: function () {
        if (!group_attribute.groupAttributeData.group_id) return;

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'removegroupattribute', data: {
                    "group_id": group_attribute.groupAttributeData.group_id,
                    "product_type": group_attribute.groupAttributeData.product_type
                }
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
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

    editGroupAttribute: function () {
        group_attribute.showPopupHandleGroupProduct('edit');
        group_attribute.groupAttributeData = JSON.parse($(this).attr('data-edit'));
        $('#group_id').val(group_attribute.groupAttributeData.group_id);
        $('#group_name').val(group_attribute.groupAttributeData.group_name);
        $('#group_desc').val(group_attribute.groupAttributeData.group_desc);
        $('#product_type').val(group_attribute.groupAttributeData.product_type);
        $('#display_index').val(group_attribute.groupAttributeData.display_index);
        $(document).off('click', '#save-group-attribute').on('click', '#save-group-attribute', group_attribute.acceptEditGroupAttribute);
    },

    acceptEditGroupAttribute: function () {
        if (!group_attribute.groupAttributeData.group_id) return;
        let group_name = $('#group_name').val(),
            group_desc = $('#group_desc').val(),
            product_type = $('#product_type').val(),
            display_index = $('#display_index').val();
        if (!group_name || group_name.trim() === '' || !product_type) return;
        if (!display_index || display_index < 0) display_index = 0;
        else display_index = parseInt(display_index);

        group_attribute.groupAttributeData.group_name = group_name;
        group_attribute.groupAttributeData.group_desc = group_desc;
        group_attribute.groupAttributeData.product_type = product_type;
        group_attribute.groupAttributeData.display_index = display_index;
        jQuery('body').addClass('gearvn_loading');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'updategroupattribute', group_attribute: group_attribute.groupAttributeData },
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
        group_attribute.groupAttributeData = null;
    },

    showPopupHandleGroupProduct: function (type = 'add-new') {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.addClass('active');
        $('#modal-product-type h3').addClass('hide');
        $('#modal-product-type #delete-group-attribute').addClass('hide');
        $('#modal-product-type #save-group-attribute').addClass('hide');
        $('#modal-product-type .form-msg').addClass('hide');
        if (type === 'add-new') {
            $('#modal-product-type h3.add-new-group-attribute').removeClass('hide');
            $('#modal-product-type #save-group-attribute').removeClass('hide');
            $('#attribute-id').addClass('hide');
            $('#group_id').val('');
            $('#group_name').val('');
            $('#group_desc').val('');
            $('#product_type').val('');
            $('#display_index').val('');
        } else if (type === 'edit') {
            $('#modal-product-type h3.edit-group-attribute').removeClass('hide');
            $('#modal-product-type #save-group-attribute').removeClass('hide');
        } else {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type .form-group').addClass('hide');
            $('#modal-product-type h3.delete-group-attribute').removeClass('hide');
            $('#modal-product-type .form-msg').removeClass('hide');
            $('#modal-product-type #delete-group-attribute').removeClass('hide');
        }
    },

    init: function () {
        var that = this;
        $(document).on('click', '#add-new-group-attribute', function (e) {
            e.preventDefault();
            that.showPopupHandleGroupProduct();
            // insert 
            $(document).off('click', '#save-group-attribute').on('click', '#save-group-attribute', that.insertGroupAttribute);
        });

        // close modal
        $(document).on('click', '#close-modal-group-attribute, #cancel-group-attribute', that.closeModal);

        // delete popup 
        $(document).on('click', '.remove-group-attribute', that.deleteGroupAttribute);

        // delete
        $(document).on('click', '#delete-group-attribute', that.acceptDeleteGroupAttribute);

        // edit popup 
        $(document).on('click', '.edit-group-attribute', that.editGroupAttribute);
    }
}

module.exports = group_attribute;
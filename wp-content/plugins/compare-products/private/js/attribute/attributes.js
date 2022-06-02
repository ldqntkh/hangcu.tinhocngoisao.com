'use strict';
const $ = jQuery;
var attributes = {
    attributeData: null,

    insertAttribute: function () {
        let attribute_id = $('#attribute_id').val(),
            attribute_name = $('#attribute_name').val(),
            attribute_desc = $('#attribute_desc').val(),
            attribute_type = $('#attribute_type').val();

        if (!attribute_id || attribute_id.trim() === '' || !attribute_name || attribute_name.trim() === '' || !attribute_type) return;

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'addattribute', attribute: {
                    "attribute_id": attribute_id,
                    "attribute_name": attribute_name,
                    "attribute_desc": attribute_desc,
                    "attribute_type": attribute_type
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

    deleteAttribute: function () {
        attributes.showPopupHandleAttribute('delete');
        attributes.attributeData = JSON.parse($(this).attr('data-remove'));
    },

    acceptDeleteAttribute: function () {
        if (!attributes.attributeData.attribute_id) return;

        jQuery('body').addClass('gearvn_loading');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'removeattribute', attribute_id: attributes.attributeData.attribute_id },
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

    editAttribute: function () {
        attributes.showPopupHandleAttribute('edit');
        attributes.attributeData = JSON.parse($(this).attr('data-edit'));
        $('#attribute_id').val(attributes.attributeData.attribute_id),
            $('#attribute_name').val(attributes.attributeData.attribute_name),
            $('#attribute_desc').val(attributes.attributeData.attribute_desc),
            $('#attribute_type').val(attributes.attributeData.attribute_type);
        $(document).off('click', '#save-attribute').on('click', '#save-attribute', attributes.acceptEditAttribute);
    },

    acceptEditAttribute: function () {
        if (!attributes.attributeData.attribute_id) return;
        let attribute_name = $('#attribute_name').val(),
            attribute_desc = $('#attribute_desc').val(),
            attribute_type = $('#attribute_type').val();

        if (!attribute_name || attribute_name.trim() === '' || !attribute_type) return;

        attributes.attributeData.attribute_name = attribute_name;
        attributes.attributeData.attribute_desc = attribute_desc;
        attributes.attributeData.attribute_type = attribute_type;
        jQuery('body').addClass('gearvn_loading');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { action: 'updateattribute', attribute: attributes.attributeData },
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
        attributes.attributeData = null;
    },

    showPopupHandleAttribute: function (type = 'add-new') {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.addClass('active');
        $('#modal-product-type h3').addClass('hide');
        $('#modal-product-type #delete-attribute').addClass('hide');
        $('#modal-product-type #save-attribute').addClass('hide');
        $('#modal-product-type .form-msg').addClass('hide');
        if (type === 'add-new') {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type h3.add-new-attribute').removeClass('hide');
            $('#modal-product-type #save-attribute').removeClass('hide');
            $('#attribute-id').addClass('hide');
            $('#attribute_name').val('');
            $('#attribute_desc').val('');
            $('#attribute_type').val('');
        } else if (type === 'edit') {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type h3.edit-attribute').removeClass('hide');
            $('#modal-product-type #save-attribute').removeClass('hide');
            $('#attribute-id').removeClass('hide');
        } else {
            $('#modal-product-type h3').addClass('hide');
            $('#modal-product-type .form-group').addClass('hide');
            $('#modal-product-type h3.delete-attribute').removeClass('hide');
            $('#modal-product-type .form-msg').removeClass('hide');
            $('#modal-product-type #delete-attribute').removeClass('hide');
        }
    },

    init: function () {
        var that = this;
        $(document).on('click', '#add-new-attribute', function (e) {
            e.preventDefault();
            that.showPopupHandleAttribute();
            // insert 
            $(document).off('click', '#save-attribute').on('click', '#save-attribute', attributes.insertAttribute);
        });

        // close modal
        $(document).on('click', '#close-modal-attribute, #cancel-attribute', that.closeModal);

        // delete popup 
        $(document).on('click', '.remove-attribute', that.deleteAttribute);

        // delete
        $(document).on('click', '#delete-attribute', that.acceptDeleteAttribute);

        // edit popup 
        $(document).on('click', '.edit-attribute', that.editAttribute);
    }
}

module.exports = attributes;
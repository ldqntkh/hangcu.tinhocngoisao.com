'use strict';
const $ = jQuery;
var group_product_attribute = {
    groupProductAttributeData: null,
    init: function () {
        var that = this;
        $(document).on('click', '#add-new-group-product-attribute', function (e) {
            e.preventDefault();
            that.openWindowAssignAttribute();
        });

        this.bindEventChangeSelectAttribute();

        // close modal
        $(document).on('click', '#close-modal-group-product-attribute, #cancel-group-product-attribute', that.closeModal);

        $(document).on('click', '#assign-attribute', that.assignAttribute);

        $(document).on('click', '.remove-group-product-attribute', that.deleteGroupProductAttribute);

        $(document).on('click', '.edit-group-product-attribute', that.editGroupProductAttribute);

        $(document).on('click', '#delete-group-product-attribute', that.acceptDeleteGroupProductAttribute)
    },
    openWindowAssignAttribute: function () {
        if (typeof adminAttributeUrl !== 'undefined') {
            var win = window.open(adminAttributeUrl, "", "width=1200,height=800");
            win.onload = function () {
                win.document.getElementById('adminmenumain').innerHTML = '';
                win.document.getElementById('wpadminbar').innerHTML = '';
                win.document.getElementById('wpcontent').style.marginLeft = '0';
                $(win.document).on('click', '.select-attribute', function () {
                    var dataSelect = JSON.parse($(this).attr('data-select'));
                    if (dataSelect && dataSelect.attribute_id)
                        $('#attribute_id_selected').val(dataSelect.attribute_id);
                    win.close();
                });
            };
        }
    },

    deleteGroupProductAttribute: function () {
        group_product_attribute.showPopupHandleGroupProduct('delete');
        group_product_attribute.groupProductAttributeData = JSON.parse($(this).attr('data-remove'));
    },

    assignAttribute: function () {
        if (typeof groupId === 'undefined') return;
        let attribute_id = $('#attribute_id_selected').val(),
            display_index = $('#display_index_table').val();

        if (!attribute_id || attribute_id.trim() === '') return;
        if (!display_index || display_index < 0) display_index = 0;
        else display_index = parseInt(display_index);
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'assignattribute', attribute: {
                    "group_id": groupId,
                    "attribute_id": attribute_id,
                    "display_index": display_index
                }
            },
            beforeSend: function () {
                jQuery('.add-new-attr').append('<span class="spinner is-active"></span>');
                jQuery('.add-new-attr').find('#assign-attribute').prop('disabled', true);
            },
            success: function (response) {
                if (response.success) {
                    location.search = location.search.replace(/paged=[^&$]*/i, 'paged=1');
                }
            },
            error: function (response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }
            }
        });
    },

    showPopupHandleGroupProduct: function (type = 'add-new') {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.addClass('active');
        $('#modal-product-type h3').addClass('hide');
        if (type === 'edit') {
            $('#modal-product-type .form-group').removeClass('hide');
            $('#modal-product-type #save-group-product-attribute').removeClass('hide');
            $('#modal-product-type h3.edit-attribute').addClass('hide');

            $('#modal-product-type .form-msg').addClass('hide');
            $('#modal-product-type #delete-group-product-attribute').addClass('hide');
        } else {
            $('#modal-product-type .form-group').addClass('hide');
            $('#modal-product-type #save-group-product-attribute').addClass('hide');
            $('#modal-product-type .form-msg').removeClass('hide');
            $('#modal-product-type h3.delete-attribute').removeClass('hide');
            $('#modal-product-type #delete-group-product-attribute').removeClass('hide');
        }
    },

    editGroupProductAttribute: function () {
        group_product_attribute.showPopupHandleGroupProduct('edit');
        group_product_attribute.groupProductAttributeData = JSON.parse($(this).attr('data-edit'));

        $('#attribute_id').val(group_product_attribute.groupProductAttributeData.attribute_id),
            $('#display_index').val(group_product_attribute.groupProductAttributeData.display_index);
        $(document).off('click', '#save-group-product-attribute').on('click', '#save-group-product-attribute', group_product_attribute.acceptEditGroupProductAttribute);
    },

    acceptEditGroupProductAttribute: function () {
        if (!group_product_attribute.groupProductAttributeData.group_id || !group_product_attribute.groupProductAttributeData.attribute_id) return;
        let display_index = $('#display_index').val();

        if (!display_index || display_index < 0) display_index = 0;
        else display_index = parseInt(display_index);

        jQuery('body').addClass('gearvn_loading');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'updategroupproductattribute', attribute: {
                    "group_id": group_product_attribute.groupProductAttributeData.group_id,
                    "attribute_id": group_product_attribute.groupProductAttributeData.attribute_id,
                    "display_index": display_index
                }
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

    acceptDeleteGroupProductAttribute: function () {
        if (!group_product_attribute.groupProductAttributeData.attribute_id || !group_product_attribute.groupProductAttributeData.group_id) return;

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'removegroupproductattribute', attribute: {
                    "group_id": group_product_attribute.groupProductAttributeData.group_id,
                    "attribute_id": group_product_attribute.groupProductAttributeData.attribute_id
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

    closeModal: function () {
        let $modalProductType = $('#modal-product-type');
        $modalProductType.removeClass('active');
        group_product_attribute.groupProductAttributeData = null;
    },
    bindEventChangeSelectAttribute: function () {
        jQuery(document).delegate('#list_attribute_id_select', 'change', function (e) {
            jQuery('#attribute_id_selected').val(jQuery(this).val());
        });
    }
}

module.exports = group_product_attribute;
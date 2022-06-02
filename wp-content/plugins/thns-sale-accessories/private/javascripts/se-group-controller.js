'use strict';

/**
 * [
 *      {
 *          "se_name" : name,
 *          "se_type" : type,
 *          "se_down" : value,
 *          "products" : [id1, id2, id3]
 *      }
 * ]
 */

const $ = jQuery;
const Se_Group_Products = require('./se-group-products');
const Se_Group_Controller = {
    campaign_id : null,
    xhrRequest: null,
    group_values : [],
    itemSelected : null,
    init : function() {
        Se_Group_Controller.initGroupValue();

        $('#save-se-group').on('click', Se_Group_Controller.saveSEGroup );

        $('body').on('click', '.update-se-group', Se_Group_Controller.updateSEGroup );

        // delete group item
        $('body').on('click', '#se-group-items .delete', Se_Group_Controller.deleteSEGroup );

        // swap group item
        $('body').on('click', '#se-group-items .move_up', Se_Group_Controller.moveUpSEGroup );
        $('body').on('click', '#se-group-items .move_down', Se_Group_Controller.moveDownSEGroup );

        // add products
        $('body').on('click', '#se-group-items .se-group-name', Se_Group_Controller.addProductToSEGroup );

        $('body').on('change', 'select[name ="se-group-type"]', Se_Group_Controller.groupTypeChange );
    
        Se_Group_Products.init( Se_Group_Controller );
    },

    initGroupValue : function() {
        try {
                
            // let jsonString = decodeURIComponent(escape(window.atob($('#se_group_values').val().trim())));

            // let jsonData = JSON.parse( jsonString );

            Se_Group_Controller.group_values = group_items;

            Se_Group_Controller.renderListSEGroup();
        } catch ( e ) {
            
        }
    },

    renderListSEGroup: function() {
        var html = '';
        let clicked = -1;
        for( let index = 0; index < Se_Group_Controller.group_values.length; index++ ) {
            let item = Se_Group_Controller.group_values[index];
            if ( Se_Group_Controller.itemSelected && Se_Group_Controller.itemSelected['se_name'] == item.se_name ) clicked = index;
            //<span class="move move_up"></span>
            // <span class="move move_down"></span>
            html += `<div class="se-group-item" data-index="${index}">
                        <span class="delete"></span>
                        <p class="se-group-name">${item.name}</p>
                        
                        <div class="form-edit-group" style="display:none">
                            <div class="form-group">
                                <label>Loại giảm giá:</label>
                                <select name="se-group-type" value="${item.discount_type}">
                                    <option value="price" ${item.discount_type=='price' ? 'selected' : ''} >Giảm tiền</option>
                                    <option value="percent" ${item.discount_type=='percent' ? 'selected' : ''}>Giảm theo %</option>
                                    <option value="gift" ${item.discount_type=='gift' ? 'selected' : ''}>Quà tặng</option>
                                </select>
                            </div>
                            <div class="form-group" ${item.discount_type=='gift' ? 'style="display:none"' : ''} >
                                <label>Giảm:</label>
                                <input type="number" name="se-down-value" value="${item.discount_value}">
                            </div>
                            <div class="form-group" >
                                <label>Thứ tự hiển thị:</label>
                                <input type="number" name="se-display-index" value="${item.display_index}">
                            </div>
                            <div class="form-group">
                                <button class="update-se-group" data-index="${index}" data-key="${item.ID}" class="button" type="button">Cập nhật</button>
                            </div>
                        </div>
                    </div>`;
        }
        $('#se-group-items').html( html );
        // update value to input
        
        $('#se_group_values').val( btoa(unescape(encodeURIComponent(JSON.stringify( Se_Group_Controller.group_values )))) );

        if ( clicked != -1 ) {
            $($($('#se-group-items .se-group-item')[clicked]).find('.se-group-name')[0]).trigger('click');
        } else {
            Se_Group_Products.renderListProductSelected(null);
        }
    },

    updateSelectedData : function () {

        for(let index in Se_Group_Controller.group_values) {
            if ( Se_Group_Controller.group_values[index].se_name == Se_Group_Controller.itemSelected.se_name ) {
                Se_Group_Controller.group_values[index] = Se_Group_Controller.itemSelected;
                break;
            }
        }

        $('#se_group_values').val( btoa(unescape(encodeURIComponent(JSON.stringify( Se_Group_Controller.group_values )))) );
    },

    saveSEGroup : function(e) {
        e.preventDefault();
        if ( Se_Group_Controller.campaign_id == null ) {
            window.alert('Vui lòng tạo 1 chiến dịch!');
            return;
        }

        if ( Se_Group_Controller.group_values.length == 3 ) {
            window.alert('Chúng tôi chỉ hỗ trợ tối đa 3 nhóm phụ kiện!');
            return;
        }

        let name = $('#se-group-name').val().trim();
        let type = $('#se-group-type').val().trim();
        let down = $('#se-down-value').val().trim();
        let index = $('#se-display-index').val().trim();

        if ( name == '' ) {
            window.alert('Vui lòng nhập tên nhóm!');
            $('#se-group-name').focus();
            return;
        }
        if ( type == '' ) {
            window.alert('Vui lòng chọn loại giảm giá!');
            return;
        }
        if ( down == '' ) {
            window.alert('Vui lòng nhập giá giảm!');
            $('#se-down-value').focus();
            return;
        }
        if ( index == '' ) {
            window.alert('Vui lòng nhập vị trí hiển thị!');
            $('#se-display-index').focus();
            return;
        }

        // check value down
        if ( type == 'price' ) {
            if ( down < 1000 || down % 1000 != 0 ) {
                window.alert('Giá giảm phải là một số chia hết cho 1000');
                $('#se-down-value').focus();
                return;
            }
        } else {
            if ( down > 100 || down < 0 ) {
                window.alert('Phần trăm giảm phải lớn -1 và nhỏ hơn hoặc bằng 100');
                $('#se-down-value').focus();
                return;
            }
        }

        if ( index <= 0 ) {
            window.alert('Vị trí hiển thị phải lớn hơn 0!');
            $('#se-display-index').focus();
            return;
        }

        

        // call ajax to insert new group
        if( Se_Group_Controller.xhrRequest != null ) return;
        Se_Group_Controller.xhrRequest = jQuery.ajax({
            type: 'post',
            url: se_admin_ajax,
            data: {
                action: 'se_insert_campaign_group',
                campaign_id: Se_Group_Controller.campaign_id,
                name: name,
                discount_type: type,
                discount_value: down,
                display_index: index
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if (response.success) {
                    let item = {
                        "ID":   response.data.group_id,
                        "campaign_id":  Se_Group_Controller.campaign_id,
                        "name": name,
                        "discount_type": type,
                        "discount_value": down,
                        "display_index": index
                    }
                    Se_Group_Controller.group_values.push(item);
                    Se_Group_Controller.renderListSEGroup();
                    $('#se-group-name').val('');
                    $('#se-down-value').val('0');
                    $('#se-display-index').val('0');

                } else {
                    
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                jQuery('body').removeClass('gearvn_loading');
                Se_Group_Controller.xhrRequest.abort();
                Se_Group_Controller.xhrRequest = null;
            }
        });


        // if ( Se_Group_Controller.checkExistsValue( name ) ) {
        //     window.alert('Nhóm này đã tồn tại!');
        //     $('#se-group-name').focus();
        // } else {
        //     let item = {
        //         "se_name" : name,
        //         "se_type" : type,
        //         "se_down" : down,
        //         "products" : []
        //     };
    
        //     Se_Group_Controller.group_values.push(item);
        //     Se_Group_Controller.renderListSEGroup();
        //     $('#se-group-name').val('').focus();
        //     $('#se-down-value').val('0');
        // }
    },

    checkExistsValue: function(value) {
        for( let index = 0; index < Se_Group_Controller.group_values.length; index++ ) {
            let item = Se_Group_Controller.group_values[index];
            if ( value.trim().toLowerCase() == item.se_name.trim().toLowerCase() ) {
                return true;
            }
        }
        return false;
    },

    deleteSEGroup: function (e) {
        if( Se_Group_Controller.xhrRequest != null ) return;

        let index = $($(this).parent()).attr('data-index');
        if ( index == null || typeof index == 'undefined' ) return;
        if ( typeof index == 'string' ) index = parseInt(index);

        var item = Se_Group_Controller.group_values[index];

        var r = confirm("Bạn muốn xóa " + item['name'] + ' ?');
        if (r == true) {
            // call ajax to update
            Se_Group_Controller.xhrRequest = jQuery.ajax({
                type: 'post',
                url: se_admin_ajax,
                data: {
                    action: 'se_remove_campaign_group',
                    group: item
                },
                beforeSend: function () {
                    jQuery('body').addClass('gearvn_loading');
                },
                success: function (response) {
                    if (response.success) {
                        window.alert('Đã xóa nhóm ' + item['name']);
                        Se_Group_Controller.group_values.splice(index, 1);
                        Se_Group_Controller.renderListSEGroup();
                    } else {
                        
                    }
                },
                error: function (response, errorStatus, errorMsg) {
                //console.log( response )
                },
                complete : function (data) {
                    jQuery('body').removeClass('gearvn_loading');
                    Se_Group_Controller.xhrRequest.abort();
                    Se_Group_Controller.xhrRequest = null;
                }
            });
        } else {
            
        }

        // Se_Group_Controller.group_values.splice(index, 1);
        // Se_Group_Controller.renderListSEGroup();
    },

    moveUpSEGroup: function(e) {
        let index = $($(this).parent()).attr('data-index');
        if ( index == null || typeof index == 'undefined' ) return;
        if ( typeof index == 'string' ) index = parseInt(index);

        if ( index == 0 ) return;

        let item = Se_Group_Controller.group_values[index];
        Se_Group_Controller.group_values[index] = Se_Group_Controller.group_values[index-1];
        Se_Group_Controller.group_values[index-1] = item;
        
        Se_Group_Controller.renderListSEGroup();
    },
    
    moveDownSEGroup: function(e) {
        let index = $($(this).parent()).attr('data-index');
        if ( index == null || typeof index == 'undefined' ) return;
        if ( typeof index == 'string' ) index = parseInt(index);

        if ( index == Se_Group_Controller.group_values.length - 1 ) return;

        let item = Se_Group_Controller.group_values[index];
        Se_Group_Controller.group_values[index] = Se_Group_Controller.group_values[index+1];
        Se_Group_Controller.group_values[index+1] = item;
        
        Se_Group_Controller.renderListSEGroup();
    },

    addProductToSEGroup: function() {
        let index = $($(this).parent()).attr('data-index');
        if ( index == null || typeof index == 'undefined' ) return;
        if ( typeof index == 'string' ) index = parseInt(index);

        $('#se-group-items .se-group-item').removeClass('active');
        $('.form-edit-group').css({'display':'none'});
        $($(this).parent()).addClass('active');
        $($(this).parent()).find('.form-edit-group').css({'display':'block'});
        $('.right-container').css({
            'display': 'block'
        });
        Se_Group_Controller.itemSelected = Se_Group_Controller.group_values[index];
        $('#group-name-display').text( Se_Group_Controller.itemSelected.name );
        Se_Group_Products.resetData();
        Se_Group_Products.renderListProductSelectedIDs( Se_Group_Controller.itemSelected );
    },

    groupTypeChange: function(e) {
        var val = $(this).val().trim();
        if ( $(this).closest( '#se-groups' ).length > 0 ) {
            if ( val == 'gift' ) {
                $('#se-down-value').val('100');
                $($('#se-down-value').closest('.form-group')[0]).css({'display':'none'});
            } else {
                $('#se-down-value').val('0');
                $($('#se-down-value').closest('.form-group')[0]).css({'display':'flex'});
            }
        } else if ( $(this).closest( '.form-edit-group' ).length > 0 ) {
            var parent = $( $(this).closest( '.form-edit-group' )[0] );
            var input = $( parent.find('input[name=se-down-value]')[0] );
            if ( val == 'gift' ) {
                input.val('100');
                $(input.closest('.form-group')[0]).css({'display':'none'});
            } else {
                input.val('0');
                $(input.closest('.form-group')[0]).css({'display':'flex'});
            }
        }

        
    },

    updateSEGroup: function() {

        if( Se_Group_Controller.xhrRequest != null ) return;

        let index = $(this).attr('data-index');
        if ( index == null || typeof index == 'undefined' ) return;
        if ( typeof index == 'string' ) index = parseInt(index);
        let parent = $(this).closest( '.form-edit-group' );
        let select_type = $(parent.find('select[name ="se-group-type"]')[0]);
        let input_price = $(parent.find('input[name ="se-down-value"]')[0]);
        let input_index = $(parent.find('input[name ="se-display-index"]')[0]);
        let type = select_type.val().trim();
        let down = input_price.val().trim();
        let display_index = input_index.val().trim();

        if ( type == '' ) {
            window.alert('Vui lòng chọn loại giảm giá!');
            return;
        }
        if ( down == '' ) {
            window.alert('Vui lòng nhập giá giảm!');
            input_price.focus();
            return;
        }

        if ( display_index == '' ) {
            window.alert('Vui lòng nhập vị trí hiển thị!');
            input_index.focus();
            return;
        }

        // check value down
        if ( type == 'price' ) {
            if ( down < 1000 || down % 1000 != 0 ) {
                window.alert('Giá giảm phải là một số chia hết cho 1000');
                input_price.focus();
                return;
            }
        } else {
            if ( down > 100 || down < 0 ) {
                window.alert('Phần trăm giảm phải lớn -1 và nhỏ hơn hoặc bằng 100');
                input_price.focus();
                return;
            }
        }

        if ( display_index <= 0 ) {
            window.alert('Vị trí hiển thị phải lớn hơn 0!');
            input_index.focus();
            return;
        }

        let group_data = Se_Group_Controller.itemSelected;
        group_data['discount_type'] = type;
        group_data['discount_value'] = down;
        group_data['display_index'] = display_index;
        // call ajax to update
        Se_Group_Controller.xhrRequest = jQuery.ajax({
            type: 'post',
            url: se_admin_ajax,
            data: {
                action: 'se_update_campaign_group',
                group: group_data
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if (response.success) {
                    window.alert('Đã cập nhật thành công!');
                    Se_Group_Controller.group_values[index] = group_data;
                    Se_Group_Controller.renderListSEGroup();
                } else {
                    
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                jQuery('body').removeClass('gearvn_loading');
                Se_Group_Controller.xhrRequest.abort();
                Se_Group_Controller.xhrRequest = null;
            }
        });
    },

    updateRecordGroup: function(  ) {

    }
}

module.exports = Se_Group_Controller;
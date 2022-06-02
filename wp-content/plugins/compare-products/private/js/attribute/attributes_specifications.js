'use strict';
const $ = jQuery;
var attributes_specifications = {

    showPopupSelect: function() {
        if ( typeof product_compare_data == 'undefined' ) {
            return
        } else {

            var data_selected = [];

            if ( $('#selected-attributes-specifications').val() ) {
                try {
                    data_selected = JSON.parse(atob(  $('#selected-attributes-specifications').val().trim() ));
                } catch( e ) {
                    ///
                    data_selected = [];
                }
            }

            var html = `
                <div class="popup-select-specifications">
                    <div class="content-specifications">
                        <div class='title'>
                            <h3>Chọn thuộc tính sẽ hiển thị tại thông số kỹ thuật cơ bản</h3>
                        </div>
                        <div class="content">`;
            
            for ( let i = 0; i < product_compare_data.length; i++ ) {
                let item = product_compare_data[i];
                html += `
                    <div class="item">
                        <h4>${item.group_name}</h4>
                        <div class="item-select">`;
                
                for ( let k = 0; k < item.attribute.length; k++ ) {
                    let attr = item.attribute[k];
                    let checked = false;
                    if ( typeof data_selected[i] !== 'undefined' 
                            && typeof data_selected[i].attribute !== 'undefined'
                            && typeof data_selected[i].attribute[k] !== 'undefined'
                            && typeof data_selected[i].attribute[k].id !== 'undefined' ) checked = true;
                    html += `
                            <div class="input-group">
                                <input type="checkbox" name="${attr.id}" id="${attr.id}" value="${attr.id}" ${ checked ? 'checked' : '' } />
                                <label for="${attr.id}">${attr.name}</label>
                            </div>`;
                }
                        
                html += `</div>
                    </div>`;
            }

            
            html +=     `</div>
                        <div class="footer">
                            <button class="button" type="button" id="save-attribute-specification">Lưu</button>
                            <button class="button" type="button" id="cancel-attribute-specification">Hủy</button>
                        </div>
                    </div>
                </div>`;

            $('.popup-select-specifications').remove();
            $('body').append(html);
        }
    },

    getSelectValue : function() {

        var data = [];

        for ( let i = 0; i < product_compare_data.length; i++ ) {

            let item = product_compare_data[i];

            let data_item = {
                group_id : item.group_id,
            }

            let attrs = [];

            for ( let k = 0; k < item.attribute.length; k++ ) {

                let attr = item.attribute[k];
                if ( $( '#' + attr.id ).is(":checked") ) {
                    attrs.push( {
                        id : attr.id
                    } )
                }

                data_item['attribute'] = attrs;
            }
            data.push( data_item );
        }
        console.log(data)
        $('#selected-attributes-specifications').val( btoa(JSON.stringify(data)) );
        $('.popup-select-specifications').remove();
    },

    init: function() {
        var that = this;
        $('body').on('click', '#select-attributes-specifications', function() {
            that.showPopupSelect();
        });

        $('body').on( 'click', '#save-attribute-specification', function() {
            that.getSelectValue();
        } );

        $('body').on( 'click', '#cancel-attribute-specification', function() {
            $('.popup-select-specifications').remove();
        } );

    }
}

module.exports = attributes_specifications;
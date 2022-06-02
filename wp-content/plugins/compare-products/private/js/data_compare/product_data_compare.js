'use strict';
const $ = jQuery;
var product_data_compare = {


    productTypeChange: function() {
        var product_type = $(this).val();
        if (!product_type) product_type = -1;
        var render_type = 'html';

        $.ajax({
            type : 'post',
            dataType : 'json',
            url : ajaxurl,
            data : {action: 'displayproductcomparedata', product_compare : {
                "product_type" : product_type,
                "product_id" : $('#post_id').val(),
                "render_type" : render_type
            }},
            success: function(response) {
                if (render_type == 'html') {
                    if (response.data.success) {
                        $('#product-compare-datas').html(response.data.data);
                    }
                }
                
            }, 
            error: function(response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }
            }
        });
    },

    init: function() {
        var that = this;
        $(document).on('change', '#product-type-compare', that.productTypeChange);
    }
};

module.exports = product_data_compare; 
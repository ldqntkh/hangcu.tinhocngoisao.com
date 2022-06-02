'use strict';
const $ = jQuery;
var cachingCompareData = {
    xhr: null,
    groups : [],
    index: 0,
    page: 1,
    init: function() {
        this.getProductTypes();
    },

    getProductTypes: function () {
        $(document).on('click', '#create-compare-caching', function() {
            // $('#create-compare-caching').text('Đợi xíu nhé....');
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'getcomparetype'
                },
                beforeSend: function () {
                    jQuery('body').addClass('gearvn_loading');
                },
                success: function (response) {
                    cachingCompareData.groups = response.data.types;
                    cachingCompareData.index = 0;
                    cachingCompareData.page = 1;
                    jQuery('body').removeClass('gearvn_loading');
                    cachingCompareData.createCachingData();
                },
                error: function (response, errorStatus, errorMsg) {
                    if (errorStatus) {
                        console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                    }
    
                    jQuery('body').removeClass('gearvn_loading');
                }
            });
        });
    },

    createCachingData: function( ) {
        let that = this;
        let item = that.groups[that.index];

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
                action: 'createcachingdata',
                product_type_id: item.id,
                end_group : that.index == that.groups.length -1,
                page: that.page
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if( response.data.end == false ) {
                    jQuery('body').removeClass('gearvn_loading');
                    that.page ++;
                    that.createCachingData();
                } else {
                    if( typeof response.data.end_group && response.data.end_group == true && that.index == that.groups.length-1 ) {
                        jQuery('body').removeClass('gearvn_loading');
                    } else {
                        that.index ++;
                        that.page = 1;
                        that.createCachingData();
                    }
                }
            },
            error: function (response, errorStatus, errorMsg) {
                if (errorStatus) {
                    console.log('The error status is: ' + errorStatus + ' and the error message is: ' + errorMsg);
                }

                jQuery('body').removeClass('gearvn_loading');
            }
        });
    }
}

module.exports = cachingCompareData;
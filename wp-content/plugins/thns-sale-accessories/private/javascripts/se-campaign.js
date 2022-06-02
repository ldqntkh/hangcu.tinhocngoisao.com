'use strict';

const $ = jQuery;
const Se_Group_Controller = require('./se-group-controller');
const Se_Campaign = {
    se_group : null,
    xhrRequest: null,
    init: function() {
        $('#campaign-start-date').datepicker(); 
        $('#campaign-end-date').datepicker(); 

        $('body').on('click', '#save-campaign', Se_Campaign.saveCampaign );

        Se_Campaign.se_group = Se_Group_Controller;
        Se_Campaign.se_group.init();
        if ( typeof campaign_id !== 'undefined' && campaign_id !== '' ) {
            Se_Campaign.se_group.campaign_id = campaign_id;
        }
    },

    isValidDate: function (date)
    {
        // var matches = /^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/.exec(date);
        // if (matches == null) return false;
        // var d = matches[3];
        // var m = matches[2];
        // var y = matches[1];
        // var composedDate = new Date(y, m, d);
        // return composedDate.getDate() == d &&
        //         composedDate.getMonth() == m &&
        //         composedDate.getFullYear() == y;
         // First check for the pattern
        var regex_date = /^\d{4}\-\d{1,2}\-\d{1,2}$/;

        if(!regex_date.test(date))
        {
            return false;
        }

        // Parse the date parts to integers
        var parts   = date.split("-");
        var day     = parseInt(parts[2], 10);
        var month   = parseInt(parts[1], 10);
        var year    = parseInt(parts[0], 10);

        // Check the ranges of month and year
        if(year < 1000 || year > 3000 || month == 0 || month > 12)
        {
            return false;
        }

        var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

        // Adjust for leap years
        if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        {
            monthLength[1] = 29;
        }

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    },

    saveCampaign: function(e) {
        e.preventDefault();
        if( Se_Campaign.xhrRequest != null ) return;
        
        var campaign_name = $('#campaign-name').val().trim();
        var campaign_start_date = $('#campaign-start-date').val().trim();
        var campaign_end_date = $('#campaign-end-date').val().trim();
        
        if ( !campaign_name ||campaign_name == '' ) {
            window.alert('Vui lòng nhập tên chiến dịch');
            return;
        }

        if ( !campaign_start_date ||campaign_start_date == '' ) {
            window.alert('Vui lòng nhập thời gian bắt đầu chiến dịch');
            return;
        }

        if ( !Se_Campaign.isValidDate( campaign_start_date ) ) {
            window.alert('Vui lòng nhập thời gian bắt đâu hợp lệ');
            return;
        }

        if ( !campaign_end_date ||campaign_end_date == '' ) {
            window.alert('Vui lòng nhập thời gian kết thúc chiến dịch');
            return;
        }

        if ( !Se_Campaign.isValidDate( campaign_end_date ) ) {
            window.alert('Vui lòng nhập thời gian kết thúc hợp lệ');
            return;
        }

        if ( Date.parse( campaign_start_date ) > Date.parse( campaign_end_date ) ) {
            window.alert('Thời gian kết thúc phải lớn hơn hoặc bằng thời gian bắt đầu');
            return;
        }

        let campaign_enable = 0;
        if ( $('#campaign_enable').is(":checked") ) campaign_enable = 1;

        // call ajax to save new campaign
        Se_Campaign.xhrRequest = jQuery.ajax({
            type: 'post',
            url: se_admin_ajax,
            data: {
                action: 'se_insert_campaign',
                campaign_name: campaign_name,
                campaign_start_date: campaign_start_date,
                campaign_end_date: campaign_end_date,
                current_product_id: current_product_id,
                campaign_enable: campaign_enable
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if (response.success) {
                    Se_Campaign.se_group.campaign_id = response.data.campaign_id; 
                } else {
                    Se_Campaign.se_group.campaign_id = null;
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                jQuery('body').removeClass('gearvn_loading');
                Se_Campaign.xhrRequest.abort();
                Se_Campaign.xhrRequest = null;
            }
        });
    }

}

module.exports = Se_Campaign;
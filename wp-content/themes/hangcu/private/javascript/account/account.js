'use strict';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';

const $ = jQuery;

const account = {
    redirect_link : null,
    init: function() {
        this.initActionPopupLogin();
    },

    initActionPopupLogin : function() {
        // 1 comment
        // 2 mua hàng
        // - 2.1 thanh toan từ minicart
        // - 2.2 thanh toán từ giỏ hàng
        $(document).on('click', 'a.checkout, a.checkout-button', function(e) {
            let data_user = sessionStorage.getItem('user');
            if( data_user ) {
                data_user = JSON.parse( data_user );
            }
            if(!data_user || !data_user.data) {
                $('.electro-overlay.electro-close-off-canvas').trigger('click');
                e.preventDefault();
                let link = $(this).attr('href');
                // account.redirect_link = link;
                // account.showPopupAccount();
                if( window.mainComponentLoginAccount ) {
                    if( !link ) link = location.href;
                    window.mainComponentLoginAccount.setActionShowPopupOutSide( link )
                }
                return false; 
            }
        });

        // comment
        $(document).on('click', 'a#show-review_form_wrapper', function(e) {
            let data_user = sessionStorage.getItem('user');
            if( data_user ) {
                data_user = JSON.parse( data_user );
            }
            if(!data_user || !data_user.data) {
                e.preventDefault();
                if( window.mainComponentLoginAccount ) {
                    window.mainComponentLoginAccount.setActionShowPopupOutSide( location.href + '#reviews' );
                } else {
                    $('#review_form_wrapper').css({
                        display: 'none',
                        opacity : 0
                    });
                }
                
                return false; 
            }
        })

    },
}

module.exports = account;
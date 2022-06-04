const $ = jQuery;

const mbCart = {
    init: function() {
        mbCart.backNavigation();
    },

    backNavigation : function() {
        $('.mb-nav-cart .icon-back').on('click', function() {
            let referrer = document.referrer;
            if( referrer == '' || referrer.indexOf(window.location.host) < 0 ) {
                window.location.href = window.location.origin;
            } else {
                if( window.location.pathname.indexOf('thanh-toan') > 0 ) {
                    window.location.href = window.location.origin;
                } else {
                    window.history.back();
                }
            }
        })
    }

}

module.exports = mbCart;
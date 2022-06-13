const $ = jQuery;

const mbHeader = {
    init: function() {
        mbHeader.navMenuBar();
        // mbHeader.handleNavSticky();
        mbHeader.initSearchInputBlur();
    },

    navMenuBar : function() {
        $(".off-canvas-navbar-toggle-buttons .navbar-toggle-hamburger").off("click").on("click", function() {
            // var c = {
            //     transform: "translateX(250px)",
            //     transition: "all .5s"
            // };
            // b && (c.transform = "translateX(-250px)"),
            // $(this).parents(".stuck").length > 0 && $("html, body").animate({
            //     scrollTop: $("body")
            // }, 0),
            $('.off-canvas-navigation').css({
                left: 0,
                transition: "all 0.2s ease-out",
                'z-index': 9999999
            })
            $(this).closest(".off-canvas-navbar-toggle-buttons").toggleClass("toggled");
            $("#page").toggleClass("off-canvas-bg-opacity");
            $("body").toggleClass("off-canvas-active");
            setTimeout(function() {
                $('.off-canvas-bg-opacity>header, .off-canvas-bg-opacity>#content').off('click').on('click', function() {
                    $(".off-canvas-navbar-toggle-buttons").toggleClass("toggled");
                    $('.off-canvas-navigation').css({left: '-290px'});
                    $('.off-canvas-bg-opacity>header, .off-canvas-bg-opacity>#content').off('click');
                    $("#page").toggleClass('off-canvas-bg-opacity');
                    $("body").toggleClass("off-canvas-active");
                });
            }, 500);

        });
    },

    handleNavSticky : function() {
        $(document).on('scroll', function() {
            let top = $(document).scrollTop();
            if( top > 100 && !$('#masthead .header-row-1').hasClass('fixed-header') ) {
                $('#masthead .header-row-1').addClass('fixed-header');
                $('body').addClass('fixed-header');
                // show id form 1
                $('.header-row-1 .product-search-field').attr('id', 'search');
                // $('.header-row-2 .product-search-field').attr('id', 'bksearch');
            } else if( top <= 100 ) {
                $('#masthead .header-row-1').removeClass('fixed-header')
                $('body').removeClass('fixed-header');
                // show id form 2
                $('.header-row-1 .product-search-field').attr('id', 'bksearch');
                // $('.header-row-2 .product-search-field').attr('id', 'search');
            }
        }).trigger('scroll');
    },

    initSearchInputBlur: function() {
        $(document).on('focus', '#search', function() {
            if(!$('.guaven_woos_suggestion').hasClass('active')) {
                $('.guaven_woos_suggestion').addClass('active');
            }
        });
        $(document).on('click', '.guaven_woos_suggestion', function() {
            $('.guaven_woos_suggestion').removeClass('active');
            $('.product-search-field').val('');
        });
    }
}

module.exports = mbHeader;
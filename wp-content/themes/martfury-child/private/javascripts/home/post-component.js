'use strict';
const $ = jQuery;
const thns_home_ajax= '/wp-admin/admin-ajax.php';
var homePost = {
    init: function () {
        this.itemPostClick();
        this.initCatClick();
        this.initHomepageSearchClick();
        this.initFooterTitleClick();

        this.initComunicate();

        $('.featured-slider').each(function() {
            var at_featured_img_slider = $(this);
            var autoplay = parseInt(at_featured_img_slider.data('autoplay'));
            var arrows = parseInt(at_featured_img_slider.data('arrows'));
            var autoplaySpeed = at_featured_img_slider.data('autoplayspeed') ? parseInt(at_featured_img_slider.data('autoplayspeed') ) : 3000;
            var prevArrow = at_featured_img_slider.closest('.slider-section').find('.at-action-wrapper > .prev');
            var nextArrow = at_featured_img_slider.closest('.slider-section').find('.at-action-wrapper > .next');
    
            at_featured_img_slider.css('visibility', 'visible').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: (autoplay===1),
                adaptiveHeight: true,
                cssEase: 'linear',
                arrows: (arrows===1),
                prevArrow: prevArrow,
                nextArrow: nextArrow,
                autoplaySpeed: autoplaySpeed,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            arrows: false
                        }
                    }
                ]
            });
        });
    },

    initComunicate : function() {
        $(document).on('click', 'body.mobile-version .communicate .icon-communicate', function() {
            $('body.mobile-version .communicate .show-on-mobile').css({
                display: 'block'
            });
        });
        $(document).on('click', 'body.mobile-version .communicate .icon-cross2', function() {
            $('body.mobile-version .communicate .show-on-mobile').css({
                display: 'none'
            });
        });
        $(document).on('click', 'body.mobile-version .communicate li.vchat', function() {
            $('body.mobile-version .communicate .show-on-mobile').css({
                display: 'none'
            });
            $('body.mobile-version #embed_fullchat').removeClass('bc_hide').css({
                display: 'block'
            });
        });
    },

    initHomepageSearchClick: function() {
        $('body.mobile-version').on('click', '.products-search input.search-field', function() {
            if( !$('#mf-els-modal-mobile').hasClass('open') ) {
                window.scrollTo(0,0);
                $('.navigation-mobile_search').trigger('click');
                // $('#mf-els-modal-mobile').addClass('open');
                $('#mf-els-modal-mobile input.search-field').focus();
            }
        });
    },

    initFooterTitleClick : function() {
        $('body.mobile-version').on('click', '.footer-widgets .widget-title', function() {
            if( $(this).closest('.widget_custom_html').hasClass('active') ) {
                $(this).closest('.widget_custom_html').removeClass('active');
            } else {
                $(this).closest('.widget_custom_html').addClass('active');
            }
        });
    },

    initCatClick: function() {
        $('#navigation-mobile_cat').on('click', function(e) {
            $('.guaven_woos_mobilesearch').removeAttr( 'style' );
            $('.guaven_woos_suggestion').removeAttr( 'style' );
        });
    },

    itemPostClick: function() {
        $('body').on('click', '.post-cpn-video', function(e) {
            e.preventDefault();
            let postId = $(this).attr('data-post-id');
            try {
                $.ajax({
                    type: 'GET',
                    url: thns_home_ajax,
                    data: {
                        action: 'thns_get_post_content',
                        post_id: postId
                    },
                    beforeSend: function() {
                        // setting a timeout
                        $('body').append(`
                            <div id="popup-post-content"><p class="loading">Đang xử lý</p></div>
                        `);
                    },
                    success: function(data) {
                        $( "#popup-post-content" ).remove();
                        let dataRP = data.data;
                        $('body').append(`
                            <div id="popup-post-content">
                                <div class="post-contents">
                                    <div class="video-content">
                                        <iframe width="100%" height="350" src="https://www.youtube.com/embed/${dataRP.data.video_url}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <div class="content-body">${dataRP.data.content}</div>
                                </div>
                                <button type="button" id="remove-popup">x</button>
                            </div>
                            
                        `).css({
                            "overflow" : "hidden"
                        });
                        $('body').on('click', '#remove-popup', function() {
                            $( "#popup-post-content" ).remove();
                            $('body').css({
                                "overflow" : "auto"
                            });
                        })
                    },
                    error: function(xhr) { // if error occured
                        // alert("Error occured.please try again");
                        $( "#popup-post-content" ).remove();
                    },
                    complete: function() {
                        
                    }
                });
            } catch (err) {
                console.log(err)
            }
        })
    }
}

module.exports = homePost;
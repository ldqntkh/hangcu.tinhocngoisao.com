'use strict';
const $ = jQuery;
const gvn_home_ajax= '/wp-admin/admin-ajax.php';
var productDetails = {
    xhr: null,
    init: function () {
        this.checkSingleProductStock();

        this.singleProduct();
        // this.displayImageVariableColor();
        // this.variableProductChange();
        // this.initVariableNameClick();
        this.initShowPopupSpecifications();
        this.initProductThumbs();
        this.initProductYoutubeVideos();
        this.showMoreLessContentDetail();
        this.initShowMoreProduct();
        // this.initCheckCommentProduct();
        this.initShowProductComment();
        // this.initCommentForm();
        // this.initCommentItems();
        // this.initProductReview();
        
        if ( typeof product_id !== "undefined" ) {
            let productids = localStorage.getItem("productids");
            if ( !productids ) productids = [];
            else {
                productids = JSON.parse(productids);
            }

            if ( !productids.includes( product_id ) ) {
                productids.unshift( product_id )
            }

            localStorage.setItem("productids", JSON.stringify(productids));
        }

        $('#mb-product-detail-bottom').on('click', '.electro-close-icon', function() {
            $('#mb-product-overlay').removeClass('active');
            $('#mb-product-detail-bottom').removeClass('active');
        });
        $('#mb-product-overlay').on('click', function() {
            $('#mb-product-overlay').removeClass('active');
            $('#mb-product-detail-bottom').removeClass('active');
        });
    },

    checkSingleProductStock: function() {
        if( typeof product_id_stock !== 'undefined' && typeof npid !== 'undefined' ) {
            // document.body.classList.add('hangcu_loading');
                    
            $.ajax({
                type: "get",
                url: '/wp-json/v1/check_stock',
                data: {
                    "dbi-ajax": true,
                    product_id: product_id_stock,
                    npid,
                    backorder
                },
                cache: false ,
                success: function (response) {
                    $('.single_add_to_cart_button').css({
                        display: 'block'
                    });
                    $('.single_add_to_cart_button.checking-stock').remove();
                    let responseData = typeof response.data !== 'undefined' ? response.data : response;
                    if( responseData == false) {
                        $('#cart-top').html(`
                            <p style="font-weight: bold; color: orange">HẾT HÀNG TẠM THỜI</p>
                        `);
                        $('#cart-bottom').remove();
                    } else if( typeof responseData == 'string' ) {
                        $('#cart-top').html(responseData);
                        $('#cart-bottom').remove();
                    }
                    if( responseData == true ) {
                        $('.pd-stock-status strong').css({
                            display: 'block'
                        });
                    }
                },
                error: function (response, errorStatus, errorMsg) {
                    
                },
                complete: function() {
                    // document.body.classList.remove('hangcu_loading');
                }
            });
        }
    },

    initProductReview: function() {
        if( $('#reviews').length > 0 ) {
            $.ajax({
                type: "post",
                url: gvn_home_ajax,
                data: {
                    action: "load_template_review_product",
                    product_id
                },
                success: function (response) {
                    var data = response.data;
                    if( data.output_review_form ) {
                        $('#advanced-review').html(data.output_review_form);
                        $( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
                        productDetails.initCheckCommentProduct();
                    }
                    if( data.output_review_item ) {
                        $('#comments').html(data.output_review_item);
                        productDetails.clickPagingComment();
                    }
                },
                error: function (response, errorStatus, errorMsg) {
                    
                }
            });
        }
    },

    // initCommentForm: function() {
    //     if( $('#reviews').length > 0 ) {
    //         $.ajax({
    //             type: "post",
    //             url: gvn_home_ajax,
    //             data: {
    //                 action: "load_review_form",
    //                 product_id
    //             },
    //             success: function (response) {
    //                 var data = response.data;
    //                 if( data ) {
    //                     $('#advanced-review').html(data);
    //                     $( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
    //                     productDetails.initCheckCommentProduct();
    //                 }
    //             },
    //             error: function (response, errorStatus, errorMsg) {
                    
    //             }
    //         });
    //     }
    // },

    // initCommentItems: function() {
    //     if( $('#reviews').length > 0 ) {
    //         $.ajax({
    //             type: "post",
    //             url: gvn_home_ajax,
    //             data: {
    //                 action: "load_review_items",
    //                 product_id
    //             },
    //             success: function (response) {
    //                 var data = response.data;
    //                 if( data ) {
    //                     $('#comments').html(data);
    //                     productDetails.clickPagingComment();
    //                 }
    //             },
    //             error: function (response, errorStatus, errorMsg) {
                    
    //             }
    //         });
    //     }
    // },

    clickPagingComment : function() {
        if( $('ul.page-numbers').length > 0 ) {
            $(document).on('click', 'ul.page-numbers li a', function(e) {
                e.preventDefault();
                let page_comment = $(this).text();
                
                if( page_comment ) {
                    $('body').animate({
                        scrollTop: $("#comments").offset().top
                    }, 100);
                    document.body.classList.add('hangcu_loading');
                    
                    $.ajax({
                        type: "post",
                        url: gvn_home_ajax,
                        data: {
                            action: "load_review_items",
                            product_id,
                            page_comment
                        },
                        success: function (response) {
                            var data = response.data;
                            if( data ) {
                                $('#comments').html(data);
                                
                                productDetails.clickPagingComment();
                            }
                        },
                        error: function (response, errorStatus, errorMsg) {
                            
                        },
                        complete: function() {
                            document.body.classList.remove('hangcu_loading');
                        }
                    });
                }
            });
        }
    },

    clickItem: function (e) {
        let data_value = $(this).attr('data-value');
        if (data_value) {
            let data_id = $(this).attr('data-id');
            if (data_id) {
                $('#' + data_id).val(data_value);
                $('div[data-id="' + data_id + '"]').each(function (i, el) {
                    $(this).removeClass('btn-primary').addClass('btn-light');
                });
                $(this).removeClass('btn-light').addClass('btn-primary');
            }
        }
        $('select[name*="attribute_pa_"]').trigger("change");
    },
    // displayImageVariableColor: function () {
    //     const form_attributes = $("form.variations_form");
    //     var data_product_variations = form_attributes.attr(
    //         "data-product_variations"
    //     );

    //     if (!data_product_variations) return;
    //     data_product_variations = JSON.parse(data_product_variations);
    //     $('div[id*="pa_color_"]').each(function (i, el) {
    //         for (let index in data_product_variations) {
    //             let item = data_product_variations[index];
    //             if (item.attributes.attribute_pa_color == $(this).attr("data-value")) {
    //                 let img = item.image.url;
    //                 $(this).css({
    //                     background: "url(" + img + ")",
    //                     "background-size": 'cover'
    //                 });
    //             }
    //         }
    //     });
    // },

    // initVariableNameClick: function () {
    //     $('body').on('click', '.pa_attribute', productDetails.clickItem);
    // },

    // variableProductChange: function () {
    //     const form_attributes = $("form.variations_form");
    //     var data_product_variations = form_attributes.attr(
    //         "data-product_variations"
    //     );

    //     if (!data_product_variations) return;
    //     data_product_variations = JSON.parse(data_product_variations);

    //     $("body").on("change", 'select[name*="attribute_pa_"]', function () {
    //         if ($(this).attr('name') == 'attribute_pa_color') {
    //             let index_attr = 0;
    //             let count = 0;
    //             var img_product = "";
    //             while (index_attr < data_product_variations.length) {
    //                 let attribute = data_product_variations[index_attr].attributes;
    //                 let attr_length = Object.keys(attribute).length;
    //                 $('select[name*="attribute_pa_"]').each(function (i, el) {
    //                     let val_attr = attribute[$(this).attr("name")];
    //                     let val = $(this).val();
    //                     if (val_attr == val) {
    //                         count++;
    //                     }
    //                 });
    //                 if (count == attr_length) {
    //                     img_product = data_product_variations[index_attr].image.url;
    //                     break;
    //                 } else {
    //                     count = 0;
    //                     index_attr++;
    //                 }
    //             }
    //             if (img_product != "") {
    //                 $(".product-variable img.img-fluid").attr("src", img_product);
    //             }

    //             if ($(this).attr("id") == "pa_color") {
    //                 $('div[id*="pa_color_"]').html("");
    //                 $("#pa_color_" + $(this).val()).html('<i class="fas fa-check"></i>');
    //             }
    //         } else {
    //             let data_id = $(this).attr('id');
    //             let val = $(this).val();
    //             $('div[data-id="' + data_id + '"]').each(function (i, el) {
    //                 $(this).removeClass('btn-primary').addClass('btn-light');
    //                 if ($(this).attr('data-value') == val) {
    //                     $(this).removeClass('btn-light').addClass('btn-primary');
    //                 }
    //             });
    //         }
    //         // set price to top
    //         $('.product-variable p.price span.electro-price').html($('.woocommerce-variation-price span.price').html());
    //     });
    //     jQuery(window).load(function () {
    //         setTimeout(function () {
    //             $('select[name*="attribute_pa_"]').trigger("change");
    //         }, 0);
    //     })

    // },

    singleProduct: function () {
        if (
            typeof product_id_compare === "undefined" ||
            typeof product_type_id === "undefined"
        )
            return;
        $("body").on("keyup", "#input-product-compare", function (e) {
            if (productDetails.xhr != null) productDetails.xhr.abort();
            var inputValue = $(this).val();
            if (inputValue !== "") {
                var $search_suggestions = $("#search-suggestions");
                $search_suggestions.css({
                    display: "none"
                });
                $search_suggestions.html("");

                if( typeof product_caching_compare == 'undefined' ) {
                    productDetails.xhr = $.ajax({
                        type: "post",
                        dataType: "json",
                        url: adminAjaxCompare,
                        data: {
                            action: "load_product_compare",
                            product_id: product_id_compare,
                            product_type_id: product_type_id,
                            search_name: inputValue
                        },
                        success: function (response) {
                            $search_suggestions = $("#search-suggestions");
    
                            var data = response.data;
                            if (!data || data.length == 0) {
                                $search_suggestions.css({
                                    display: "none"
                                });
                                $search_suggestions.html("");
                            } else {
                                let html = "<ul>";
                                for (let i in response.data) {
                                    html +=
                                        '<li><a target="_blank" href="' +
                                        response.data[i].link +
                                        '">' +
                                        response.data[i].name +
                                        "<a></li>";
                                }
                                html += "</ul>";
                                $search_suggestions.css({
                                    display: "block"
                                });
                                $search_suggestions.html(html);
                            }
                        },
                        error: function (response, errorStatus, errorMsg) {
                            $search_suggestions.css({
                                display: "none"
                            });
                            $search_suggestions.html("");
                            if (errorStatus) {
                                console.log(
                                    "The error status is: " +
                                    errorStatus +
                                    " and the error message is: " +
                                    errorMsg
                                );
                            }
                        }
                    });
                    return false;
                } else {
                    let data_group = product_caching_compare[product_type_id];
                    let html = "<ul>";
                    for( let i = 0; i < data_group.length; i++ ) {
                        if( data_group[i].id != product_id_compare && data_group[i].name.toLocaleLowerCase().indexOf( inputValue.toLocaleLowerCase() ) >= 0 ) {
                            let link = `/sssp/${product_slug_compare}-vs-${data_group[i].slug}`; 
                            html +=
                                '<li><a target="_blank" href="' +
                                link +
                                '">' +
                                data_group[i].name +
                            "<a></li>";
                        }
                    }
                    html += "</ul>";
                    $search_suggestions.css({
                        display: "block"
                    });
                    $search_suggestions.html(html);
                }
            }
        });

        $("body").on("blur", "#input-product-compare", function (e) {
            setTimeout(function () {
                var $search_suggestions = $("#search-suggestions");
                $search_suggestions.css({
                    display: "none"
                });
                $search_suggestions.html("");
            }, 200)
        });
    
        
    },

    initShowPopupSpecifications: function () {

        $('.viewparameterfull').on('click', function () {
            $('.viewparameterfullcontent').css({
                'display': 'block'
            });
            $('body').css({
                'overflow': 'hidden'
            });
        });

        $('.close-content').on('click', function () {
            $('.viewparameterfullcontent').css({
                'display': 'none'
            });
            $('body').css({
                'overflow': 'auto'
            });
        });

        jQuery('.hangcu-product .product-right-content .viewparameterfullcontent').click(function (e) {
            if (!jQuery(e.target).parents('.content').length && !jQuery(e.target).hasClass('content')) {
                $('.viewparameterfullcontent').css({
                    'display': 'none'
                });
                $('body').css({
                    'overflow': 'auto'
                });
            }
        });
    },

    initProductThumbs: function () {
        $('body').on('click', '.woocommerce-product-gallery__image a', function(e) {
            if ( $('#image-thumbs-slide').length > 0 ) {
                e.preventDefault();
                $('#image-thumbs-slide').trigger('click');
            }
            return false;
        });
        if (typeof arrFullSize !== 'undefined' && typeof arrThums !== 'undefined') {
            $('body').on('click', '#image-thumbs-slide', function () {
                var popup_images_thumbs = $('#popup-images-thumbs');
                var content_img_thumbs = $('#content-img-thumbs');
                var html = '<div id="image-product-thumb-item">';
                var htmlImages = '<ul>';
                for (var i = 0; i < arrFullSize.length; i++) {
                    htmlImages += '<li><img src="' + arrFullSize[i] + '" alt="" /></li>';
                }
                htmlImages += '</ul>';
                html += htmlImages + '</div>';

                content_img_thumbs.html(html);
                popup_images_thumbs.removeClass('d-none');

                setTimeout(function () {
                    $('#image-product-thumb-item ul').slick({
                        rows: 0,
                        slidesToShow: 1,
                        prevArrow: "<i class='fas fa-caret-left'></i>",
                        nextArrow: "<i class='fas fa-caret-right'></i>",
                        draggable: false,
                        useTransform: false,
                        mobileFirst: true,
                        dots: true,
                        customPaging: function (slider, i) {
                            return '<a href="#"><img src="' + arrThums[i] + '" /></a>';
                        }
                    });
                    var width = $($('#image-product-thumb-item ul.slick-dots')).width();
                    if (width >= arrThums.length * 100) {
                        $($('#image-product-thumb-item ul.slick-dots')).css({
                            "justify-content": "center"
                        });
                    }
                }, 300);

            });

            $('body').on('click', '#popup-images-thumbs-close, #content-img-thumbs-rect', function () {
                $('#popup-images-thumbs').addClass('d-none');
            });
        }
    },

    initProductYoutubeVideos: function () {
        if (typeof youtube_videos !== 'undefined') {
            $('body').on('click', '#show-popup-youtube-video', function () {
                setTimeout(function() {
                    var popup_youtube_video = $('#popup-youtube-videos');
                    var content_youtube_video = $('#content-youtube-videos');
                    var html = '<div id="youtube-video-item">';
                    var htmlVideos = '<ul>';
                    for (var i = 0; i < youtube_videos.length; i++) {
                        htmlVideos += '<li class="youtube-video-item-dot" data-video-id="' + youtube_videos[i] + '"><img src="http://i3.ytimg.com/vi/' + youtube_videos[i] + '/maxresdefault.jpg" alt="" /><i class="fas fa-play"></i></li>';
                    }
                    htmlVideos += '</ul>';
                    html += htmlVideos + '</div>';

                    content_youtube_video.html(html);
                    popup_youtube_video.removeClass('d-none');

                    setTimeout(function () {
                        $('#youtube-video-item ul').slick({
                            rows: 0,
                            slidesToShow: 1,
                            arrows: false,
                            draggable: false,
                            useTransform: false,
                            mobileFirst: true,
                            dots: true,
                            customPaging: function (slider, i) {
                                return '<a href="#" class="youtube-video-item-dot" data-video-id="' + youtube_videos[i] + '"><img src="http://i3.ytimg.com/vi/' + youtube_videos[i] + '/hqdefault.jpg" alt="" /><i class="fas fa-pause"></i><i class="fas fa-play"></i></a>';
                            },
                        }).on('afterChange', function (event, slick, currentSlide) {
                            removeVideo();
                            setTimeout(() => {
                                playVideo(youtube_videos[currentSlide]);
                            }, 200);
                        });

                        var width = $($('#youtube-video-item ul.slick-dots')).width();
                        if (width >= youtube_videos.length * 100) {
                            $($('#youtube-video-item ul.slick-dots')).css({
                                "justify-content": "center"
                            });
                        }
                        playVideo(youtube_videos[0]);
                    }, 400);
                }, 1000)

            });

            $('body').on('click touch', '#popup-youtube-videos-close', function () {
                $('#popup-youtube-videos').addClass('d-none');
                $('#content-youtube-videos').html('');
            });

            function playVideo(videoId) {
                var elementVideo = $('#popup-youtube-videos').find('#video-frame');
                if (elementVideo) {
                    elementVideo.remove();
                }

                elementVideo = '<div id="video-frame">';
                elementVideo += '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' + videoId + '?autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                elementVideo += '</div>';
                $('#youtube-video-item li.youtube-video-item-dot.slick-active').append(elementVideo);
            }

            function removeVideo() {
                var elementVideo = $('#popup-youtube-videos').find('#video-frame');
                if (elementVideo) {
                    elementVideo.remove();
                }
            }
        }
    },

    showMoreLessContentDetail: function () {
        let contentDetailtElement = jQuery(".single-product .hangcu-product .electro-description");

        if (contentDetailtElement.outerHeight() > 400) {
            contentDetailtElement.addClass('less-content');
            contentDetailtElement.append("<button class='btn-show-more-content'><span>Đọc thêm</span><i class='fas fa-sort-down'></i></button>");
        }

        jQuery(document).on('click', ".single-product .hangcu-product .electro-description .btn-show-more-content", function () {
            jQuery(this).closest('.electro-description').removeClass('less-content').addClass('show-more-content');
        });
    },

    initShowMoreProduct: function() {
        $('body').on('click', '.product-configuation .btn-show-more-content', function () {
            let parent = jQuery(this).closest('.product-configuation');
            if ( parent.hasClass( 'less-content' ) ) {
                parent.removeClass('less-content').addClass('show-more-content');
                jQuery(this).html( '<span class="show-title">Thu gọn</span><i class="fas fa-sort-up"></i>' );
                jQuery(this).removeClass( 'un-active' );
            } else {
                parent.addClass('less-content').removeClass('show-more-content');
                jQuery(this).html( '<span class="show-title">Xem thêm</span><i class="fas fa-sort-down"></i>' );
                jQuery(this).addClass( 'un-active' );
            }
            
        });
    },

    initShowProductComment: function() {
        $('body').on('click', '#show-review_form_wrapper', function(e) {
            e.preventDefault();
            if( sessionStorage.getItem('user') && JSON.parse(sessionStorage.getItem('user')).data ) {
                $('#review_form_wrapper').css({
                    display: 'flex',
                    opacity : 1
                })
            }
            
        })
    },

    initCheckCommentProduct: function () {
        $("#respond p.stars a").on('click', function() {
            let classID = $(this).attr('class');
            try {
                if( classID.indexOf('star-') >= 0 ) {
                    classID = classID.split('star-')[1].split(' ')[0];
                    let title = $('#rating option[value="'+ classID +'"]').text();
                    $('.comment-form-rating label').text(title);
                } else {
                    let title = $('#rating option[value=""]').text();
                    $('.comment-form-rating label').text(title);
                }
            } catch (err) {

            }
        });
        $('#review_form span.electro-close-icon').on('click', function() {
            $('#review_form_wrapper').css({
                display: 'none',
                opacity : 0
            });
            $('#product-review-error').text('').css({
                color: 'black'
            });
            // remove element
            $('#commentform p.stars').removeClass('selected');
            $('#commentform p.stars a').removeClass('active');
            // t.val('');
            $('#commentform p.comment-form-comment #comment').val('');
        });
        $(document).on('click', '#close-popup-cmt', function() {
            $('#review_form_wrapper').remove();
            $('#show-review_form_wrapper').remove();
        })
        $('#respond #submit').off('click').on('click', function (event) {
            event.preventDefault();
            if( productDetails.xhr != null ) return false;
            $('#product-review-error').text('').css({
                color: 'black'
            });
            let  t = $(this).closest("form#commentform").find("#rating");
            let e = t.val();
            let msg = '';
            if (0 < t.length && !e) {
                msg = 'Vui lòng cho biết mức độ hài lòng của bạn!';
            } else if ($('form#commentform textarea#comment').val().trim() == '') {
                msg = 'Vui lòng cho biết đánh giá của bạn!';
            }
            if( msg != '' ) {
                $('#product-review-error').text(msg).css({
                    color: 'red'
                });
                return false;
            }
            productDetails.xhr = $.ajax({
                type: 'POST',
                url: gvn_home_ajax,
                dataType: 'json',
                data: {
                    action: 'gvn_add_product_review',
                    product_id: $('#product-review-id').val(),
                    review: $('form#commentform textarea#comment').val().trim(),
                    rating: e
                },
                beforeSend: function() {
                    // setting a timeout
                    $('#respond #submit').val('Đang gửi...');
                },
                success: function(data) {
                    if( !data.success ) {
                        $('#product-review-error').text(data.data.errMsg).css({
                            color: 'red'
                        });
                    } else {
                        // $('#product-review-error').text('Cảm ơn bạn đã gửi đánh giá về sản phẩm. Chúng tôi đã nhận được và đang xem xét đánh giá của bạn!').css({
                        //     color: 'green'
                        // });
                        // // remove element
                        // $('#commentform p.stars').removeClass('selected');
                        // $('#commentform p.stars a').removeClass('active');
                        // t.val('');
                        // $('#commentform p.comment-form-comment #comment').val('');
                        $('#review_form').css({ width: '400px', padding: "32px 16px 16px" });
                        $('#respond').html('');
                        let htmlTks = `
                            <div class="content-cmt-thanks">
                                <div class="icon-tks"></div>
                                <h3>Cảm ơn bạn đã đánh giá!</h3>
                                <p>Chúng tôi sẽ thông báo đến bạn khi đánh giá được duyệt. Đánh giá của bạn giúp mọi người mua sắm tốt hơn.</p>
                                <button id="close-popup-cmt" type="button">OK</button>
                            </div>
                        `;
                        $('#respond').html(htmlTks);
                    }
                },
                error: function(xhr) { // if error occured
                    // alert("Error occured.please try again");
                    console.log(xhr)
                },
                complete: function() {
                    $('#respond #submit').val('GỬI ĐÁNH GIÁ');
                    productDetails.xhr = null;
                }
            });
        });
    },
};

module.exports = productDetails;

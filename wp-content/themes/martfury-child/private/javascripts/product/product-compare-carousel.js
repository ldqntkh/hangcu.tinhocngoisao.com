'use strict';
const $ = jQuery;
const thns_home_ajax= '/wp-admin/admin-ajax.php';
var product_compare_carausel = {
    oldDataHtml: null,
    xhrRequest: null,
    init: function () {
        this.initSlide();

        this.bindEventProductCompareFromDetail();
        this.bindEventCloseModalAddProduct();

        $('body').on('click', '#add-compare-product, .product-compare .view-more-product-compare', function (e) {
            e.preventDefault();

            if (jQuery(this).is('#add-compare-product')) {
                jQuery('#modalAddCompareProduct').attr('data-need-reload', 'false');
            } else {
                jQuery('#modalAddCompareProduct').attr('data-need-reload', 'true');
            }

            product_compare_carausel.showPopupSearchProductCompare();
        });

        $('body').on('keyup', '#search-compare-product', function () {
            var search = $(this).val().trim();
            if (search.length < 3) return;
            if (product_compare_carausel.xhrRequest !== null) {
                product_compare_carausel.xhrRequest.abort();
                product_compare_carausel.xhrRequest = null;
            }

            if (!$('#input-search-loading').hasClass('compare_loading')) $('#input-search-loading').addClass('compare_loading');

            product_compare_carausel.searchProductCompare(
                {
                    action: "loadproductcompareexcludeids",
                    search_name: search,
                    product_id: product_id,
                    exclude_product_ids: product_id_exclude,
                    product_type_id: product_type_id
                },
                $('#compare-lst-products')
            )
        });

        $('body').on('click', '#compare-lst-products li span', function () {
            var id = $(this).attr('data-product-id'),
                params = {
                    addProductId: id,
                    product_id_exclude: product_id_exclude,
                    product_id: product_id
                };

            if (jQuery('#modalAddCompareProduct').attr('data-need-reload') === 'true') {
                $('#modalAddCompareProduct').modal('hide');

                jQuery('body').addClass('compare_loading');

                return product_compare_carausel.reloadPageCompare(jQuery(this).attr('data-slug'));
            }

            product_compare_carausel.getTemplateHtmlCompare(params);
        });

        $('body').on('click', '.remove-compare', function () {
            var deleteId = jQuery(this).attr('data-id'),
                params = {},
                listIds = jQuery('input.list-id-compare').val();

            listIds = listIds.split(',');

            if (listIds && listIds.length) {
                listIds = listIds.filter(id => id !== deleteId);

                params = {
                    product_id: listIds[0],
                    product_id_exclude: listIds[1],
                    addProductId: listIds[2]
                };

                product_compare_carausel.getTemplateHtmlCompare(params);
            }

            // $('.product-compare').html(product_compare_carausel.oldDataHtml);
        });
    },

    initSlide: function () {
        $(document).off('init', '.slider-images').on('init', '.slider-images', function (event, slick) {
            slick.slickNext();
        });

        $('.slider-images').each(function () {
            var at_featured_img_slider = $(this);
            var autoplaySpeed = 4000;
            var prevArrow = "<i class='icon-chevron-left slick-prev-arrow slick-arrow'></i>";
            var nextArrow = "<i class='btn-next-slick icon-chevron-right slick-next-arrow slick-arrow'></i>";

            at_featured_img_slider.css('visibility', 'visible').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                adaptiveHeight: true,
                cssEase: 'linear',
                dots: false,
                prevArrow: prevArrow,
                nextArrow: nextArrow,
                autoplaySpeed: autoplaySpeed
            });
        });
    },

    bindEventCloseModalAddProduct: function () {
        jQuery(document).delegate('#modalAddCompareProduct .modal-body .far.fa-times-circle', 'click', function () {
            jQuery('#modalAddCompareProduct').modal('hide');
        });
    },

    showPopupSearchProductCompare: function () {
        $('#modalAddCompareProduct').modal('show');
        product_compare_carausel.searchProductCompare(
            {
                action: "loadproductcompareexcludeids",
                search_name: '',
                product_id: product_id,
                exclude_product_ids: product_id_exclude,
                product_type_id: product_type_id
            },
            $('#compare-lst-products')
        )
    },

    searchProductCompare: function (dataJson, element) {
        if( typeof product_caching_compare == 'undefined' ) {
            product_compare_carausel.xhrRequest = $.ajax({
                type: "post",
                dataType: "json",
                url: thns_home_ajax,
                data: dataJson,
                beforeSend: function () {
                    // jQuery('body').addClass('compare_loading');
                },
                complete: function () {
                    $('#input-search-loading').removeClass('compare_loading')
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data;
                        let html = '';
                        html += '<ul>';
                        for (let i = 0; i < data.length; i++) {
                            html += `<li>
                                        <img src="${data[i].image}" alt="" />
                                        <h3>${data[i].name}</h3>
                                        <p class="price">${data[i].price}</p>
                                        <span data-slug="${data[i].slug}" data-product-id="${data[i].id}">Chọn sản phẩm</span>
                                    </li>`;
                        }
                        html += '</ul>';

                        element.html(html);
                    }
                },
                error: function (response) {
                    // console.log(response.message);
                }
            });
        } else {
            let data_group = product_caching_compare[product_type_id];
            let total = 0;
            let html = "<ul>";
            for( let i = 0; i < data_group.length; i++ ) {
                if( data_group[i].id != dataJson.exclude_product_ids && data_group[i].id != dataJson.product_id ) {
                    if( dataJson.search_name != '' && data_group[i].name.toLocaleLowerCase().indexOf( dataJson.search_name.toLocaleLowerCase() ) < 0 ) {
                        continue;
                    }
                    // let link = `/sssp/${product_slug_compare}-vs-${data_group[i].slug}`; 
                    html += `<li>
                        <img src="${data_group[i].image}" alt="" />
                        <h3>${data_group[i].name}</h3>
                        <span data-slug="${data_group[i].slug}" data-product-id="${data_group[i].id}">Chọn sản phẩm</span>
                    </li>`;
                    total ++;
                }
                if( total == 6 ) break;
            }
            html += "</ul>";
            element.html(html);
            $('#input-search-loading').removeClass('compare_loading')
        }
    },

    getTemplateHtmlCompare: function (params) {
        var data = {
            action: "loadproductcomparetemplate"
        };

        if (params.product_id) {
            data.product_id_1 = params.product_id;
        }

        if (params.product_id_exclude) {
            data.product_id_2 = params.product_id_exclude;
        }

        if (params.addProductId) {
            data.product_id_3 = params.addProductId;
        }

        $.ajax({
            type: "post",
            dataType: "json",
            url: thns_home_ajax,
            data: data,
            beforeSend: function () {
                jQuery('body').addClass('compare_loading');
                $('#modalAddCompareProduct').modal('hide');
            },
            success: function (response) {
                if (response.success) {
                    // product_compare_carausel.oldDataHtml = $('.product-compare').html();
                    $('.product-compare').html(response.data);

                    product_compare_carausel.initSlide();
                    jQuery('body').removeClass('compare_loading');
                }
            },
            error: function (response) {
                // console.log(response.message);
            }
        });
    },
    bindEventProductCompareFromDetail: function () {
        jQuery(document).delegate('.product-compare-default .list-product li', 'click', function (e) {
            e.preventDefault();

            product_compare_carausel.reloadPageCompare(jQuery(this).attr('data-slug'));
        });
    },
    reloadPageCompare: function (newSlug) {
        var url = location.href,
            urlArray = [];

        urlArray = url.split('-vs-');

        jQuery('body').addClass('compare_loading');

        if (urlArray && urlArray.length && newSlug) {
            return location.href = urlArray[0] + '-vs-' + newSlug;
        }

        location.href = '/';
    }
}

module.exports = product_compare_carausel;
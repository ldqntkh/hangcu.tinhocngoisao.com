'use strict';

const $ = jQuery;

const homepageconfig = {
    elementSelect: null,
    catSelected: null,
    init: function () {
        $('body').on('click', ".show_if_product_category > input.wc_shortcode_product_category_slug, p[class*=_cat_slugs_field] > input.short,  p[class*=category_slugs_field] > input.short ", function () {
            let ele = $(this);
            ele.blur();
            homepageconfig.elementSelect = ele;
            homepageconfig.renderListCategory(ele);
        });

        $('body').on('click', '#cancelCategories', function () {
            $('#popup-cats').remove();
            homepageconfig.catSelected = [];
            homepageconfig.elementSelect = null;
        });

        $('body').on('click', '#saveCategories', function () {
            homepageconfig.elementSelect.val(homepageconfig.catSelected.join(','));
            homepageconfig.elementSelect = null;
            homepageconfig.catSelected = [];
            $('#popup-cats').remove();
        });

        $('body').on('change', 'input.cat-item', function () {
            var val = $(this).val().trim();
            if (this.checked) {
                homepageconfig.catSelected.push(val);
            } else {
                var filtered = homepageconfig.catSelected.filter(function (value, index, arr) {
                    return value != val;
                });
                homepageconfig.catSelected = filtered;
            }
            homepageconfig.renderListCategorySelected();
        })
    },

    renderListCategory: function (ele) {
        homepageconfig.catSelected = [];
        $('#popup-cats').remove();

        var val = ele.val().trim();
        var vals = val.split(',');
        var keys = Object.keys(lstCategories);
        var html = '';
        html += '<div id="popup-cats">';
        html += '<div class="container">';
        html += '<div class="lst-cat">';
        html += '<div id="lst-cat-label">';
        for (var i = 0; i < keys.length; i++) {
            if (vals.indexOf(keys[i]) > -1) {
                html += '<label><input class="cat-item" type="checkbox" checked="checked" value="' + keys[i] + '"/>' + lstCategories[keys[i]] + '</label>';

            } else html += '<label><input class="cat-item" type="checkbox" value="' + keys[i] + '"/>' + lstCategories[keys[i]] + '</label>';
        }
        html += '</div>';
        html += '<div id="lst-cat-label-selected" class="drag-container">';

        html += '</div>';
        html += '</div>';
        html += '<div class="btns"><button class="button" type="button" id="saveCategories">Lưu</button><button class="button" type="button" id="cancelCategories">Hủy</button></div>';
        html += '</div>';
        html += '</div>';
        $('body').append(html);
        homepageconfig.catSelected = vals;
        homepageconfig.renderListCategorySelected();
    },

    renderListCategorySelected: function () {
        var html = '';
        var ele = $('#lst-cat-label-selected');
        ele.html('');

        for (var i = 0; i < homepageconfig.catSelected.length; i++) {
            if (lstCategories[homepageconfig.catSelected[i]]) {
                html += '<div class="drag-box" data-key="' + homepageconfig.catSelected[i] + '">' + lstCategories[homepageconfig.catSelected[i]] + '</div>';
            }
        }
        ele.html(html);
        dragonfly('.drag-container', function () {
            homepageconfig.updateIndexCats();
        });
    },
    updateIndexCats: function () {
        var arrRS = [];
        $('#lst-cat-label-selected').find('.drag-box').each(function () {
            var val = $(this).attr('data-key');
            if (val) arrRS.push(val);
        });

        if (arrRS.length > 0) {
            homepageconfig.catSelected = arrRS;
        }
    }
}

export default homepageconfig;
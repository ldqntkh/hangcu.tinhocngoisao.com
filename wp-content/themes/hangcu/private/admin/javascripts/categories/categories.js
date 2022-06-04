'use strict';

const $ = jQuery;

const categories = {
    toogleAddNew: false,
    fetching : false,
    terms: [],
    init: function () {
        $('body').on('click', '#addnewtop5', function () {
            if (categories.toogleAddNew) return;
            categories.toogleAddNew = true;
            categories.showFormAddNewTop5();
        });

        $('body').on('click', '#inserttop5', function () {
            var short_title = $('#short_title').val().trim();
            var title = $('#title').val().trim();
            var link_category = $('#link_category').val().trim();
            var group_category = $('#group_category').val().trim();
            if (short_title == "" || title == "" || link_category == "" || group_category == "") {
                $("top5error").text("Vui lòng nhập đầy đủ thông tin!");
                return;
            } else {
                var val = $('#hangcu_cat_top5banchay').val();
                if (val == "") {
                    val = [];
                } else {
                    if (typeof val == 'string')
                        val = JSON.parse(val);
                }

                val.push({
                    short_title: short_title,
                    title: title,
                    link_category: link_category,
                    group_category: group_category
                });

                $('#hangcu_cat_top5banchay').val(JSON.stringify(val));
                $('#addnewcontenttop5').html('');
                categories.reInitDragItem(val);
                categories.toogleAddNew = false;
            }
        });

        $('body').on('click', '#addnewcontenttop5items span.removetop5', function () {
            var parent = $(this).parents('.drag-box');
            var term_slug = $(parent).attr('term_slug');
            var arr = categories.terms.filter(function (item) {
                return item.group_category !== term_slug
            });
            parent.remove();
            categories.terms = arr;
            categories.updateIndexTop5();

        });

        categories.initListTerms();

        categories.initExportData();
    },

    reInitDragItem: function (val) {
        if (typeof val == 'undefined') return;
        $('#addnewcontenttop5items').html('');
        categories.terms = [];
        for (var i = 0; i < val.length; i++) {
            $('#addnewcontenttop5items').append('<div class="drag-box" term_slug="' + val[i].group_category + '" > Short title: ' + val[i].short_title + '</br> Title: ' + val[i].title + '</br> Url: ' + val[i].link_category + '<span class="removetop5">x</span></div>');
            categories.terms.push(val[i]);
        }
        dragonfly('.drag-container', function () {
            categories.updateIndexTop5();
        });
    },
    updateIndexTop5: function () {
        var arrRs = [];
        $('#addnewcontenttop5items').find('.drag-box').each(function () {
            var term_slug = $(this).attr('term_slug');
            let obj = categories.terms.find(o => o.group_category === term_slug);
            if (obj) {
                arrRs.push(obj);
            }
        });
        if (arrRs.length == 0) return;
        categories.terms = arrRs;
        $('#hangcu_cat_top5banchay').val(JSON.stringify(arrRs));
    },
    showListSelectCat: function () {
        if (typeof lstSelectCats != 'undefined') {

            var html = '<option value="">Chọn nhóm sản phẩm</option>';
            for (var i = 0; i < lstSelectCats.length; i++) {
                if (lstSelectCats[i].term_id == $term_id) continue;
                let obj = categories.terms.find(o => o.group_category === lstSelectCats[i].slug);
                if (obj) continue;

                html += '<option value="' + lstSelectCats[i].slug + '">' + lstSelectCats[i].name + '</option>';
            }

            return html;
        }
    },
    showFormAddNewTop5: function () {
        var element = $('#addnewcontenttop5');
        var html = `<div class="content">
                        <span class="error" id="top5error"></span></br>
                        <div class="row">
                            <label>Short title*</label>
                            <input name="short_title" id="short_title" type="text" value="" placeholder="Short title" />
                        </div>
                        <div class="row">
                            <label>Title*</label>
                            <input name="title" id="title" type="text" value="" placeholder="Title" />
                        </div>
                        <div class="row">
                            <label>Url Redirect*</label>
                            <input name="link_category" id="link_category" type="text" value="" placeholder="Url Redirect" />
                        </div>
                        <div class="row">
                            <label>Nhóm sản phẩm*</label>
                            <select name="group_category" id="group_category" value="">
                                ${ categories.showListSelectCat()}
                            </select>
                        </div>
                        <button type="button" id="inserttop5" class="button">Thêm</button>
                    </div>`;
        element.html(html);
    },
    initListTerms: function () {
        var val = $('#hangcu_cat_top5banchay').val();
        if (val == "") {
            val = [];
        } else {
            if (typeof val == 'string')
                val = JSON.parse(val);
        }
        categories.reInitDragItem(val);
    },

    initExportData: function() {
        //export_product_cat_
        $('body').on('click', 'a[id^="export_product_cat_"]', function(e) {
            e.preventDefault();
            if(categories.fetching) return;
            let id = $(this).attr('id');
            console.log(id)
            let idNum = id.split('export_product_cat_')[1];
            idNum = parseInt(idNum);

            categories.fetching = true;
            $('#'+id).text('Pedding');
            $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                    action: 'export_product_data_by_cat_id',
                    cat_id: idNum
                },
                success: function (res) {
                    if( res.success ) {
                        categories.downloadFile(JSON.stringify(res.data), id + '.json', 'json' );
                    } 
                },
                error: function (err) {
                    if (err && err.errMsg) {
                        alert(err.errMsg);
                    } else {
                        alert("Đã có lỗi xảy ra. Vui lòng liên hệ kỹ thuật để được xử lý");
                    }
                }, complete: function() {
                    categories.fetching = false;
                    $('#'+id).text('Export');
                }
            });
            
        })
    },
    downloadFile : function (data, filename, type) {
        var file = new Blob([data], {type: type});
        if (window.navigator.msSaveOrOpenBlob) // IE10+
            window.navigator.msSaveOrOpenBlob(file, filename);
        else { // Others
            var a = document.createElement("a"),
                    url = URL.createObjectURL(file);
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);  
            }, 0); 
        }
    }
}

export default categories;
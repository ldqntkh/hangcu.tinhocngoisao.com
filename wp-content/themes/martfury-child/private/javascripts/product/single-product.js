
'use strict';
const $ = jQuery;
const thns_home_ajax= '/wp-admin/admin-ajax.php';
const upload_media = 'https://media.tinhocngoisao.com/media/upload-';
const delete_media = 'https://media.tinhocngoisao.com/media/delete-media';

var singleProduct = {
    xhr: null,
    xhrVideo : null,
    xhrImages : [null, null, null, null, null],
    chooseUpload: '',
    init: function () {
        singleProduct.compareProduct();
        singleProduct.initChooseMediaType();
        singleProduct.initMediaAction();
        if( window.location.href.indexOf('#cmt-lst-tags') > -1 ) {
            $('.tab-reviews').trigger('click');
        }

        $('#commentform').on('submit', function(e) {
            let total = 0;
            for(let i = 0; i < 5; i++) {
                if(singleProduct.xhrImages[i] == null) total++;
            }
            if( singleProductxhrVideo != null || total != 5 ) {
                e.preventDefault();
                return false;
            }
        });

        $('.cmt-media video').on('click', function() {
            let src = $(this).attr('src');
            if( !src ) return false;
            let content = `<div id="popup-media" class="martfury-modal mf-newsletter-popup open" tabindex="-1" aria-hidden="true">
                                <div class="mf-modal-overlay"></div>
                                <div class="modal-content">
                                    <a href="#" class="close-modal" id="delete-modal-media">
                                        <i class="icon-cross"></i>
                                    </a>
                                    <div class="newletter-content">
                                        <video controls autoplay>
                                            <source src="${src}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                            </div>`;
            $('body').append(content);
        });

        $('.cmt-media img').on('click', function() {
            let src = $(this).attr('src');
            if( !src ) return false;
            let content = `<div id="popup-media" class="martfury-modal mf-newsletter-popup open" tabindex="-1" aria-hidden="true">
                                <div class="mf-modal-overlay"></div>
                                <div class="modal-content">
                                    <a href="#" class="close-modal" id="delete-modal-media">
                                        <i class="icon-cross"></i>
                                    </a>
                                    <div class="newletter-content">
                                        <img src="${src}" />
                                    </div>
                                </div>
                            </div>`;
            $('body').append(content);
        });

        $(document).on('click', '#delete-modal-media', function(e) {
            e.preventDefault();
            $('#popup-media').remove();
        });
    },

    compareProduct: function () {
        if (
            typeof product_id === "undefined" ||
            typeof product_type_id === "undefined"
        )
            return;
        $("body").on("keyup", "#input-product-compare", function (e) {
            if (singleProduct.xhr != null) singleProduct.xhr.abort();
            var inputValue = $(this).val();
            if (inputValue !== "") {
                var $search_suggestions = $("#search-suggestions");
                $search_suggestions.css({
                    display: "block"
                });
                $search_suggestions.html(`<p>Đang tìm kiếm....</p>`);
                if( typeof product_caching_compare == 'undefined' ) {
                    singleProduct.xhr = $.ajax({
                        type: "post",
                        dataType: "json",
                        url: thns_home_ajax,
                        data: {
                            action: "load_product_compare",
                            product_id: product_id,
                            product_type_id: product_type_id,
                            search_name: inputValue
                        },
                        success: function (response) {
                            $search_suggestions = $("#search-suggestions");
        
                            var data = response.data;
                            if (!data || data.length == 0) {
                                $search_suggestions.css({
                                    display: "block"
                                });
                                $search_suggestions.html(`<p>Không tìm thấy sản phẩm phù hợp!</p>`);
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

    initChooseMediaType: function() {
        // <label><input type="radio" name="choose-media" value="video" />Video</label>
        // <label><input type="radio" name="choose-media" value="image" />Hình ảnh</label>
        let choose = `<div class="choose">
                            <strong>Tải lên nội dung: </strong>
                            
                        </div>`;
        $('#media-fields').append( choose );
        singleProduct.initUploadMedia();
        // $(document).on('change', 'input[name=choose-media]', function() {
        //     singleProduct.chooseUpload = this.value;

        //     // gọi api xóa dữ liệu
        // });
    },

    initUploadMedia: function() {
        if( $('#media-fields').length > 0 ) {
            let parent = $('#media-fields');
            let btnUploadVideo = `<div class="custom-video-upload"><label>
                                        <input id="video-file" type="file" accept="video/mp4,video/x-m4v,video/*"/>
                                        <i class="fa fa-video-camera"></i>
                                    </label><i class="fa fa-times-circle hidden" id="remove-video"></i></div>`;
            let btnUploadImage = `<div class="upload-images" >
                    <div class="custom-image-upload">
                        <label>
                            <input id="image-file-1" type="file" accept="image/png,image/jpeg"/>
                            <i class="fa fa-camera-retro"></i>
                        </label>
                        <i class="fa fa-times-circle hidden" id="remove-image-1"></i>
                    </div>    
                    <div class="custom-image-upload">
                        <label>
                            <input id="image-file-2" type="file" accept="image/png,image/jpeg"/>
                            <i class="fa fa-camera-retro"></i>
                        </label>
                        <i class="fa fa-times-circle hidden" id="remove-image-2"></i> 
                    </div> 
                    <div class="custom-image-upload">
                        <label>
                            <input id="image-file-3" type="file" accept="image/png,image/jpeg"/>
                            <i class="fa fa-camera-retro"></i>
                        </label>
                        <i class="fa fa-times-circle hidden" id="remove-image-3"></i>
                    </div>  
                    <div class="custom-image-upload">
                        <label>
                            <input id="image-file-4" type="file" accept="image/png,image/jpeg"/>
                            <i class="fa fa-camera-retro"></i>
                        </label>
                        <i class="fa fa-times-circle hidden" id="remove-image-4"></i>  
                    </div>
                    <div class="custom-image-upload">
                        <label>
                            <input id="image-file-5" type="file" accept="image/png,image/jpeg"/>
                            <i class="fa fa-camera-retro"></i>
                        </label>
                        <i class="fa fa-times-circle hidden" id="remove-image-5"></i>  
                    </div>
            </div>`;
            parent.append( btnUploadVideo ).append( btnUploadImage );
            // // xử lý xóa video
            // if( singleProduct.chooseUpload == 'video' ) { 
            //     $('.upload-images').remove();
            //     parent.append( btnUploadVideo );
            // } else if( singleProduct.chooseUpload == 'image' ) { 
            //     $('.custom-video-upload').remove();
                
            // }
        }
    },

    initMediaAction: function() {
        if( $('#media-fields').length > 0 ) {
            $(document).on('change', '#video-file', function() {
                if( $('#media_video_url').val() != '' ) return false;
                try {
                    var form = new FormData();
                    var file = $(this)[0].files[0];
                    form.append("video", file, file.name );
                    form.append("post_id", product_id);
                    singleProduct.xhrVideo = $.ajax({
                        
                        url: upload_media + 'video',
                        data: form,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        // dataType: 'jsonp',
                        // "headers":{
                        //     // "Content-Type":"multipart/form-data",
                        //     "Accept":"application/json, text/plain, */*"
                        // },
                        
                        beforeSend: function() {
                            $('.custom-video-upload label').addClass('uploading');
                            $('input#submit').addClass('disabled');
                        },
                        success: function(data) {
                            // gắn vào hidden
                            $('#media_video_url').val(data.file_url);
                            $('.custom-video-upload').addClass('has-uploaded');

                        },
                        error: function(xhr, status, error) { // if error occured
                            console.log(error);
                        },
                        complete: function() {
                            $('.custom-video-upload label').removeClass('uploading');
                            $('input#submit').removeClass('disabled');
                            singleProduct.xhrVideo = null;
                        }
                    });
                } catch (err) {
                    console.log(err);
                }
            });
            // remove video
            $(document).on('click', '#remove-video', function() {
                // call api xóa video
                try {
                    singleProduct.xhrVideo = $.ajax({
                        url: delete_media,
                        method:"POST",
                        data: { 
                            "post_id" : product_id,
                            "file_url" : $('#media_video_url').val()
                        },
                        beforeSend: function() {
                            $('.custom-video-upload label').addClass('uploading');
                            $('input#submit').addClass('disabled');
                        },
                        success: function(data) {
                            if( data.success == true ) {
                                $('#media_video_url').val('');
                                $('.custom-video-upload').removeClass('has-uploaded');
                                $('#video-file').val('');
                            } else {
                                alert('Có lỗi khi thực hiện thao tác này');
                            }
                        },
                        error: function(xhr, status, error) { // if error occured
                            console.log(error);
                        },
                        complete: function() {
                            $('.custom-video-upload label').removeClass('uploading');
                            $('input#submit').removeClass('disabled');
                            singleProduct.xhrVideo = null;
                        }
                    });
                } catch (err) {
                    console.log(err);
                }
                
            });

            // Images
            $(document).on('change', "[id^='image-file-']", function() {
                
                try {
                    var form = new FormData();
                    var file = $(this)[0].files[0];
                    form.append("image", file, file.name );
                    form.append("post_id", product_id);

                    let id_index = $(this).attr('id').replace( 'image-file-', '' );
                    
                    singleProduct.xhrImages[id_index-1] = $.ajax({
                        url: upload_media + 'image',
                        data: form,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        // dataType: 'jsonp',
                        // "headers":{
                        //     // "Content-Type":"multipart/form-data",
                        //     "Accept":"application/json, text/plain, */*"
                        // },
                        
                        beforeSend: function() {
                            $($('.upload-images').find('label')[id_index - 1]).addClass('uploading');
                            $('input#submit').addClass('disabled');
                        },
                        success: function(data) {
                            // gắn vào hidden
                            
                            let vals = $('#media_image_urls').val();
                            if( vals ) {
                                vals = JSON.parse(vals);
                            } else {
                                vals = [];
                            }
                            vals[id_index-1] = data.file_url;
                            $('#media_image_urls').val( JSON.stringify(vals) );

                            $($('.upload-images').find('.custom-image-upload')[id_index - 1]).addClass('has-uploaded');

                        },
                        error: function(xhr, status, error) { // if error occured
                            console.log(error)
                        },
                        complete: function() {
                            $($('.upload-images').find('label')[id_index - 1]).removeClass('uploading');
                            $('input#submit').removeClass('disabled');
                            singleProduct.xhrImages[id_index-1] = null;
                        }
                    });
                } catch (err) {
                    console.log(err);
                }
            });

            // remove image
            $(document).on('click', "[id^='remove-image-']", function() {
                // call api xóa video
                try {
                    let id_index = $(this).attr('id').replace( 'remove-image-', '' );
                    let vals = $('#media_image_urls').val();
                    if( vals ) {
                        vals = JSON.parse(vals);
                        if( vals[id_index-1] == null ) return false;
                    } else {
                        return false;
                    }
                    singleProduct.xhrImages[id_index-1] = $.ajax({
                        url: delete_media,
                        method:"POST",
                        data: { 
                            "post_id" : product_id,
                            "file_url" : vals[id_index-1]
                        },
                        beforeSend: function() {
                            $($('.upload-images').find('label')[id_index - 1]).addClass('uploading');
                            $('input#submit').addClass('disabled');
                        },
                        success: function(data) {
                            if( data.success == true ) {
                                vals[id_index-1] = null;
                                $('#media_image_urls').val( JSON.stringify(vals) );
                                $($('.upload-images').find('.custom-image-upload')[id_index - 1]).removeClass('has-uploaded');
                                $('#image-file-' + id_index).val('');
                            } else {
                                alert('Có lỗi khi thực hiện thao tác này');
                            }
                        },
                        error: function(xhr, status, error) { // if error occured
                            console.log(error)
                        },
                        complete: function() {
                            $($('.upload-images').find('label')[id_index - 1]).removeClass('uploading');
                            $('input#submit').removeClass('disabled');
                            singleProduct.xhrImages[id_index-1] = null;
                        }
                    });
                } catch (err) {
                    console.log(err);
                }
                
            });
        }
    }
}

module.exports = singleProduct;

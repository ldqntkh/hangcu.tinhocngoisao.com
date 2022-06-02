var $ = jQuery;
$(document).ready(function(){
    $('.upload-btn').click(function(e) {
        $(this).addClass('selected');
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('.selected').siblings().val(image_url);
            $('.upload-btn').removeClass('selected');
        });
    });
});

jQuery(document).ready(function(){
    jQuery('.upload-btn').click(function(e) {
        jQuery(this).addClass('selected');
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            jQuery('.selected').siblings().val(image_url);
            jQuery('.upload-btn').removeClass('selected');
        });
    });
    jQuery(function() {
        jQuery('.color-field').wpColorPicker();
        jQuery( '.cpa-color-picker' ).wpColorPicker();
    });
});
'use strict';
const $ = jQuery;
var mediaLibrary = {

    selectImageSlider: function() {
        var product_gallery_frame;
        $(document).on('click', '.add_product_images_slider a', function( event ) {
            var $el = $( this );
            var inputValueId = $el.attr('data-input-id');
            var $product_images    = $( '#product_images_container' + $el.attr('data-block') ).find( 'ul.product_images' );
        
            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( product_gallery_frame ) {
                // product_gallery_frame.open();
                // return;
                product_gallery_frame= null;
            }

            // Create the media frame.
            product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                // Set the title of the modal.
                title: $el.data( 'choose' ),
                button: {
                    text: $el.data( 'update' )
                },
                states: [
                    new wp.media.controller.Library({
                        title: $el.data( 'choose' ),
                        filterable: 'all',
                        multiple: true
                    })
                ]
            });

            // When an image is selected, run a callback.
            product_gallery_frame.on( 'select', function() {
                var selection = product_gallery_frame.state().get( 'selection' );
                var inputValue = $('#' + inputValueId);
                var flag = false;
                
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    if (!flag) {
                        if ( attachment.id ) {
                            if (inputValue.attr('data-select') && inputValue.attr('data-select') === 'image') {
                                flag =true;
                                $product_images.html('');
                                inputValue.val('');
                            }
                            inputValue.val( inputValue.val().length == 0 ? attachment.id :  inputValue.val() + ',' +  attachment.id );
                            
                            var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
    
                            $product_images.append(
                                '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image +
                                '" /><ul class="actions"><li><a href="#" class="delete" '+
                                'data-container-id="product_images_container' + $el.attr('data-block')+  '"' +
                                ' title="' + $el.data('delete') + '">' +
                                $el.data('text') + '</a></li></ul></li>'
                            );
                        }
                    };
                });
            });

            // Finally, open the modal.
            product_gallery_frame.open();
        });

        
    },

    deleteImageSlider: function() {
        $(document).on('click', "div[id^=product_images_container-] ul.product_images a.delete", function() {
            $( this ).closest( 'li.image' ).remove();
            var $el = $( this );
            var $product_images    = $('#' + $el.attr('data-container-id')).find( 'ul.product_images' );
            var attachment_ids = '';
            
            var inputValueId = $el.attr('data-input-id');
            var inputValue = $('#' + inputValueId);

            $product_images.find( 'li.image' ).css( 'cursor', 'default' ).each( function() {
                var attachment_id = $( this ).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });
    
            inputValue.val( attachment_ids );
    
            // Remove any lingering tooltips.
            $( '#tiptip_holder' ).removeAttr( 'style' );
            $( '#tiptip_arrow' ).removeAttr( 'style' );
    
            return false;
        });
    },


    init: function() {
        this.selectImageSlider();
        this.deleteImageSlider();
    }
}

module.exports = mediaLibrary;
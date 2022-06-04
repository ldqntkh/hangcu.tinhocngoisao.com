'use strict';

const $ = jQuery;

const edit_account = {
    init: function() {
        edit_account.changeAvatar();
    },

    changeAvatar: function() {      

        $('body').on( 'change', '#account_image', function( e ){ 
            edit_account.encodeImageFileAsURLL( $(this)[0] );
        } )

        $('body').on( 'click', '#user_avatar', function(e) {
            $('#account_image').trigger('click');
        } );
    },

    encodeImageFileAsURLL: function (element) {
        if ( !element.files || element.files.length <= 0 ) return false;
        let file = element.files[0];
        let file_size = file.size / 1024 / 1024;
        if ( file_size > 2 ) {
            alert( 'Ảnh đại diện tối đa cho phép chỉ 2MB.' );
            return false;
        }
        if ( file ) {
            let reader = new FileReader();
            reader.onloadend = function() {
                if ( reader.result ) {
                    $('span.dp-name').css({
                        'display': 'none'
                    });
                    if ( $('#user_avatar').find('img').length > 0 ) {
                        $('#user_avatar img').remove();
                    }
                    let html = '<img src="' + reader.result + '" alt="" />';
                    $('#user_avatar').prepend( html );
                }
            }
            reader.readAsDataURL(file);
        }
    }
}

export default edit_account;
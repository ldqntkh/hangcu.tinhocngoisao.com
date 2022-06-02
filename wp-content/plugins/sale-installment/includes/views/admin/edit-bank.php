<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bank = $bank[0];
?>


<h1><?php _e('Chỉnh sửa ngân hàng', BANK_PLUGIN_NAME) ?></h1>
<div class="brands-container">
	
	<div class="left-container">
		<form class="form" id="bank-data-update" method="post">
			<div class="form-field form-required term-name-wrap">
				<label for="bank-name"><?php _e('Tên ngân hàng', BANK_PLUGIN_NAME) ?></label>
				<input name="bank-name" id="bank-name" type="text" value="<?php echo $bank->bank_name ?>" size="40" aria-required="true">
			</div>

			<div class="form-field term-bank-type-wrap">
				<label for="bank-type"><?php _e( 'Loại ngân hàng', BANK_PLUGIN_NAME ) ?></label>
				<select name="bank-type" id="bank-type" class="postform">
					<option <?php if( $bank->bank_type == 1 ) echo 'selected' ?> value="1"><?php _e( 'Tổ chức tài chính', BANK_PLUGIN_NAME ) ?></option>
					<option <?php if( $bank->bank_type == 0 ) echo 'selected' ?> value="0"><?php _e( 'Thẻ tín dụng', BANK_PLUGIN_NAME ) ?></option>
				</select>
			</div>

			<div class="form-field term-thumbnail-wrap">
				<label><?php _e('Hình ảnh ngân hàng. (Size: 280px x 150px )',) ?></label>
                <?php 
                    $image = wp_get_attachment_image_src( $bank->bank_img, 'full' );
                    if ( !$image ) {
                        $image = esc_js( wc_placeholder_img_src() );
                    } else {
                        $image = $image[0];
                    }

                ?>
				<div id="bank_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo $image ?>" width="280px" height="150px"></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="bank_thumbnail_id" name="bank_thumbnail_id" value="<?php echo $bank->bank_img ?>">
					<button type="button" class="upload_image_button button"><?php _e('Tải lên/Thêm ảnh', BANK_PLUGIN_NAME) ?></button>
					<button type="button" class="remove_image_button button"><?php _e('Loại bỏ ảnh', BANK_PLUGIN_NAME) ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( ! jQuery( '#bank_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php esc_html_e( 'Chọn hình ảnh', BANK_PLUGIN_NAME ); ?>',
							button: {
								text: '<?php esc_html_e( 'Sử dụng hình ảnh', BANK_PLUGIN_NAME ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
                            var attachment_thumbnail = attachment.sizes.full;
							jQuery( '#bank_thumbnail_id' ).val( attachment.id );
							jQuery( '#bank_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#bank_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#bank_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

					jQuery( document ).ajaxComplete( function( event, request, options ) {
						if ( request && 4 === request.readyState && 200 === request.status
							&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

							var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
							if ( ! res || res.errors ) {
								return;
							}
							// Clear Thumbnail fields on submit
							jQuery( '#bank_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
							jQuery( '#bank_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							// Clear Display type field on submit
							jQuery( '#display_type' ).val( '' );
							return;
						}
					} );

				</script>
				<div class="clear"></div>
			</div>

			<div class="form-field form-required term-index-wrap">
				<label for="bank-index"><?php _e('Độ ưu tiên', BANK_PLUGIN_NAME) ?></label>
				<input type="number" min="0" name="bank-index" id="bank-index" type="text" value="<?php echo $bank->display_index ?>" size="40" aria-required="true" />
			</div>
            
            <input name="bank-id" id="bank-id" type="hidden" value="<?php echo $bank->ID ?>">

			<div class="form-field form-required term-name-wrap">
				<button class="button" type="submit">
					<?php _e('Cập nhật', BANK_PLUGIN_NAME) ?>
					<span class="spinner is-active hide"></span>
				</button>
                <a class="button" href="<?php echo admin_url( 'admin.php?page=star_banks' ); ?>"><?php _e('Quay lại trang danh sách', BANK_PLUGIN_NAME) ?></a>
			</div>
		</form>
	</div>
    <script>
		const bank_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		const thumb_image = '<?php echo esc_js( wc_placeholder_img_src() ); ?>';
        const bank_page = "<?php echo admin_url( 'admin.php?page=star_banks' ); ?>";
	</script>
</div>
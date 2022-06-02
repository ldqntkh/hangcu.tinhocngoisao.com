<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h1><?php _e('Quản lý ngân hàng', BANK_PLUGIN_NAME) ?></h1>
<div class="brands-container">
	
	<div class="left-container">
		<form class="form" id="bank-data" method="post">
			<h3><?php _e( 'Thêm ngân hàng mới', BANK_PLUGIN_NAME ) ?></h3>

			<div class="form-field form-required term-name-wrap">
				<label for="bank-name"><?php _e('Tên ngân hàng', BANK_PLUGIN_NAME) ?></label>
				<input name="bank-name" id="bank-name" type="text" value="" size="40" aria-required="true">
			</div>

			<div class="form-field term-bank-type-wrap">
				<label for="bank-type"><?php _e( 'Loại ngân hàng', BANK_PLUGIN_NAME ) ?></label>
				<select name="bank-type" id="bank-type" class="postform">
					<option value="1"><?php _e( 'Tổ chức tài chính', BANK_PLUGIN_NAME ) ?></option>
					<option value="0"><?php _e( 'Thẻ tín dụng', BANK_PLUGIN_NAME ) ?></option>
				</select>
			</div>

			<div class="form-field term-thumbnail-wrap">
				<label><?php _e('Hình ảnh ngân hàng. (Size: 280px x 150px )', BANK_PLUGIN_NAME) ?></label>
				<div id="bank_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="280px" height="150px"></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="bank_thumbnail_id" name="bank_thumbnail_id">
					<button type="button" class="upload_image_button button"><?php _e('Tải lên/Thêm ảnh', BANK_PLUGIN_NAME) ?></button>
					<button type="button" class="remove_image_button button" style="display: none;"><?php _e('Loại bỏ ảnh', BANK_PLUGIN_NAME) ?></button>
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
				<input type="number" min="0" name="bank-index" id="bank-index" type="text" value="" size="40" aria-required="true" />
			</div>

			<div class="form-field form-required term-name-wrap">
				<button class="button" type="submit">
					<?php _e('Lưu', BANK_PLUGIN_NAME) ?>
					<span class="spinner is-active hide"></span>
				</button>
			</div>
		</form>
	</div>
	<div class="right-container" id="list-banks">
		<table class="wp-list-table widefat fixed striped tags ui-sortable">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Hình ảnh', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Tên', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Loại', BANK_PLUGIN_NAME) ?></strong></th>
					<th scope="col" class="manage-column column-thumb"><strong><?php _e('Actions', BANK_PLUGIN_NAME) ?></strong></th>
				</tr>
			</thead>
			<?php 
				$objBank = new Bank();
				$banks = $objBank->getListBanks();
				echo '<tbody id="the-list">';
				if ( $banks ) { 
					foreach( $banks as $bank ) : 
						$image = wp_get_attachment_image_src( $bank->bank_img, 'full' );
						if ( !$image ) {
							$image = esc_js( wc_placeholder_img_src() );
						} else {
							$image = $image[0];
						}
					?>
						<tr>
							<td scope="col" class="manage-column column-thumb">
								<img src="<?php echo $image ?>" width="70px" height="37px"/>
							</td>
							<td scope="col" class="manage-column column-thumb colum-action">
								<p><strong><?php echo $bank->bank_name ?></strong></p>
								<p class="action">
									<a href="<?php echo admin_url( 'admin.php?page=star_banks&type=edit&bank_id='.$bank->ID) ?>" class="edit-brand" action='edit' data-id="<?php echo $bank->ID ?>"><?php _e('Sửa', BANK_PLUGIN_NAME) ?></a>
									&nbsp;|&nbsp;
									<a href="#" class="remove-bank" action='delete' data-title='<?php echo $bank->bank_name ?>' data-id="<?php echo $bank->ID ?>"><?php _e('Xóa', BANK_PLUGIN_NAME) ?></a>
								</p>
							</td>
							<?php 
								if ( $bank->bank_type == 1 ) { ?>
									<td scope="col" class="manage-column column-thumb" style="color: green"><?php _e( 'Tổ chức tài chính', BANK_PLUGIN_NAME ) ?></td>
								<?php } else { ?>
									<td scope="col" class="manage-column column-thumb" style="color: red"><?php _e( 'Tín dụng', BANK_PLUGIN_NAME ) ?></td>
								<?php }
							?>

							<td>
								<?php 
									if ( $bank->bank_type == 1 ) { ?>
										<a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-installment&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thiết lập trả góp', BANK_PLUGIN_NAME) ?></a>
									<?php } else { ?>
										<a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-sub&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thêm sub bank', BANK_PLUGIN_NAME) ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo admin_url( 'admin.php?page=star_banks&type=insert-installment&bank_id='.$bank->ID) ?>" data-id="<?php echo $bank->ID ?>"><?php _e('Thiết lập trả góp', BANK_PLUGIN_NAME) ?></a>
									<?php }
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php }
				echo '</tbody>';
			?>
		</table>
	</div>

	<script>
		const bank_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		const thumb_image = '<?php echo esc_js( wc_placeholder_img_src() ); ?>';
	</script>
</div>
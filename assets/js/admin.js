/* global notify_users_e_mail_params, wp */
(function ( $ ) {
	'use strict';

	/**
	 * Theme Options and Metaboxes.
	 */
	$( function () {

		/**
		 * Image field.
		 */
		$( '.notify-users-e-mail-upload-image .button' ).on( 'click', function ( e ) {
			e.preventDefault();

			var uploadFrame,
				uploadWrap    = $( this ).parent( '.notify-users-e-mail-upload-image' ),
				uploadInput   = $( '.notify-users-e-mail-image', uploadWrap ),
				uploadPreview = $( '.notify-users-e-mail-preview', uploadWrap );

			// If the media frame already exists, reopen it.
			if ( uploadFrame ) {
				uploadFrame.open();

				return;
			}

			// Create the media frame.
			uploadFrame = wp.media.frames.downloadable_file = wp.media({
				title: notify_users_e_mail_params.uploadTitle,
				button: {
					text: notify_users_e_mail_params.uploadButton
				},
				multiple: false,
				library: {
					type: 'image'
				}
			});

			uploadFrame.on( 'select', function () {
				var attachment = uploadFrame.state().get( 'selection' ).first().toJSON();
				uploadPreview.attr( 'src', attachment.url );
				uploadInput.val( attachment.id );
			});

			// Finally, open the modal.
			uploadFrame.open();
		});

		$( '.notify-users-e-mail-delete' ).on( 'click', function ( e ) {
			e.preventDefault();

			var wrapper      = $( this ).parents( '.notify-users-e-mail-upload-image' ),
				defaultImage = $( '.notify-users-e-mail-default-image', wrapper ).text();

			$( '.notify-users-e-mail-image', wrapper ).val( '' );
			$( '.notify-users-e-mail-preview', wrapper ).attr( 'src', defaultImage );
		});


		$( '.input-select2-tags' ).each(function(k, el){
			var $el = $( el ),
				data = $el.data( 'options' ),
				opts = {
					multiple: true,
					placeholder: $el.attr( 'placeholder' ),
					allowClear: true,
					minimumResultsForSearch: -1,
					data: data
				};

			$el.select2( opts )
		});

	});
}( jQuery ));

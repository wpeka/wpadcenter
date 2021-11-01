/**
 * AdSense Api integration.
 *
 * @since 1.1.0
 *
 * @package
 */

( function( $ ) {
	/**
	 * Connect to google adsense
	 *
	 * @package
	 * @author     WPEka Club <support@wpeka.com>
	 */
	/*global jQuery, ajaxurl, AdsenseGAPI*/
	$( document ).ready(
		function() {
			var AUTH_WINDOW = null;
			$( '.init-gauthentication' ).click(
				function( e ) {
					$( '#mapi-confim-code' ).removeAttr( 'disabled', false );
					AUTH_WINDOW = window.open( AdsenseGAPI.oAuth2, 'advadsOAuth2' );
					$( '#gadsense-modal' ).show();
				},
			);

			$( '.remove-gauthentication' ).click( function( e ) {
				var spinner = $( '#gadsense_remove_authentication' ).parent().find( '.spinner' );
				spinner.addClass( 'is-active' );
				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'adsense_remove_authentication',
						nonce: AdsenseGAPI.nonce,
					},
				} ).done( function( data ) {
					spinner.removeClass( 'is-active' );
					location.reload();
				} );
			} );

			// Confirm code for account connection.
			$( '#mapi-confirm-code' ).click(
				function( e ) {
					e.preventDefault();

					var spinner = $( '#mapi-confirm-code' ).parent().find( '.spinner' );
					spinner.addClass( 'is-active' );
					var code = $( '#mapi-code' ).val();
					if ( '' == code ) {
						spinner.removeClass( 'is-active' );
						return;
					}

					var data = {
						action: 'adsense_confirm_code',
						code: code,
						nonce: AdsenseGAPI.nonce,
					};

					$.ajax(
						{
							url: ajaxurl,
							type: 'post',
							data: data,
							success: function( response, status, XHR ) {
								spinner.removeClass( 'is-active' );
								if ( null !== AUTH_WINDOW ) {
									AUTH_WINDOW.close();
								}
								$( '#mapi-code' ).val( '' );
								if ( response.status && true === response.status ) {
									location.reload();
								} else {
									alert( response.body );
								}
							},
							error: function( request, status, error ) {
								spinner.removeClass( 'is-active' );
								alert( error );
							},
						},
					);
				},
			);

			$( document ).on( 'click', '.wpadcenter_copy_text',
				function( e ) {
					e.preventDefault();
					var data = $( this ).attr( 'data-attr' );
					document.addEventListener(
						'copy',
						function( e ) {
							e.clipboardData.setData( 'text/plain', data );
							e.preventDefault();
						},
						true,
					);

					document.execCommand( 'copy' );
					if ( data.includes( 'wpadcenter_display' ) ) {
						alert( 'Template tag copied to clipboard!' );
					} else {
						alert( 'Shortcode copied to clipboard!' );
					}
				},
			);

			$( function() {
				$( '#wpadcenter_scripts' ).addClass( 'closed' );
			} );
		},
	);
}( window.jQuery ) );

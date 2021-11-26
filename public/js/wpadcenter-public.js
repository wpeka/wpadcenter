/**
 * Javascript file for public section.
 *
 * @since 1.0.0
 *
 * @package
 */

( function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	/*global jQuery, ajax_url*/
	$( document ).ready( function() {
		//track clicks on ads rendered without iframe.
		$( document ).on(	'click', '#wpadcenter_ad', function() {
			var boundAdClick = onAdClick.bind( this );
			boundAdClick();
		} );

		var onAdClick = function() {
			var ad_id = $( this ).data( 'value' );
			var placement_id = $( this ).data( 'placement' );
			var request = {
				action: 'set_clicks',
				ad_id: ad_id,
				placement_id: placement_id,
				security: ajax_url.security,
				async: false,
			};
			$.ajax( {
				url: ajax_url.url,
				dataType: 'json',
				type: 'POST',
				data: request,
				async: false,
				success: function( response ) {
					if ( /(^|;)\s*wpadcenter_hide_ads=/.test( document.cookie ) ) {
						$( '.wpadcenter-ad-container' ).hide();
						$( '.wpadcenter-adgroup' ).hide();
					}
				},
				error: function() {

				},
			} );
		};

		//track clicks on ads rendered within iframe.
		setTimeout( function() {
			window.addEventListener( 'blur', function() {
				window.setTimeout( function() {
					if ( document.activeElement instanceof HTMLIFrameElement ) {
						var ad = document.activeElement.closest( '#wpadcenter_ad' );
						if ( ad ) {
							var boundAdClick = onAdClick.bind( ad );
							boundAdClick();
							window.focus();
						}
					}
				}, 0 );
			} );
		}, 1000 );
	} );
}( jQuery ) );


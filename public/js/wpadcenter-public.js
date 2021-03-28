/**
 * Javascript file for public section.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */

(function( $ ) {
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

	$( document ).ready(
		function() {
			$( document ).on(
				'click',
				'#wpadcenter_ad',
				function() {
					var ad_id   = $( this ).data( 'value' );
					var request = {
						action: "set_clicks",
						ad_id: ad_id,
						security: ajax_url.security,
					};
					$.post( ajax_url.url, request ).always(
						function(response){
							response = JSON.parse( response );
						}
					);
				}
			);
		}
	);
})( jQuery );

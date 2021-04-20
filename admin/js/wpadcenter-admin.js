/**
 * Javascript file for admin section.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */

(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
		function(){
			$( '#geo_countries select.geo_countries' ).select2();
			$( 'input[name="target-ads-by"]' ).click(
				function() {
					var target_ads_by = $( this ).attr( 'value' );
					if (target_ads_by == 'countries') {
						$( '#geo_cities' ).hide();
						$( '#geo_countries' ).show();
					} else if (target_ads_by == 'cities') {
						$( '#geo_countries' ).hide();
						$( '#geo_cities' ).show();
					}
				}
			);
			if ($( '#limit-ad-impressions-set' ).prop( 'checked' )) {
				$( '#impressions_number' ).show();
			} else {
				$( '#impressions_number' ).hide();
			}

			if ($( '#limit-ad-clicks-set' ).prop( 'checked' )) {
				$( '#clicks_number' ).show();
			} else {
				$( '#clicks_number' ).hide();
			}

			$( '#limit-ad-impressions-set' ).change(
				function() {
					if ($( '#limit-ad-impressions-set' ).prop( 'checked' )) {
						$( '#impressions_number' ).show();
					} else {
						$( '#impressions_number' ).hide();
					}
				}
			);

			$( '#limit-ad-clicks-set' ).change(
				function() {
					if ($( '#limit-ad-clicks-set' ).prop( 'checked' )) {
						$( '#clicks_number' ).show();
					} else {
						$( '#clicks_number' ).hide();
					}
				}
			);

			$( ".make_radio" ).click(
				function(){
					$( ".make_radio" ).not( this ).prop( "checked",false ).trigger( 'change' );
				}
			);

			if ( 'undefined' !== typeof wpadcenter_render_metaboxes ) {

				var metaboxes = [];
				var ad_types  = {};

				var ad_meta_relation = wpadcenter_render_metaboxes[0];

				var current_ad_type = wpadcenter_render_metaboxes[1];

				for (var key in ad_meta_relation) {
					ad_types[key] = ad_meta_relation[key].active_meta_box;
					for (var k_meta in ad_meta_relation[key].active_meta_box) {
						metaboxes.push( ad_meta_relation[key].active_meta_box[k_meta] )
					}
				}

				metaboxes = _.uniq( metaboxes );

				change_active_metaboxes( current_ad_type );

				$( '#ad-type' ).change(
					function () {
						const selected_ad = $( "#ad-type :selected" ).val();
						change_active_metaboxes( selected_ad );

					}
				);

				function change_active_metaboxes(selected_ad){

					const active_metaboxes = ad_types[selected_ad];

					for (key in metaboxes) {
						if (active_metaboxes.includes( metaboxes[key] )) {

							// Show this metabox and return.
							$( '#' + metaboxes[key] ).show();

						} else {
							// Hide this metabox.
							$( '#' + metaboxes[key] ).hide();

						}
					}

				}

			}

		}
	);

})( jQuery );

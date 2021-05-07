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

				if( $( "#ad-type :selected" ).val() === 'amp_ad' ) {
					displayAmpWarning();
			   	}

				$( '#ad-type' ).change(
					function () {
						const selected_ad = $( "#ad-type :selected" ).val();
						change_active_metaboxes( selected_ad );
						displayAmpWarning();
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

		//  create AMP ad page functions
		var wrapper = $("#wpadcenter-amp-attributes-container");
		var add_button = $("#wpadcenter-amp-add-attr-button");
   
		$(add_button).click(function(e) {
			e.preventDefault();
   
				$(wrapper).append(`<div><label>Attribute : </label><input  name="amp-attributes[]" /> =
				<label>Value : </label><input  name="amp-values[]" />
				<button class="wpadcenter-amp-delete-attr-button">Remove</button><br><br></div>`);
   
		});
   
		$(wrapper).on("click", ".wpadcenter-amp-delete-attr-button", function(e) {
			e.preventDefault();
			$(this).parent('div').remove();
		});
   
		// adsense amp preference functions
		if ($('#ampPreference').prop("checked")) {
			$('.wpadcenterAmpCustomizeSettings').show();
		}
		else{
			$('.wpadcenterAmpCustomizeSettings').hide();
   
		}
   
		$('#ampPreference').change(function(){
   
			if ($(this).prop("checked")) {
   
						convertAdsenseToAmp();
						$('.wpadcenterAmpCustomizeSettings').show();
   
			}
			else{
				$('#wpadcenterAdsenseAmpCode').text('');
				$('.wpadcenterAmpCustomizeSettings').hide();
   
			}
		});
   
		$('.wpadcenterAmpCustomize').change(function(){
			convertAdsenseToAmp();
		});
		$('#wpadcenter-google-adsense-code').change(function(){
			convertAdsenseToAmp();
   
		});
   
   
		function convertAdsenseToAmp(){
			let rawCode=$('#wpadcenter-google-adsense-code').val();
			if(rawCode){
				   rawCode = $('<div />').html(rawCode);
						let rawCode_html = rawCode.find( 'ins' );
						let clientId = rawCode_html.attr( 'data-ad-client' );
						if(!clientId){
						   $('#wpadcenterAdsenseAmpCode').text("Please provide Client ID");
						   return;
					   }
						let slotId = rawCode_html.attr( 'data-ad-slot' );
						if(!slotId){
							$('#wpadcenterAdsenseAmpCode').text("Please provide slot ID");
							return;
						}
   
			let ampAdCode=`
						<amp-ad
						type="adsense"
						data-ad-client="${clientId}"
						data-ad-slot="${slotId}"`;
   
						   if ($('#wpadcenterAmpCustomizeAuto').prop("checked")) {
   
						ampAdCode += `
						layout="fixed"
						   width="300"
						   height="250" `;
						   }
   
						   if ($('#wpadcenterAmpCustomizeDynamic').prop("checked")) {
							   let dynamicWidth= $('#wpadcenterAmpCustomizeDynamicWidth').val();
							   let dynamicHeight=$('#wpadcenterAmpCustomizeDynamicHeight').val();
   
							   ampAdCode += `
							   layout="responsive"
							   width="${dynamicWidth}vw"
							   height="${dynamicHeight}vw" `;
						   }
   
						   if ($('#wpadcenterAmpCustomizeStatic').prop("checked")) {
							   let staticHeight =$('#wpadcenterAmpCustomizeStaticHeight').val();
							   ampAdCode += `
							   layout="fixed-height"
							   width="auto"
							   height="${staticHeight}" `;
						   }
   
						   ampAdCode += `>`;
   
   
   
						ampAdCode += `</amp-ad>`;
						$('#wpadcenterAdsenseAmpCode').text(ampAdCode);
   
				}
		   }


		   function displayAmpWarning() {
			$('#wpadcenter_amp_warning').remove();
			const selected_ad = $( "#ad-type :selected" ).val();
			if ( selected_ad === 'amp_ad'){
			var j = jQuery.noConflict();
			j.ajax({
				  type:"POST",
				  url: "./admin-ajax.php",
				  data: {
				 action:'wpadcenter_pro_display_amp_warning'    
			  }
			  }).done(success => {
				jQuery('.wp-header-end').after(success);  });
			}
		}

		}
	);

})( jQuery );

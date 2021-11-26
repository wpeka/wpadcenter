/**
 * Javascript file for admin section.
 *
 * @since 1.0.0
 *
 * @package
 */

( function( $ ) {
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

	/*global jQuery, ajaxurl, AdsenseGAPI, wpadcenter_render_metaboxes, _, tinymce, wp*/

	$( document ).ready(
		function() {
			//google adsense ad selection
			$( '#adsense-adunits button' ).click(
				function( e ) {
					e.preventDefault();
					var button = $( e.target );

					$.ajax(
						{
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'adsense_load_adcode',
								_wpnonce: AdsenseGAPI.nonce,
								adunit: button.attr( 'data-unitid' ),
							},
							success: function( data ) {
								$( '#adsense-adunits button' ).text( 'Load' );
								button.text( 'Loaded' );
								$( '#wpadcenter-google-adsense-code' ).text( data.message );
								$( '#wpadcenter-google-adsense-code' ).val( data.message );

								convertAdsenseToAmp();
							},
							error: function( request, status, error ) {
								alert( error );
							},

						},
					);
				},
			);
			$( '.wpadcenter-additional-tags-select' ).select2();

			$( '#geo_countries select.geo_countries' ).select2();
			$( 'input[name="target-ads-by"]' ).click(
				function() {
					var target_ads_by = $( this ).attr( 'value' );
					if ( target_ads_by == 'countries' ) {
						$( '#geo_cities' ).hide();
						$( '#geo_countries' ).show();
					} else if ( target_ads_by == 'cities' ) {
						$( '#geo_countries' ).hide();
						$( '#geo_cities' ).show();
					}
				},
			);
			if ( $( '#limit-ad-impressions-set' ).prop( 'checked' ) ) {
				$( '#impressions_number' ).show();
			} else {
				$( '#impressions_number' ).hide();
			}

			if ( $( '#limit-ad-clicks-set' ).prop( 'checked' ) ) {
				$( '#clicks_number' ).show();
			} else {
				$( '#clicks_number' ).hide();
			}

			$( '#limit-ad-impressions-set' ).change(
				function() {
					if ( $( '#limit-ad-impressions-set' ).prop( 'checked' ) ) {
						$( '#impressions_number' ).show();
					} else {
						$( '#impressions_number' ).hide();
					}
				},
			);

			$( '#limit-ad-clicks-set' ).change(
				function() {
					if ( $( '#limit-ad-clicks-set' ).prop( 'checked' ) ) {
						$( '#clicks_number' ).show();
					} else {
						$( '#clicks_number' ).hide();
					}
				},
			);

			$( '.make_radio' ).click(
				function() {
					$( '.make_radio' ).not( this ).prop( 'checked', false ).trigger( 'change' );
				},
			);

			if ( 'undefined' !== typeof wpadcenter_render_metaboxes ) {
				var metaboxes = [];
				var ad_types = {};

				var ad_meta_relation = wpadcenter_render_metaboxes[0];

				var current_ad_type = wpadcenter_render_metaboxes[1];

				for ( var key in ad_meta_relation ) {
					ad_types[key] = ad_meta_relation[key].active_meta_box;
					for ( var k_meta in ad_meta_relation[key].active_meta_box ) {
						metaboxes.push( ad_meta_relation[key].active_meta_box[k_meta] );
					}
				}

				metaboxes = _.uniq( metaboxes );

				change_active_metaboxes( current_ad_type );

				if ( $( '#ad-type :selected' ).val() === 'amp_ad' ) {
					displayAmpWarning();
				}

				$( '#ad-type' ).change(
					function() {
						const selected_ad = $( '#ad-type :selected' ).val();
						change_active_metaboxes( selected_ad );
						displayAmpWarning();
					},
				);

				function change_active_metaboxes( selected_ad ) {
					const active_metaboxes = ad_types[selected_ad];

					for ( key in metaboxes ) {
						if ( active_metaboxes.includes( metaboxes[key] ) ) {
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
			var wrapper = $( '#wpadcenter-amp-attributes-container' );
			var add_button = $( '#wpadcenter-amp-add-attr-button' );

			$( add_button ).click( function( e ) {
				e.preventDefault();
				var ampInputFields = '<div><label>Attribute : </label><input  name="amp-attributes[]" />' + ' = ' + '<label>Value : </label><input  name="amp-values[]" />' + ' <button class="wpadcenter-amp-delete-attr-button">Remove</button><br><br></div>';

				$( wrapper ).append( ampInputFields );
			} );

			$( wrapper ).on( 'click', '.wpadcenter-amp-delete-attr-button', function( e ) {
				e.preventDefault();
				$( this ).parent( 'div' ).remove();
			} );

			// adsense amp preference functions
			if ( $( '#ampPreference' ).prop( 'checked' ) ) {
				$( '.wpadcenterAmpCustomizeSettings' ).show();
			} else {
				$( '.wpadcenterAmpCustomizeSettings' ).hide();
			}

			$( '#ampPreference' ).change( function() {
				if ( $( this ).prop( 'checked' ) ) {
					convertAdsenseToAmp();
					$( '.wpadcenterAmpCustomizeSettings' ).show();
				} else {
					$( '#wpadcenterAdsenseAmpCode' ).text( '' );
					$( '.wpadcenterAmpCustomizeSettings' ).hide();
				}
			} );

			$( '.wpadcenterAmpCustomize' ).change( function() {
				convertAdsenseToAmp();
			} );
			$( '#wpadcenter-google-adsense-code' ).change( function() {
				convertAdsenseToAmp();
			} );

			function convertAdsenseToAmp() {
				var rawCode = $( '#wpadcenter-google-adsense-code' ).val();
				if ( rawCode ) {
					rawCode = $( '<div />' ).html( rawCode );

					//Extract attributes from the google adsense loaded code
					var rawCode_html = rawCode.find( 'ins' );
					var clientId = rawCode_html.attr( 'data-ad-client' );
					if ( ! clientId ) {
						$( '#wpadcenterAdsenseAmpCode' ).text( 'Please provide Client ID' );
						$( '#wpadcenterAdsenseAmpCode' ).val( 'Please provide Client ID' );
						return;
					}
					var slotId = rawCode_html.attr( 'data-ad-slot' );
					if ( ! slotId ) {
						$( '#wpadcenterAdsenseAmpCode' ).text( 'Please provide slot ID' );
						$( '#wpadcenterAdsenseAmpCode' ).val( 'Please provide slot ID' );
						return;
					}
					var adType = '';
					var width = '';
					var height = '';
					var format = rawCode_html.attr( 'data-ad-format' );
					var style = rawCode_html.attr( 'style' ) || '';

					//Check for the ad types
					if ( 'undefined' == typeof ( format ) && -1 != style.indexOf( 'width' ) ) {
						adType = 'normal';
						width = rawCode_html.css( 'width' ).replace( 'px', '' );
						height = rawCode_html.css( 'height' ).replace( 'px', '' );
					} else if ( 'undefined' != typeof ( format ) && 'auto' == format ) {
						adType = 'responsive';
					} else if ( 'undefined' != typeof ( format ) && 'link' == format ) {
						if ( -1 != style.indexOf( 'width' ) ) { //fixed size
							width = rawCode_html.css( 'width' ).replace( 'px', '' );
							height = rawCode_html.css( 'height' ).replace( 'px', '' );
							adType = 'link';
						} else { //responsive size
							adType = 'link-responsive';
						}
					} else if ( 'undefined' != typeof ( format ) && 'autorelaxed' == format ) {
						adType = 'matched-content';
					} else if ( 'undefined' != typeof ( format ) && 'fluid' == format ) {
						adType = 'in-article';
					}

					//Converts into Amp code
					var ampAdCode = '<amp-ad type="adsense" data-ad-client="' + clientId + '" data-ad-slot="' + slotId + '" ';

					if ( $( '#wpadcenterAmpCustomizeAuto' ).prop( 'checked' ) ) {
						switch ( adType ) {
							case 'normal':
							case 'link':
								if ( width > 0 && height > 0 ) {
									ampAdCode += 'layout="fixed" width="' + width + '" height="' + height + '" ';
								}
								break;

							case 'link-responsive':
								ampAdCode += ' width="auto" height="90" layout="fixed-height" ';
								break;

							case 'responsive':

								ampAdCode += 'width="100vw" height="320" data-auto-format="rspv" data-full-width><div overflow></div ';

								break;

							case 'in-article':
								ampAdCode += ' width="auto" height="320" layout="fixed-height" ';
								break;

							case 'matched-content':

								ampAdCode += ' width="auto" height="320" layout="fixed-height" ';

								break;
						}
					}

					if ( $( '#wpadcenterAmpCustomizeDynamic' ).prop( 'checked' ) ) {
						var dynamicWidth = $( '#wpadcenterAmpCustomizeDynamicWidth' ).val();
						var dynamicHeight = $( '#wpadcenterAmpCustomizeDynamicHeight' ).val();

						ampAdCode += 'layout="responsive" width="' + dynamicWidth + 'vw" height="' + dynamicHeight + 'vw" ';
					}

					if ( $( '#wpadcenterAmpCustomizeStatic' ).prop( 'checked' ) ) {
						var staticHeight = $( '#wpadcenterAmpCustomizeStaticHeight' ).val();

						ampAdCode += 'layout="fixed-height" width="auto" height="' + staticHeight + '" ';
					}

					ampAdCode += '>';

					ampAdCode += '</amp-ad>';

					$( '#wpadcenterAdsenseAmpCode' ).text( ampAdCode );
					$( '#wpadcenterAdsenseAmpCode' ).val( ampAdCode );
				} else {
					$( '#wpadcenterAdsenseAmpCode' ).text( '' );
					$( '#wpadcenterAdsenseAmpCode' ).val( '' );
				}
			}

			function displayAmpWarning() {
				$( '#wpadcenter_amp_warning' ).remove();
				var selected_ad = $( '#ad-type :selected' ).val();
				if ( selected_ad === 'amp_ad' ) {
					var j = jQuery.noConflict();
					j.ajax( {
						type: 'POST',
						url: './admin-ajax.php',
						data: {
							action: 'wpadcenter_pro_display_amp_warning',
						},
					} ).done( function( success ) {
						jQuery( '.wp-header-end' ).after( success );
					} );
				}
			}

			/* Text ad functions*/

			//Initial frame load of tiny mce
			$( '#text-ad' ).on( 'DOMNodeInserted', function( event ) {
				if ( event.target.className === 'mce-path-item' ) {
					applyTextAdStyles();
				}
			} );
			//frame reload on change in parameters
			$( '#wpadcenter_text_ad_bg_color,#wpadcenter_text_ad_border_color,#wpadcenter_text_ad_border_width' ).change( function() {
				applyTextAdStyles();
			} );

			//Adds classes to track clicks on links
			function addTextAdClasses( editor ) {
				var content = '';
				if ( editor === 'visual' ) {
					content = tinymce.get( 'text_ad_code' ).getContent();
				} else if ( editor === 'text' ) {
					content = $( '#text-ad .wp-editor-area' ).val();
				}
				var adId = $( '#wpadcenter_get_text_ad_id' ).data( 'value' );

				var htmlObject = document.createElement( 'div' );

				htmlObject.innerHTML = content;
				var x = htmlObject.getElementsByTagName( 'a' );
				for ( var i = 0; i < x.length; i++ ) {
					if ( x[i].getAttribute( 'href' ) ) {
						x[i].setAttribute( 'id', 'wpadcenter_ad' );
						x[i].setAttribute( 'data-value', adId );
					}
				}
				var tinyString = htmlObject.innerHTML;
				if ( editor === 'visual' ) {
					tinymce.get( 'text_ad_code' ).setContent( tinyString );
				} else if ( editor === 'text' ) {
					$( '#text-ad .wp-editor-area' ).val( tinyString );
				}
			}
			//triggers function to add click tracking classes on changes in text editor
			$( '#text-ad .wp-editor-area' ).focusout( function() {
				addTextAdClasses( 'text' );
			} );

			//reloads tinymce editor with changes in parameters
			function applyTextAdStyles() {
				window.requestAnimationFrame( function() {
					var textBackgroundColor = $( '#wpadcenter_text_ad_bg_color' ).val();
					var textBorderColor = $( '#wpadcenter_text_ad_border_color' ).val();
					var textBorderWidth = $( '#wpadcenter_text_ad_border_width' ).val();

					var textAdContainerId = window.tinyMCE.get( 'text_ad_code' ).contentAreaContainer.id;

					var textAdBody = document.querySelector( '#' + textAdContainerId + ' iframe' ).contentDocument.body;

					$( '#' + textAdContainerId + ' iframe' ).css( {
						border: textBorderWidth + 'px solid ' + textBorderColor,
						boxSizing: 'border-box',
					} );

					var textAdDoc = document.querySelector( '#' + textAdContainerId + ' iframe' ).contentDocument;
					var tinyMceEditor = textAdDoc.querySelector( "[contenteditable='true']" );

					tinyMceEditor.onblur = function() {
						addTextAdClasses( 'visual' );
					};

					textAdBody.style.background = textBackgroundColor;

					textAdBody.style.maxWidth = 'none';
					textAdBody.style.margin = '0';
				} );
			}

			// link options functions
			function additionalRelTagSetup() {
				if ( $( '#globalAdditionalRelTagsPreference' ).prop( 'checked' ) ) {
					$( '.wpadcenter-additional-rel-tag-container' ).hide();
				} else {
					$( '.wpadcenter-additional-rel-tag-container' ).show();
				}
			}
			function additionalCssClassSetup() {
				if ( $( '#globalAdditionalCssClassPreference' ).prop( 'checked' ) ) {
					$( '.wpadcenter-additional-css-class-container' ).hide();
				} else {
					$( '.wpadcenter-additional-css-class-container' ).show();
				}
			}
		//check on page load
		additionalRelTagSetup();
		additionalCssClassSetup();
		//check on change in selection
		$( '#globalAdditionalRelTagsPreference' ).change( additionalRelTagSetup );
		$( '#globalAdditionalCssClassPreference' ).change( additionalCssClassSetup );

		//html5 ad upload
		$( '#wpadcenter-html5-select' ).change( function() {
		$( '#wpadcenter-html5-select' ).prop( 'disabled', true );
		$( '#wpdcenter-html5-upload' ).prop( 'disabled', false );
		$( '.wpadcenter-active-filename, .wpadcenter-delete-icon-container' ).css( 'display', 'flex' );
		$( '#wpadcenter_html5_filename' ).text( $( '#wpadcenter-html5-select' ).val().replace( /.*[\/\\]/, '' ) );
		$( '#wpadcenter-html5-db-filename' ).val( $( '#wpadcenter-html5-select' ).val().replace( /.*[\/\\]/, '' ) );
		} );
		if ( $( '#wpadcenter-html5-db-filename' ).val() == '' ) {
			$( '.wpadcenter-delete-icon-container' ).css( 'display', 'none' );
		} else {
			$( '#wpadcenter-html5-select' ).prop( 'disabled', true );
			$( '#wpdcenter-html5-upload' ).text( 'Uploaded' );
		}
		$( '#wpadcenter-file-delete' ).click( function( e ) {
			$( '#wpdcenter-html5-upload' ).prop( 'disabled', true );
			$( '#wpdcenter-html5-upload' ).text( 'Upload Now' );
			$( '#wpadcenter_html5_ad_url, #wpadcenter-html5-select, #wpadcenter-html5-db-filename' ).val( '' );
			$( '#wpadcenter_html5_filename' ).text( '' );
			$( '.wpadcenter-active-filename, .wpadcenter-delete-icon-container' ).css( 'display', 'none' );
			$( '#wpadcenter-html5-select' ).prop( 'disabled', false );
			$( '#wpadcenter-html5-upload-error' ).css( 'display', 'none' );
			$( '#wpadcenter-html5-upload-error' ).text( '' );
		} );
		$( '#wpdcenter-html5-upload' ).click( function( e ) {
			e.preventDefault();
			$( this ).text( 'Uploading..' );
			const adID = $( this ).data( 'ad_id' );
			var html5Nonce = wpadcenter_render_metaboxes[2];
			const formData = new FormData();
			formData.append( 'action', 'upload_html5_file' );
			formData.append( 'nonce_security', html5Nonce );
			formData.append( 'ad_id', adID );

			var uploaded_files = document.getElementById( 'wpadcenter-html5-select' ).files;

			$.each( uploaded_files, function( key, file ) {
				formData.append( 'html5_uploaded_file', file );
			} );

			$.ajax(
				{
					url: ajaxurl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function( data ) {
						if ( data.success ) {
							$( '#wpadcenter_html5_ad_url' ).val( data.data.ad_url + 'index.html' );
							$( '#wpdcenter-html5-upload' ).text( 'Uploaded' );
							$( '#wpdcenter-html5-upload' ).prop( 'disabled', true );
							$( '#wpadcenter-html5-upload-error' ).css( 'display', 'none' );
							$( '#wpadcenter-html5-upload-error' ).text( '' );
						} else {
							$( '#wpadcenter-html5-upload-error' ).text( data.data );
							$( '#wpadcenter-html5-upload-error' ).css( 'display', 'block' );
							$( '#wpdcenter-html5-upload' ).text( 'Failed, try again' );
						}
					},
					error: function( request, status, error ) {
						$( '#wpdcenter-html5-upload' ).text( 'Failed, try again' );
					},

				},
			);
		} );
		$( '#wpadcenter_upload_video' ).click( function( e ) {
			e.preventDefault();
			wpadcenter_video_upload( $( this ) );
		} );
		function wpadcenter_video_upload( $elem ) {
			var file_frame, attachment;

			// If an instance of file_frame already exists, then we can open it rather than creating a new instance
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Use the wp.media library to define the settings of the media uploader
			file_frame = wp.media.frames.file_frame = wp.media( {
				frame: 'post',
				state: 'insert',
				multiple: false,
			} );

			// Setup an event handler for what to do when a media has been selected
			file_frame.on( 'insert', function() {
				// Read the JSON data returned from the media uploader
				attachment = file_frame.state().get( 'selection' ).first().toJSON();
				// First, make sure that we have the URL of the media to display
				if ( 0 > $.trim( attachment.url.length ) ) {
					return;
				}
				$( '#wpadcenter_video_ad_url' ).val( attachment.url );
				$( '#wpadcenter_video_ad_filename' ).val( attachment.filename );
				$( '#wpadcenter_video_filename' ).text( attachment.filename );
				$( '#wpadcenter_video_filename_container' ).css( 'display', 'block' );
			} );
			// Now display the actual file_frame
			file_frame.open();
		}

		$( '#wpadcenter_video_autoplay' ).change( function( e ) {
			e.preventDefault();
			$( '#wpadcenter_video_autoplay' ).prop( 'checked' ) ? $( '#wpadcenter_video_autoplay' ).val( true ) : $( '#wpadcenter_video_autoplay' ).val( false );
		} );

		$( '#wpadcenter_video_filename_close' ).click( function() {
			$( '#wpadcenter_video_filename_container' ).css( 'display', 'none' );
			$( '#wpadcenter_video_ad_url' ).val( '' );
		} );
		},
	);
}( jQuery ) );

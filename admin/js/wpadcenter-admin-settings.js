/**
 * Admin Settings Javascript
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
	/*global jQuery, wpadcenter_settings_success_message, wpadcenter_settings_error_message*/

	$( document ).ready(
		function() {
			var wpadcenter_nav_tab = $( '.wpadcenter-tab-head .nav-tab' );
			if ( wpadcenter_nav_tab.length > 0 ) {
				wpadcenter_nav_tab.click(
					function() {
						var wpadcenter_tab_hash = $( this ).attr( 'href' );
						wpadcenter_nav_tab.removeClass( 'nav-tab-active' );
						$( this ).addClass( 'nav-tab-active' );
						wpadcenter_tab_hash = wpadcenter_tab_hash.charAt( 0 ) == '#' ? wpadcenter_tab_hash.substring( 1 ) : wpadcenter_tab_hash;
						if ( wpadcenter_tab_hash == 'wpadcenter-ads-txt' ) {
							$( 'input[name="ads_txt_tab"]' ).val( '1' );
						} else {
							$( 'input[name="ads_txt_tab"]' ).val( '0' );
						}
						if ( wpadcenter_tab_hash == 'wpadcenter-advanced' ) {
							$( 'input[name="geo_targeting_tab"]' ).val( '1' );
						} else {
							$( 'input[name="geo_targeting_tab"]' ).val( '0' );
						}
						var wpadcenter_tab_elm = $( 'div[data-id="' + wpadcenter_tab_hash + '"]' );
						$( '.wpadcenter-tab-content' ).hide();
						if ( wpadcenter_tab_elm.length > 0 ) {
							wpadcenter_tab_elm.fadeIn();
						}
						var ads_txt_tab = $( 'input[name="ads_txt_tab"]' ).val();
						if ( ads_txt_tab == '1' ) {
							$( 'input[name="check_ads_txt_problems"]' ).trigger( 'click' );
						}
						var geo_targeting_tab = $( 'input[name="geo_targeting_tab"]' ).val();
						if ( geo_targeting_tab == '1' ) {
							$( 'input[name="check_maxmind_license_key"]' ).trigger( 'click' );
						}
					},
				);
				var location_hash = window.location.hash;
				if ( location_hash != '' ) {
					var wpadcenter_tab_hash = location_hash.charAt( 0 ) == '#' ? location_hash.substring( 1 ) : location_hash;
					if ( wpadcenter_tab_hash != '' ) {
						$( 'div[data-id="' + wpadcenter_tab_hash + '"]' ).show();
						$( 'a[href="#' + wpadcenter_tab_hash + '"]' ).addClass( 'nav-tab-active' );
						if ( wpadcenter_tab_hash == 'wpadcenter-ads-txt' ) {
							$( 'input[name="ads_txt_tab"]' ).val( '1' );
						} else {
							$( 'input[name="ads_txt_tab"]' ).val( '0' );
						}
						if ( wpadcenter_tab_hash == 'wpadcenter-advanced' ) {
							$( 'input[name="geo_targeting_tab"]' ).val( '1' );
						} else {
							$( 'input[name="geo_targeting_tab"]' ).val( '0' );
						}
					}
				} else {
					wpadcenter_nav_tab.eq( 0 ).click();
				}
			}
			$( document ).on(
				'click',
				'input[name="replace_ads_txt_file"]',
				function( e ) {
					e.preventDefault();
					var ads_txt_tab = $( 'input[name="ads_txt_tab"]' ).val();
					if ( ads_txt_tab == '1' ) {
						var message = $( '.ads_txt_problems' );
						var spinner = $( '.ads_txt_spinner' );
						var btn = $( this );
						var ajax_url = $( 'input[name="ads_txt_ajaxurl"]' ).val();
						var security = $( 'input[name="ads_txt_replace_security"]' ).val();
						message.html( '' );
						spinner.show();
						spinner.css( { visibility: 'visible' } );
						btn.css( { opacity: '.5', cursor: 'default' } ).prop( 'disabled', true );
						var data = {
							action: 'check_ads_txt_replace',
							security: security,
						};
						$.ajax(
							{
								url: ajax_url,
								data: data,
								dataType: 'json',
								type: 'POST',
								success: function( data ) {
									btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									var check_message = '';
									if ( data.response === true ) {
										if ( data.file_available ) {
											check_message = data.file_available;
										}
										if ( data.file_imported ) {
											check_message += data.file_imported;
											if ( data.file_content ) {
												$( '#ads_txt_content_field' ).val( data.file_content );
											}
										}
										message.html( check_message );
									} else {
										if ( data.file_available ) {
											check_message += data.file_available;
										}
										if ( data.replace_error_message ) {
											check_message += data.replace_error_message;
										}
										message.html( check_message );
									}
								},
								error: function() {
									btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									if ( data.error_message ) {
										message.html( data.error_message );
									}
								},
							},
						);
					}
				},
			);

			$( 'input[name="check_maxmind_license_key"]' ).on(
				'click',
				function( e ) {
					e.preventDefault();
					var geo_targeting_tab = $( 'input[name="geo_targeting_tab"]' ).val();
					if ( geo_targeting_tab == '1' ) {
						var message = $( '.maxmind_key_errors' );
						var spinner = $( '.geo_targeting_spinner' );
						var license_key = $( 'input[name="maxmind_license_key_field"]' ).val();
						var ajax_url = $( 'input[name="geo_targeting_ajaxurl"]' ).val();
						var security = $( 'input[name="geo_targeting_security"]' ).val();
						message.html( '' );
						spinner.show();
						spinner.css( { visibility: 'visible' } );

						var data = {
							action: 'check_maxmind_license_key',
							security: security,
							license_key: license_key,
						};
						$.ajax(
							{
								url: ajax_url,
								data: data,
								dataType: 'json',
								type: 'POST',
								success: function( data ) {
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									var check_message = '';
									if ( data.response == false ) {
										check_message = data.error_message;
										message.html( check_message );
									}
								},
								error: function() {
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									if ( data.error_message ) {
										message.html( data.error_message );
									}
								},
							},
						);
					}
				},
			);

			$( 'input[name="check_ads_txt_problems"]' ).on(
				'click',
				function( e ) {
					e.preventDefault();
					var ads_txt_tab = $( 'input[name="ads_txt_tab"]' ).val();
					if ( ads_txt_tab == '1' ) {
						var message = $( '.ads_txt_problems' );
						var spinner = $( '.ads_txt_spinner' );
						var btn = $( this );
						var ajax_url = $( 'input[name="ads_txt_ajaxurl"]' ).val();
						var security = $( 'input[name="ads_txt_security"]' ).val();
						message.html( '' );
						spinner.show();
						spinner.css( { visibility: 'visible' } );
						btn.css( { opacity: '.5', cursor: 'default' } ).prop( 'disabled', true );
						var data = {
							action: 'check_ads_txt_problems',
							security: security,
						};
						$.ajax(
							{
								url: ajax_url,
								data: data,
								dataType: 'json',
								type: 'POST',
								success: function( data ) {
									btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									var check_message = '';
									if ( data.response === true ) {
										if ( data.file_available ) {
											check_message = data.file_available;
										}
										if ( data.is_third_party ) {
											check_message += data.is_third_party;
										}
										message.html( check_message );
									} else {
										if ( data.error_message ) {
											check_message = data.error_message;
										}
										if ( data.domain_error_message ) {
											check_message += data.domain_error_message;
										}
										message.html( check_message );
									}
								},
								error: function() {
									btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );
									spinner.css( { visibility: 'hidden' } );
									spinner.hide();
									if ( data.error_message ) {
										message.html( data.error_message );
									}
								},
							},
						);
					}
				},
			);
			$( '#wpadcenter_settings_form' ).submit(
				function( e ) {
					var submit_action = $( '#wpadcenter_update_action' ).val();
					e.preventDefault();
					var data = $( this ).serialize();
					var url = $( this ).attr( 'action' );
					var submit_btn = $( this ).find( 'input[type="submit"]' );
					var wpadcenter_save = $( this ).find( '.wpadcenter_save' );
					wpadcenter_save.addClass( 'button--loading' );
					submit_btn.css( { opacity: '.5', cursor: 'default' } ).prop( 'disabled', true );
					$.ajax(
						{
							url: url,
							type: 'POST',
							data: data + '&wpadcenter_settings_ajax_update=' + submit_action, /*eslint-disable no-unused-vars*/
							success: function( data ) {
								wpadcenter_save.removeClass( 'button--loading' );
								submit_btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );
								wpadcenter_notify_msg.success( wpadcenter_settings_success_message );
								var ads_txt_tab = $( 'input[name="ads_txt_tab"]' ).val();
								if ( ads_txt_tab == '1' ) {
									$( 'input[name="check_ads_txt_problems"]' ).trigger( 'click' );
								}
								var geo_targeting_tab = $( 'input[name="geo_targeting_tab"]' ).val();
								if ( geo_targeting_tab == '1' ) {
									$( 'input[name="check_maxmind_license_key"]' ).trigger( 'click' );
								}
							},
							error: function() {
								wpadcenter_save.removeClass( 'button--loading' );
								submit_btn.css( { opacity: '1', cursor: 'pointer' } ).prop( 'disabled', false );

								wpadcenter_notify_msg.error( wpadcenter_settings_error_message );
							},
						},
					);
				},
			);

			var wpadcenter_form_toggler =
				{
					set: function() {
						$( 'select.wpadcenter_form_toggle' ).each(
							function() {
								wpadcenter_form_toggler.toggle( $( this ) );
							},
						);
						$( 'input[type="radio"].wpadcenter_form_toggle' ).each(
							function() {
								if ( $( this ).is( ':checked' ) ) {
									wpadcenter_form_toggler.toggle( $( this ) );
								}
							},
						);
						$( 'select.wpadcenter_form_toggle' ).change(
							function() {
								wpadcenter_form_toggler.toggle( $( this ) );
							},
						);
						$( 'input[type="radio"].wpadcenter_form_toggle' ).click(
							function() {
								if ( $( this ).is( ':checked' ) ) {
									wpadcenter_form_toggler.toggle( $( this ) );
								}
							},
						);
					},
					toggle: function( elm ) {
						var vl = elm.val();
						var trgt = elm.attr( 'wpadcenter_frm_tgl-target' );
						$( '[wpadcenter_frm_tgl-id="' + trgt + '"]' ).hide();
						var selcted_trget = $( '[wpadcenter_frm_tgl-id="' + trgt + '"]' ).filter(
							function() {
								return $( this ).attr( 'wpadcenter_frm_tgl-val' ) == vl;
							},
						);
						selcted_trget.show();
					},
				};

			wpadcenter_form_toggler.set();

			$( document ).on(
				'change',
				'input[name="enable_advertisers_field"]',
				function() {
					if ( this.value == 'true' ) {
						$( '.wpadcenter-woo-notice' ).show();
					} else {
						$( '.wpadcenter-woo-notice' ).hide();
					}
				},
			);
			$( document ).on(
				'change',
				'input[name="enable_ads_txt_field"]',
				function() {
					if ( this.value == 'true' ) {
						$( 'input[name="ads_txt_tab"]' ).val( '1' );
						$( 'input[name="check_ads_txt_problems"]' ).trigger( 'click' );
					} else {
						$( 'input[name="ads_txt_tab"]' ).val( '0' );
					}
				},
			);
			$( document ).on(
				'change',
				'input[name="geo_targeting_field"]',
				function() {
					if ( this.value == 'true' ) {
						$( 'input[name="ads_txt_tab"]' ).val( '1' );
						$( 'input[name="check_maxmind_license_key"]' ).trigger( 'click' );
					} else {
						$( 'input[name="geo_targeting_tab"]' ).val( '0' );
					}
				},
			);
		},
	);
	$( document ).ready(
		function() {
			var ads_txt_tab = $( 'input[name="ads_txt_tab"]' ).val();
			if ( ads_txt_tab == '1' ) {
				$( document ).find( 'input[name="check_ads_txt_problems"]' ).trigger( 'click' );
			}
			var geo_targeting_tab = $( 'input[name="geo_targeting_tab"]' ).val();
			if ( geo_targeting_tab == '1' ) {
				$( document ).find( 'input[name="check_maxmind_license_key"]' ).trigger( 'click' );
			}
		},
	);
}( jQuery ) );
var wpadcenter_notify_msg = {
	error: function( message ) {
		var er_elm = jQuery( '<div class="notify_msg" style="background:#d9534f; border:solid 1px #dd431c;">' + message + '</div>' );
		this.setNotify( er_elm );
	},
	success: function( message ) {
		var succ_elm = jQuery( '<div class="notify_msg" style="background:#5cb85c; border:solid 1px #2bcc1c;">' + message + '</div>' );
		this.setNotify( succ_elm );
	},
	setNotify: function( elm ) {
		jQuery( 'body' ).append( elm );
		elm.stop( true, true ).animate( { opacity: 1, top: '50px' }, 1000 );
		setTimeout(
			function() {
				elm.animate(
					{ opacity: 0, top: '100px' },
					1000,
					function() {
						elm.remove();
					},
				);
			},
			3000,
		);
	},
};
function wpadcenter_settings_btn_click( vl ) { /*eslint-disable no-unused-vars*/
	document.getElementById( 'wpadcenter_update_action' ).value = vl;
}

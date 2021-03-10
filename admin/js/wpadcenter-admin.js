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
	

	
	$(document).ready(function(){
		
		
		
		if( undefined !== wpadcenter_ad_scheduler ){
			$(`#minor-publishing-actions`).hide();
			$(`#misc-publishing-actions`).hide();

			$(`#edit-ad-schedule`).click(function(){
				$(`#ad-schedule-show`).show('slow');
				$(`#edit-ad-schedule`).hide();
			});

	


			setDatePicker('start');
			setDatePicker('end');
			

			function setDatePicker(type){

				const timestamp = $( `#${ type }_date` ).val();

				let date;

				if ( wpadcenter_ad_scheduler.timezone_string ) {
					date = moment( timestamp * 1000 ).tz( wpadcenter_ad_scheduler.timezone_string );
				} else {
					
					const offset = Number.parseInt( wpadcenter_ad_scheduler.gmt_offset );
					date = moment( timestamp * 1000 ).utcOffset( offset );
				}
				
		
				const datepicker = $( `#${ type }-date` ).datepicker( {
					changeMonth : true,
					changeYear  : true,
					dateFormat  : 'MM dd, yy',
					onSelect    : function( selectedDate, instance ) {
						applyDateChange( selectedDate, instance,type );
					},
				} );

				datepicker.datepicker( 'setDate', date.toDate() );

				$( `#${ type }-hours` ).val( date.format( 'HH' ) );

			
				$( `#${ type }-minutes` ).val( date.format( 'mm' ) );

				if(type=='end'){
					const monthNum = date.format( 'M' );
					const monthName= wpadcenter_ad_scheduler.months[ ( monthNum<10 ? '0'+monthNum : monthNum ) ];

					let publishText= `${wpadcenter_ad_scheduler.expires_message}: <strong>${monthName} ${date.format('D,YYYY')}</strong>`;
					if(wpadcenter_ad_scheduler.expire_limit === $(`#end_date`).val()){
						publishText= wpadcenter_ad_scheduler.forever_message;
					}
					$(`#publish-text`).html(publishText);

				}

			}



			$( 'body' ).on('change','#start-date, #start-hours, #start-minutes',function applyHourChange() {

				const instance = $.datepicker._getInst( $( '#start-date' )[0] );
				applyDateChange( null, instance, 'start' );

			}
			);
			$( 'body' ).on('change','#end-date, #end-hours, #end-minutes',function applyHourChange() {

				const instance = $.datepicker._getInst( $( '#end-date' )[0] );
				applyDateChange( null, instance, 'end' );

			}
			);
			
			

			function applyDateChange(selectedDate,instance,type){

				let day      = instance.selectedDay.toString();
				let month    = ( instance.selectedMonth + 1 ).toString();
				const year   = instance.selectedYear;
				const hour   = $( `#${ type }-hours` ).val();
				const minute = $( `#${ type }-minutes` ).val();
				

				let timestamp;

				
				if ( ! wpadcenter_ad_scheduler.timezone_string ) {
				
					const time = moment( `${ year }-${ month }-${ day } ${ hour }:${ minute }Z` );
		
					timestamp = Number.parseInt( time.format( 'X' ) ) + ( wpadcenter_ad_scheduler.gmt_offset * 3600 );
					
				} else {
			
					const time = moment.tz( `${ year }-${ month }-${ day } ${ hour }:${ minute }`, wpadcenter_ad_scheduler.timezone_string );

					timestamp = time.format( 'X' );
				}

				
			
				$( `#${ type }_date` ).val( timestamp );

				if(type=='end'){
				

					let publishText= `${wpadcenter_ad_scheduler.expires_message}: <strong> ${ $('#end-date').val() }</strong>`;
					if(wpadcenter_ad_scheduler.expire_limit === $(`#end_date`).val()){
						publishText= wpadcenter_ad_scheduler.forever_message;
					}
					$(`#publish-text`).html(publishText);

				}
			}
			$( '#ad-publish-forever' ).click(function publishAdForever() {
		
					$(`#end_date`).val(wpadcenter_ad_scheduler.expire_limit);

					let expire_limit;

					if ( ! wpadcenter_ad_scheduler.timezone_string ) {
						expire_limit = moment( wpadcenter_ad_scheduler.expire_limit * 1000 + ( 3600 * wpadcenter_ad_scheduler.gmt_offset ) );
					} else {
						expire_limit = moment( wpadcenter_ad_scheduler.expire_limit * 1000 ).tz( wpadcenter_ad_scheduler.timezone_string );
					}
				
					$( '#end-date' ).datepicker( 'setDate', expire_limit.toDate() );
				
					$( '.end-hour' ).val( expire_limit.format( 'HH' ) );
					$( '.end-minute' ).val( expire_limit.format( 'mm' ) );
		
				}
			);

			$( '#ad-schedule-ok' ).click(function adScheduleOk() {
				$(`#ad-schedule-show`).hide('slow');
				$(`#edit-ad-schedule`).show();
			});
			
		}
		

		if( undefined !== wpadcenter_render_metaboxes ){

			let metaboxes = [];
			let ad_types = {};

			let ad_meta_relation= wpadcenter_render_metaboxes[0];

			let current_ad_type=wpadcenter_render_metaboxes[1];

			for (let [key, val] of Object.entries(ad_meta_relation)) {

				ad_types[key] = val.active_meta_box;
				metaboxes.push(...val.active_meta_box);

			}

			


			metaboxes = _.uniq(metaboxes);

			change_active_metaboxes( current_ad_type );


			$('#ad-type').change(function () {
					const selected_ad = $("#ad-type :selected").val();
					change_active_metaboxes(selected_ad);		
		
				}
			);

			
			function change_active_metaboxes(selected_ad){


				const active_metaboxes = ad_types[selected_ad];
		
				metaboxes.forEach((metabox) => {
					if (active_metaboxes.includes(metabox)) {
	
						// Show this metabox and return
						$(`#${metabox}`).show();
	
					}
					else {
						// Hide this metabox
						$(`#${metabox}`).hide();
	
					}
				});

			}
			
		}
		
	});



})(jQuery);

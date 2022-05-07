/**
 * Javascript file for adscheduler.
 *
 * @since 1.0.0
 *
 * @package
 */
/*global jQuery, wpadcenter_ad_scheduler*/

( function( $ ) {
	'use strict';
	$( document ).ready( function() {
		if ( 'undefined' !== typeof wpadcenter_ad_scheduler ) {
			$( '#minor-publishing-actions' ).hide();
			$( '#misc-publishing-actions' ).hide();

			$( '#edit-ad-schedule' ).click( function() {
				$( '#ad-schedule-show' ).show( 'slow' );
				$( '#edit-ad-schedule' ).hide();
			} );

			setDatePicker( 'start' );
			setDatePicker( 'end' );

			function setDatePicker( type ) {
				const timestamp = $( `#${ type }_date` ).val();

				let date;

				if ( wpadcenter_ad_scheduler.timezone_string ) {
					date = moment( timestamp * 1000 ).tz( wpadcenter_ad_scheduler.timezone_string );
				} else {
					const offset = Number.parseInt( wpadcenter_ad_scheduler.gmt_offset );
					date = moment( timestamp * 1000 ).utcOffset( offset );
				}

				const datepicker = $( `#${ type }-date` ).datepicker( {
					changeMonth: true,
					changeYear: true,
					dateFormat: 'MM dd, yy',
					onSelect: function( selectedDate, instance ) {
						applyDateChange( selectedDate, instance, type );
					},
				} );

				datepicker.datepicker( 'setDate', date.toDate() );

				$( `#${ type }-hours` ).val( date.format( 'HH' ) );

				$( `#${ type }-minutes` ).val( date.format( 'mm' ) );

				if ( type == 'end' ) {
					const monthNum = date.format( 'M' );
					const monthName = wpadcenter_ad_scheduler.months[ ( monthNum < 10 ? '0' + monthNum : monthNum ) ];

					let publishText = `${wpadcenter_ad_scheduler.expires_message}: <strong>${monthName} ${date.format( 'D,YYYY' )}</strong>`;
					if ( wpadcenter_ad_scheduler.expire_limit === $( '#end_date' ).val() ) {
						publishText = wpadcenter_ad_scheduler.forever_message;
					}
					$( '#publish-text' ).html( publishText );
				}
			}

			$( 'body' ).on( 'change', '#start-date, #start-hours, #start-minutes', function applyHourChange() {
				const instance = $.datepicker._getInst( $( '#start-date' )[0] );
				applyDateChange( null, instance, 'start' );
			},
			);
			$( 'body' ).on( 'change', '#end-date, #end-hours, #end-minutes', function applyHourChange() {
				const instance = $.datepicker._getInst( $( '#end-date' )[0] );
				applyDateChange( null, instance, 'end' );
			},
			);

			function applyDateChange( selectedDate, instance, type ) {
				let day = instance.selectedDay.toString();
				let month = ( instance.selectedMonth + 1 ).toString();
				const year = instance.selectedYear;
				const hour = $( `#${ type }-hours` ).val();
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

				if ( type == 'end' ) {
					let publishText = `${wpadcenter_ad_scheduler.expires_message}: <strong> ${ $( '#end-date' ).val() }</strong>`;
					if ( wpadcenter_ad_scheduler.expire_limit === $( '#end_date' ).val() ) {
						publishText = wpadcenter_ad_scheduler.forever_message;
					}
					$( '#publish-text' ).html( publishText );
				}
			}
			$( '#ad-publish-forever' ).click( function publishAdForever() {
				$( '#end_date' ).val( wpadcenter_ad_scheduler.expire_limit );

				let expire_limit;

				if ( ! wpadcenter_ad_scheduler.timezone_string ) {
					expire_limit = moment( wpadcenter_ad_scheduler.expire_limit * 1000 + ( 3600 * wpadcenter_ad_scheduler.gmt_offset ) );
				} else {
					expire_limit = moment( wpadcenter_ad_scheduler.expire_limit * 1000 ).tz( wpadcenter_ad_scheduler.timezone_string );
				}

				$( '#end-date' ).datepicker( 'setDate', expire_limit.toDate() );

				$( '.end-hour' ).val( expire_limit.format( 'HH' ) );
				$( '.end-minute' ).val( expire_limit.format( 'mm' ) );
			},
			);

			$( '#ad-schedule-ok' ).click( function adScheduleOk() {
				$( '#ad-schedule-show' ).hide( 'slow' );
				$( '#edit-ad-schedule' ).show();
			} );
		}
	} );
}( jQuery ) );

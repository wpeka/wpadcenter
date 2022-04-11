/*global jQuery, elementorFrontend*/
jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/wpadcenter-adtype.default', function( $scope, $ ) {
		$( document ).ready( function() {
			var slideIndex = [];
			var time = [];
			var children = [];
			$( '.wpadcenter_rotating_adgroup' ).each( function( index ) {
				slideIndex[index] = 0;
				time[index] = $( this ).find( '#wpadcenter_rotating_time' ).val();
				children[index] = $( this ).find( '.wpadcenter-ad-container' );
				function carousel( slideIndex, time, children ) {
					for ( var i = 0; i < children.length; i++ ) {
						$( children[i] ).hide();
					}
					slideIndex++;
					if ( slideIndex > children.length ) {
						slideIndex = 1;
					}
					$( children[slideIndex - 1] ).css( 'display', 'block' );
					setTimeout( function() {
						carousel( slideIndex, time, children );
					}, parseInt( time * 1000 ) );
				}
				carousel( slideIndex[index], time[index], children[index] );
			} );
		} );
	} );
} );


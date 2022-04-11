/*global wp, jQuery, wpadcenter_adtypes_verify*/
const apiFetch = wp.apiFetch;
const { Component } = wp.element;
const { __ } = wp.i18n;

class AdTypes extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			ad_id: this.props.adId,
			ad_type: this.props.ad_type,

			ad_html: {
				__html: '',
			},
		};
	}

	componentDidMount() {
		this.setState( {
			ad_html: {
				__html: `<h4 style="font-weight:300">${__( 'Loading Ad', 'wpadcenter' )}</h4>`,
			},
		} );

		const animatedAds = [];
		if ( this.props.ad_type === 'Animated Ads' ) {
			this.props.animated_ads.forEach( ( ad ) => {
				animatedAds.push( ad.value );
			} );
		} else {
			this.props.ordered_ads.forEach( ( ad ) => {
				animatedAds.push( ad.value );
			} );
		}
		if ( this.props.ad_type === 'Animated Ads' ) {
			this.setState( {
				ad_html: {
					__html: `<div class="wpadcenter-gutenberg-preview-container"><strong>WPAdcenter Animated Ads</strong><p>${ __(
						'Preview for animated ads is not availble in the editor, it can be seen on the preview or live page.',
						'wpadcenter',
					) }</p></div>`,
				},
			} );
		}
		if ( this.props.ad_type !== 'Animated Ads' ) {
			this.loadAds( animatedAds );
		}
	}

	loadAds( animatedAds ) {
		var j = jQuery.noConflict();
		j.ajax( {
			type: 'POST',
			url: './admin-ajax.php',
			data: {
				action: 'wpadcenter_adtypes_gutenberg_preview',
				adtypes_nonce: wpadcenter_adtypes_verify.adtypes_nonce,
				ad_type: this.props.ad_type,
				ad_id: this.props.adId,
				alignment: this.props.adAlignment,
				max_width_check: this.props.max_width_check,
				max_width_px: this.props.max_width_px,
				ad_groups: this.props.adGroupIds,
				adgroupAlignment: this.props.adgroupAlignment,
				num_ads: this.props.numAds,
				num_columns: this.props.numColumns,
				time: this.props.time,
				ad_order: this.props.adOrder,
				adgroup_id: this.props.adGroupId,
				ad_ids: animatedAds,
				adgroup_ad_ids: this.props.adgroup_ad_ids,
				ordered_ads: animatedAds,
			},
		} ).done( adtypes_html => {
			this.setState( {
				ad_html: {
					__html: JSON.parse( adtypes_html ).html,
				},
			} );

			( function( $ ) {
				'use strict';
				const slideIndex = [];
				const time = [];
				const children = [];
				$( '.wpadcenter_rotating_adgroup' ).each( function( index ) {
					slideIndex[ index ] = 0;
					time[ index ] = $( this )
						.find( '#wpadcenter_rotating_time' )
						.val();
					children[ index ] = $( this ).find(
						'.wpadcenter-ad-container',
					);
					function carousel( slideIndex, time, children ) {
						for ( let i = 0; i < children.length; i++ ) {
							$( children[ i ] ).hide();
						}
						slideIndex++;
						if ( slideIndex > children.length ) {
							slideIndex = 1;
						}
						$( children[ slideIndex - 1 ] ).css(
							'display',
							'block',
						);
						setTimeout( function() {
							carousel( slideIndex, time, children );
						}, parseInt( time * 1000 ) );
					}
					carousel(
						slideIndex[ index ],
						time[ index ],
						children[ index ],
					);
				} );
			}( jQuery ) );
		} );
	}

	render() {
		let adAlignment = {
			zIndex: '20',
			position: 'relative',
		};
		return (

			<div style={ adAlignment } dangerouslySetInnerHTML={ this.state.ad_html } ></div>
		);
	}
}
export default AdTypes;

/*global wp, jQuery, wpadcenter_singlead_verify*/
const apiFetch = wp.apiFetch;
const { Component } = wp.element;
const { __ } = wp.i18n;

class AdTypes extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			ad_id: this.props.adId,

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
		this.loadAds();
	}

	loadAds() {
		var j = jQuery.noConflict();
		j.ajax( {
			type: 'POST',
			url: './admin-ajax.php',
			data: {
				action: 'wpadcenter_adtypes_gutenberg_preview',
				adtypes_nonce: wpadcenter_adtypes_verify.adtypes_nonce,
				ad_id: this.props.adId,
				alignment: this.props.adAlignment,
				max_width_check: this.props.max_width_check,
				max_width_px: this.props.max_width_px,
			},
		} ).done( adtypes_html => {
			this.setState( {
				ad_html: {
					__html: JSON.parse( adtypes_html ).html,
				},
			} );
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

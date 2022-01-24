/*global wp, jQuery, wpadcenter_singlead_verify*/
const apiFetch = wp.apiFetch;
const { Component } = wp.element;
const { __ } = wp.i18n;

class SingleAd extends Component {
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
				action: 'wpadcenter_singlead_gutenberg_preview',
				singlead_nonce: wpadcenter_singlead_verify.singlead_nonce,
				ad_id: this.props.adId,
				alignment: this.props.adAlignment,
				max_width_check: this.props.max_width_check,
				max_width_px: this.props.max_width_px,
			},
		} ).done( singlead_html => {
			this.setState( {
				ad_html: {
					__html: JSON.parse( singlead_html ).html,
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
export default SingleAd;

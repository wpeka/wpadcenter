/*global wp, jQuery, wpadcenter_random_ad_verify*/
const apiFetch = wp.apiFetch;
const { Component } = wp.element;
const { __ } = wp.i18n;

class RandomAd extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
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
				action: 'wpadcenter_random_ad_gutenberg_preview',
				random_ad_nonce: wpadcenter_random_ad_verify.random_ad_nonce,
				ad_groups: this.props.adGroupIds,
				alignment: this.props.adgroupAlignment,
				max_width_check: this.props.max_width_check,
				max_width_px: this.props.max_width_px,

			},
		} ).done( random_ad_html => {
			this.setState( {
				ad_html: {
					__html: JSON.parse( random_ad_html ).html,
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
export default RandomAd;

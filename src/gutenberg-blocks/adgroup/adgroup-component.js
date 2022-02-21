/*global wp, jQuery, wpadcenter_adgroup_verify*/
const apiFetch = wp.apiFetch;
const { Component } = wp.element;
const { __ } = wp.i18n;

class AdGroup extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			ad_ids: [],
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
				action: 'wpadcenter_adgroup_gutenberg_preview',
				adgroup_nonce: wpadcenter_adgroup_verify.adgroup_nonce,
				ad_groups: this.props.adGroupIds,
				alignment: this.props.adgroupAlignment,
				num_ads: this.props.numAds,
				num_columns: this.props.numColumns,
				max_width_check: this.props.max_width_check,
				max_width_px: this.props.max_width_px,
			},
		} ).done( adgroup_html => {
			this.setState( {
				ad_html: {
					__html: JSON.parse( adgroup_html ).html,
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
export default AdGroup;

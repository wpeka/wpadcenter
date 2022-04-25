/*global wp*/
import AsyncSelect from 'react-select/async';

const { registerBlockType } = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder } = wp.components;
const { __ } = wp.i18n;

import SingleAd from './single-ad-component';
import AdAlignment from '../ad-alignment-component';
import MaxWidth from '../maxwidth-component';
import SelectDevice from '../select-device-component';
import icons from '../icons';

registerBlockType( 'wpadcenter/single-ad', {

	title: __( 'WPAdCenter Single Ad (Deprecated)', 'wpadcenter' ),
	description: __( 'Block to generate WPAdCenter single ad', 'wpadcenter' ),
	icon: icons.icon,
	category: 'wpadcenter',

	attributes: {
		ad_id: {
			type: 'number',
		},
		ad_name: {
			type: 'text',
		},
		ad_alignment: {
			type: 'text',
		},
		max_width_check: {
			type: 'boolean',
			default: false,
		},
		max_width_px: {
			type: 'text',
			default: 100,
		},
		devices: {
			type: 'string',
			default: '["mobile","tablet","desktop"]',
		},
		align: {
			type: 'string',
			default: 'wide',
		},
	},

	edit( props ) {
		const getOptions = ( value, callback )=>{
			apiFetch( {
				path: '/wp/v2/wpadcenter-ads/',
			} )
				.then( ( ads ) => {
					ads = ads.map( ( ad ) => {
						let adLabel = ad.title.rendered + ' ( ' + ad.ad_type + ' - ' + ad.ad_size + ' )';
						return {
              value: ad.id,
              label: adLabel,
            };
					} );
					callback( ads );
				} );
		};

		const customStyles = {
			control: ( base, state ) => ( {
				...base,

				minWidth: '300px',
				maxWidth: '400px',

				fontSize: '125%',
				borderRadius: state.isFocused ? '3px 3px 0 0' : 3,

				boxShadow: state.isFocused ? null : null,

			} ),
			menu: base => ( {
				...base,

				borderRadius: 0,

				marginTop: 0,
				minWidth: '300px',
			} ),
			menuList: base => ( {
				...base,
				padding: 0,
				maxWidth: '300px',

			} ),
		};

		const onMaxWidthControlChange = ( value )=>{
			props.setAttributes( {
				max_width_check: value,

			} );
		};
		const onMaxWidthChange = ( value )=>{
			props.setAttributes( {
				max_width_px: value,

			} );
		};

		const onAdSelection = ( selection ) => {
			props.setAttributes( {
				ad_id: selection.value,
				ad_name: selection.label,

			} );
		};
		const defaultValue = {
			value: props.attributes.ad_id,
			label: props.attributes.ad_name,
		};

		const adAlignment = ( value )=>{
			props.setAttributes( {
				ad_alignment: value,
			} );
		};

		const onDeviceListChange = ( value )=>{
			let currentDevicesList = JSON.parse( props.attributes.devices );
			var index = currentDevicesList.indexOf( value );
			if ( index !== -1 ) {
				currentDevicesList.splice( index, 1 );
			} else {
				currentDevicesList.push( value );
			}
			props.setAttributes( {
				devices: JSON.stringify( currentDevicesList ),
			} );
		};

		return <div className="Wpadcenter-gutenberg-container">
			{ !! props.isSelected ? (

				<Placeholder label="WPAdCenter Single Ad" isColumnLayout="true">

					<h3 style={ { fontWeight: '300', textAlign: 'center', fontSize: 'medium' } }>{ __( 'Select Ad', 'wpadcenter' ) }</h3>
					<div style={ { display: 'flex', justifyContent: 'center' } }>

						<AsyncSelect
							styles={ customStyles }
							className="wpadcenter-async-select"
							defaultOptions
							loadOptions={ getOptions }
							defaultValue={ defaultValue }
							onChange={ onAdSelection }

						/>
					</div>
					<AdAlignment
						adAlignment={ adAlignment }
						currentAdAlignment={ props.attributes.ad_alignment }
					/>
					<MaxWidth
						maxWidthCheck={ props.attributes.max_width_check }
						maxWidthControlChange={ onMaxWidthControlChange }
						maxWidthChange={ onMaxWidthChange }
						maxWidth={ props.attributes.max_width_px }
					/>
					<SelectDevice
						devicesList={ JSON.parse( props.attributes.devices ) }
						devicesListChange={ onDeviceListChange }
						displayOnMobile={ JSON.parse( props.attributes.devices ).indexOf( 'mobile' ) !== -1 ? true : false }
						displayOnTablet={ JSON.parse( props.attributes.devices ).indexOf( 'tablet' ) !== -1 ? true : false }
						displayOnDesktop={ JSON.parse( props.attributes.devices ).indexOf( 'desktop' ) !== -1 ? true : false }

					/>

				</Placeholder> ) : (
					<SingleAd
						adId={ props.attributes.ad_id }
						adAlignment={ props.attributes.ad_alignment }
						max_width_check={ props.attributes.max_width_check }
						max_width_px={ props.attributes.max_width_px }
				/>

			) }

		</div>;
	},

	save() {
		return null;
	},

} );

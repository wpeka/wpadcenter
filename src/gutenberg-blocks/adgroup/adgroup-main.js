/*global wp*/

import AsyncSelect from 'react-select/async';

const { registerBlockType } = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder } = wp.components;
const { __ } = wp.i18n;

import AdGroup from './adgroup-component';
import AdAlignment from '../ad-alignment-component';
import MaxWidth from '../maxwidth-component';
import SelectDevice from '../select-device-component';
import icons from '../icons';

registerBlockType( 'wpadcenter/adgroup', {

	title: __( 'WPAdCenter Grouped Ads (Deprecated)', 'wpadcenter' ),
	description: __( 'Block to generate WPAdCenter Ads from Adgroups', 'wpadcenter' ),
	icon: icons.icon,
	category: 'wpadcenter',

	attributes: {
		ad_ids: {
			type: 'array',
			default: [],
		},
		adgroup_ids: {
			type: 'array',
			default: [],
		},
		adgroups: {
			type: 'array',
			default: [],
		},
		adgroup_alignment: {
			type: 'text',
		},
		num_columns: {
			type: 'text',
			default: '1',
		},
		num_ads: {
			type: 'text',
			default: '1',
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
				path: '/wp/v2/wpadcenter-adgroups/',
			} )
				.then( ( adgroups ) => {
					adgroups = adgroups.map( ( adgroup ) => {
						return {
							value: adgroup.id,
							label: adgroup.name,
							ad_ids: adgroup.ad_ids,
						};
					} );
					callback( adgroups );
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
			let current_adgroup_ids = [];
			let current_ad_ids = [];
			selection.forEach( ( adgroup )=>{
				current_adgroup_ids.push( adgroup.value );
				current_ad_ids = [ ...current_ad_ids, ...adgroup.ad_ids ];
			} );
			props.setAttributes( {
				adgroup_ids: current_adgroup_ids,
				adgroups: selection,
				ad_ids: current_ad_ids,

			} );
		};
		const defaultValue = props.attributes.adgroups;

		const adAlignment = ( value )=>{
			props.setAttributes( {
				adgroup_alignment: value,
			} );
		};
		const headingStyles = {
			fontWeight: '300',
			textAlign: 'center',
			fontSize: 'medium',
		};
		const setNumAds = ( event )=>{
			props.setAttributes( {
				num_ads: event.target.value,

			} );
		};
		const setNumCol = ( event )=>{
			props.setAttributes( {
				num_columns: event.target.value,

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

				<Placeholder label="WPAdCenter Grouped Ads" isColumnLayout="true">

					<h3 style={ headingStyles }>{ __( 'Select Ad Groups', 'wpadcenter' ) }</h3>
					<div style={ { display: 'flex', justifyContent: 'center' } }>

						<AsyncSelect
							styles={ customStyles }
							className="wpadcenter-async-select"
							defaultOptions
							isMulti
							loadOptions={ getOptions }
							defaultValue={ defaultValue }
							onChange={ onAdSelection }

						/>
					</div>

					<AdAlignment
						adAlignment={ adAlignment }
						currentAdAlignment={ props.attributes.adgroup_alignment }
					/>
					<div style={ { display: 'flex', justifyContent: 'space-around' } }>
						<div>
							<h3 style={ headingStyles }>{ __( 'Number of Ads', 'wpadcenter' ) }</h3>
							<input type="number" min="1" onChange={ setNumAds } value={ props.attributes.num_ads } />
						</div>
						<div>
							<h3 style={ headingStyles }>{ __( 'Number of Columns', 'wpadcenter' ) }</h3>
							<input type="number" min="1" onChange={ setNumCol } value={ props.attributes.num_columns } />

						</div>
					</div>
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
					<AdGroup
						numAds={ props.attributes.num_ads }
						numColumns={ props.attributes.num_columns }
						adGroupIds={ props.attributes.adgroup_ids }
						adIds={ props.attributes.ad_ids }
						adgroupAlignment={ props.attributes.adgroup_alignment }
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

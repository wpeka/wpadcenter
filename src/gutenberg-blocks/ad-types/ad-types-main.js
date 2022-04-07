/*global wp*/
import AsyncSelect from 'react-select/async';
import AdTypes from '../ad-types/ad-types-component';
import AdAlignment from '../ad-alignment-component';
import MaxWidth from '../maxwidth-component';
import SelectDevice from '../select-device-component';

const { registerBlockType } = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder } = wp.components;
const { __ } = wp.i18n;
const { CheckboxControl, Tooltip } = wp.components;


registerBlockType( 'wpadcenter/ad-types', {

	title: __( 'WPAdCenter Ad Block', 'wpadcenter' ),
	description: __( 'Choose from different blocks', 'wpadcenter' ),
	icon: 'flag',
	category: 'wpadcenter',

	attributes: {
		ad_type: {
			type: 'string',
			default: '',
		},
		is_pro:{
			type: 'boolean',
			default: wpadcenter_adtypes_verify.is_pro
		},
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
		time: {
			type: 'text',
			default: '10',
		},
		ad_order: {
			type: 'boolean',
			default: false,
		},
		adgroup_id: {
			type: 'number',
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
		const getAdGroupOptions = ( value, callback )=>{
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
		const getRotatingAdGroupOptions = ( value, callback ) => {
			apiFetch( {
				path: '/wp/v2/wpadcenter-adgroups/',
			} ).then( ( adgroups ) => {
				adgroups = adgroups.map( ( adgroup ) => {
					return {
						value: adgroup.id,
						label: adgroup.name,
					};
				} );
				callback( adgroups );
			} );
		};
		
		
        const customStyles = {
			control: ( base, state ) => ( {
				...base,

				minWidth: '450px',
				maxWidth: '400px',

				fontSize: '125%',
				borderRadius: state.isFocused ? '3px 3px 0 0' : 3,

				boxShadow: state.isFocused ? null : null,

			} ),
			menu: base => ( {
				...base,

				borderRadius: 0,

				marginTop: 0,
				minWidth: '450px',
			} ),
			menuList: base => ( {
				...base,
				padding: 0,
				maxWidth: '450px',

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
		const onAdSelection = ( selection ) => {
			props.setAttributes( {
				ad_id: selection.value,
				ad_name: selection.label,

			} );
		};
        const adAlignment = ( value )=>{
			props.setAttributes( {
				ad_alignment: value,
			} );
		};
		const adGroupAlignment = ( value )=>{
			props.setAttributes( {
				adgroup_alignment: value,
			} );
		};
		const onAdOrderChange = ( value ) => {
			props.setAttributes( {
				ad_order: value,
			} );
		};
		const onRotatingAdSelection = ( selection ) => {
			props.setAttributes( {
				adgroup_id: selection.value,
				ad_name: selection.label,
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

		const getAdOptions =  [
            {value:"Single Ad",label:'Single Ad'}, 
            {value:"Adgroup",label:'Adgroup'}, 
            {value:"Random Ads",label:'Random Ads'},
		];

        const getProAdOptions =  [
            {value:"Single Ad",label:'Single Ad'}, 
            {value:"Adgroup",label:'Adgroup'}, 
            {value:"Random Ads",label:'Random Ads'}, 
			{value:"Rotating Ads",label:'Rotating Ads'},
			{value:"Ordered Ads",label:'Ordered Ads'},
			{value:"Animated Ads",label:'Animated Ads'},
        ]
		
        const defaultValue = {
			value: props.attributes.ad_type,
			label: props.attributes.ad_type,
		};

		const defaultValueSingleAd = {
			value: props.attributes.ad_id,
			label: props.attributes.ad_name,
		}

		const defaultValueAdgroup = props.attributes.adgroups;

		const defaultValueRotatingAds = {
			value: props.attributes.ad_id,
			label: props.attributes.ad_name,
		};

		//const defaultValueRotatingAds = props.attributes.adgroup_id;
		const headingStyles = {
			fontWeight: '300',
			textAlign: 'center',
			fontSize: 'medium',
		};

        const onAdTypeSelection = ( selection ) => {
			props.setAttributes( {
				ad_type: selection.label,

			} );
		};

		const onAdGroupSelection = ( selection ) => {
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

		const setTime = ( event ) => {
			props.setAttributes( {
				time: event.target.value,
			} );
		};
		
		return <div className="Wpadcenter-gutenberg-container">
			{ !! props.isSelected ? (
            <Placeholder label="WPAdCenter Ad Block" isColumnLayout="true">
				 
				<h3 style={ headingStyles }>{ __( 'Select Ad Type', 'wpadcenter' ) }</h3>
				<div style={ { display: 'flex', justifyContent: 'center' } }>
				<AsyncSelect
					styles={ customStyles }
					className="wpadcenter-async-select"
					defaultOptions = { props.attributes.is_pro !== '' ? getProAdOptions : getAdOptions  }
					defaultValue={ defaultValue }
					onChange={ onAdTypeSelection }
				/>
				</div>
				{ props.attributes.ad_type !== '' ? (
                   <div>
					{ props.attributes.ad_type === 'Single Ad' ? (
						<div>
							<h3 style={ { fontWeight: '300', textAlign: 'center', fontSize: 'medium' } }>{ __( 'Select Ad', 'wpadcenter' ) }</h3>
							<div style={ { display: 'flex', justifyContent: 'center' } }>

								<AsyncSelect
									key = { props.attributes.ad_type }
									styles={ customStyles }
									className="wpadcenter-async-select"
									defaultOptions
									loadOptions={ getOptions }
									defaultValue={ defaultValueSingleAd }
									onChange={ onAdSelection }
								/>
							</div>
							<AdAlignment
								key = { props.attributes.ad_type }
								adAlignment={ adAlignment }
								currentAdAlignment={ props.attributes.ad_alignment }
							/>
							<p>{props.attributes.ad_alignment}</p>
						</div> ) : (
							<div>
								<h3 style={ headingStyles }>{ __( 'Select Ad Groups', 'wpadcenter' ) }</h3>
								<div style={ { display: 'flex', justifyContent: 'center' } }>
								{  props.attributes.ad_type === 'Rotating Ads' ? (
									<div>
									<AsyncSelect
									key = { props.attributes.ad_type }
									styles={ customStyles }
									className="wpadcenter-async-select"
									defaultOptions
									loadOptions={ getRotatingAdGroupOptions  }
									defaultValue={ defaultValueRotatingAds }
									onChange={ onRotatingAdSelection  }
								/>
								</div>
								) : (
									<div>
									<AsyncSelect
										key = { props.attributes.ad_type }
										styles={ customStyles }
										className="wpadcenter-async-select"
										isMulti
										defaultOptions
										loadOptions={ getAdGroupOptions }
										defaultValue={ defaultValueAdgroup }
										onChange={ onAdGroupSelection }
									/>
									</div>
								) }
								</div>
							</div>
						) }

					{  props.attributes.ad_type === 'Rotating Ads' ? (
						<div style={ { textAlign: 'center' } }>
							<h3 style={ headingStyles }>
								{ __( 'Time (in seconds)', 'wpadcenter' ) }
							</h3>
							<input
								type="number"
								min="1"
								onChange={ setTime }
								value={ props.attributes.time }
							/>
						</div>
					): (<p></p>)}
				   { props.attributes.ad_type === 'Adgroup' ? (
					<div>
						<AdAlignment
							key = { props.attributes.ad_type }
							adAlignment={ adGroupAlignment }
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
					</div> ) : props.attributes.ad_type === 'Random Ads' ? (
						<AdAlignment
						key = { props.attributes.ad_type }
						adAlignment={ adGroupAlignment }
						currentAdAlignment={ props.attributes.adgroup_alignment }
					/> ) : props.attributes.ad_type === 'Rotating Ads' ? (
						<div>
						<AdAlignment
							key = { props.attributes.ad_type }
							adAlignment={ adAlignment }
							currentAdAlignment={ props.attributes.ad_alignment }
						/>
						</div>
					) : ( <p></p> ) }
                   <MaxWidth
                       maxWidthCheck={ props.attributes.max_width_check }
                       maxWidthControlChange={ onMaxWidthControlChange }
                       maxWidthChange={ onMaxWidthChange }
                       maxWidth={ props.attributes.max_width_px }
                   />
					{ props.attributes.ad_type === 'Rotating Ads' ? (
					<div className="wpadcenter-order-container">
						<CheckboxControl
							onChange={ onAdOrderChange }
							checked={ props.attributes.ad_order }
							style={ {
								fontSize: 'medium',
								fontWeight: '300',
							} }
						/>
						<h3
							style={ {
								fontWeight: '300',
								textAlign: 'center',
								marginTop: '20px',
								fontSize: 'medium',
								margin: '0',
							} }
						>
							{ __( 'Order Randomly', 'wpadcenter' ) }
						</h3>

						<Tooltip
							text={ __(
								'If unchecked the ads are ordered by published date',
								'wpadcenter',
							) }
						>
							<span className="dashicons dashicons-lightbulb"></span>
						</Tooltip>
					</div> ) : ( <p></p> ) }
						
                   <SelectDevice
                       devicesList={ JSON.parse( props.attributes.devices ) }
                       devicesListChange={ onDeviceListChange }
                       displayOnMobile={ JSON.parse( props.attributes.devices ).indexOf( 'mobile' ) !== -1 ? true : false }
                       displayOnTablet={ JSON.parse( props.attributes.devices ).indexOf( 'tablet' ) !== -1 ? true : false }
                       displayOnDesktop={ JSON.parse( props.attributes.devices ).indexOf( 'desktop' ) !== -1 ? true : false }
                   />
               </div> ) : (<p></p>) }
            </Placeholder> ) : (
				<div>
				<AdTypes
					ad_type={ props.attributes.ad_type}
					adId={ props.attributes.ad_id }
					adAlignment={ props.attributes.ad_alignment }
					adgroupAlignment={ props.attributes.adgroup_alignment }
					max_width_check={ props.attributes.max_width_check }
					max_width_px={ props.attributes.max_width_px }
					numAds={ props.attributes.num_ads }
					numColumns={ props.attributes.num_columns }
					adGroupIds={ props.attributes.adgroup_ids }
					adGroupId={ props.attributes.adgroup_id }
					adIds={ props.attributes.ad_ids }
					time={ props.attributes.time }
					adOrder={ props.attributes.ad_order }
				/> 
				
				</div>)
			}
		</div>;
	},

	save() {
		return null;
	},

} );

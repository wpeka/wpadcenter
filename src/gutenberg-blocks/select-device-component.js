/*global wp*/
const { Component } = wp.element;
const { Tooltip } = wp.components;
const { __ } = wp.i18n;

class SelectDevice extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			displayOnMobile: this.props.displayOnMobile,
			displayOnTablet: this.props.displayOnTablet,
			displayOnDesktop: this.props.displayOnDesktop,
		};
		this.onDeviceChange = this.onDeviceChange.bind( this );
	}

	componentDidUpdate( prevProps ) {
		if ( prevProps.devicesList !== this.props.devicesList ) {
			if ( this.props.devicesList.indexOf( 'mobile' ) !== -1 ) {
				this.setState( {
					displayOnMobile: true,
				} );
			} else {
				this.setState( {
					displayOnMobile: false,
				} );
			}
			if ( this.props.devicesList.indexOf( 'tablet' ) !== -1 ) {
				this.setState( {
					displayOnTablet: true,
				} );
			} else {
				this.setState( {
					displayOnTablet: false,
				} );
			}
			if ( this.props.devicesList.indexOf( 'desktop' ) !== -1 ) {
				this.setState( {
					displayOnDesktop: true,
				} );
			} else {
				this.setState( {
					displayOnDesktop: false,
				} );
			}
		}
	}

	onDeviceChange( event ) {
		this.props.devicesListChange( event.target.value );
	}
	render() {
		return <div>

			<div className="wpadcenter-select-ad-top">
				<h3 style={ { fontWeight: '300', textAlign: 'center', marginTop: '0px', fontSize: 'medium' } }>{ __( 'Display on Specific Devices', 'wpadcenter' ) }</h3>
				<Tooltip
					text={ __( 'Ads will be displayed only on selected devices.( Changes will take effect only on preview or live page and not while editing.)', 'wpadcenter' ) }
				>

					<span style={ { marginTop: '0px' } } className="dashicons dashicons-lightbulb"></span>
				</Tooltip>
			</div>
			<ul className="wpadcenter-specific-devices-container">
				<li className="wpadcenter-specific-devices__item">
					<input type="checkbox"
						name="mobileCheck"
						value="mobile"
						onChange={ this.onDeviceChange }
						checked={ this.state.displayOnMobile }
					/>
					<span className="dashicons dashicons-smartphone"></span>
					<span className="wpadcenter-specific-devices__label">Mobile</span>
				</li>

				<li className="wpadcenter-specific-devices__item">
					<input type="checkbox"
						name="tabletCheck"
						value="tablet"
						onChange={ this.onDeviceChange }
						checked={ this.state.displayOnTablet }

					/>
					<span className="dashicons dashicons-tablet"></span>
					<span className="wpadcenter-specific-devices__label">Tablet</span>

				</li>

				<li className="wpadcenter-specific-devices__item">
					<input type="checkbox"
						name="desktopCheck"
						value="desktop"
						onChange={ this.onDeviceChange }
						checked={ this.state.displayOnDesktop }

					/>
					<span className="dashicons dashicons-desktop"></span>
					<span className="wpadcenter-specific-devices__label">Desktop</span>

				</li>

			</ul>
		</div>;
	}
}

export default SelectDevice;

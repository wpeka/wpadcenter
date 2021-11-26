/*global wp*/
const { __ } = window.wp.i18n;
const { Component } = window.wp.element;
const {
	CheckboxControl,
	Tooltip,
} = wp.components;

class MaxWidth extends Component {
	constructor() {
		super( ...arguments );
		this.onMaxWidthControlChange = this.onMaxWidthControlChange.bind( this );
		this.onMaxWidthChange = this.onMaxWidthChange.bind( this );
	}
	onMaxWidthControlChange( value ) {
		this.props.maxWidthControlChange( value );
	}
	onMaxWidthChange( event ) {
		this.props.maxWidthChange( event.target.value );
	}

	render() {
		return (
			<div className="wpadcenter-maxwidth-container">
				<div className="wpadcenter-maxwidth-top">
					<CheckboxControl
						onChange={ this.onMaxWidthControlChange }
						checked={ this.props.maxWidthCheck }

					/>
					<h3 style={ { fontWeight: '300', textAlign: 'center', marginTop: '20px', fontSize: 'medium', margin: '0' } }>{ __( 'Enable Max Width', 'wpadcenter' ) }</h3>

					<Tooltip
						text={ __( 'Set maximum width for this ad', 'wpadcenter' ) }
					>

						<span className="dashicons dashicons-lightbulb"></span>
					</Tooltip>
				</div>
				{ this.props.maxWidthCheck && (
					<div className="wpadcenter-maxwidth-bottom">
						<label>
							<span style={ { fontWeight: '300', textAlign: 'center', marginTop: '20px', fontSize: 'medium' } }>{ __( 'Max width:', 'wpadcenter' ) }</span>
							<input
								type="text"
								style={ { marginLeft: '10px' } }
								onChange={ this.onMaxWidthChange }
								value={ this.props.maxWidth }
							/>
						</label>
					</div>
				) }
			</div>

		);
	}
}

export default MaxWidth;

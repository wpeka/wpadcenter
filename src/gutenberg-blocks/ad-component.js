/*global wp*/

const { Component } = wp.element;
const { __ } = wp.i18n;
const { IconButton } = wp.components;

class Ad extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			ad_id: this.props.adId,

			ad_html: {
				__html: '',
			},
		};
	}

	render() {
		const { adLabel, index, numAds } = this.props;
		const onMoveUp = () => {
			this.props.onMoveUp( index );
		};
		const onMoveDown = () => {
			this.props.onMoveDown( index );
		};
		const onClear = () => {
			this.props.onClear( index );
		};

		return (
			<div className="wpadcenter-animatedad-drag-list">
				<p>{ adLabel }</p>
				<div className="buttons">
					<IconButton
						disabled={ 0 === index }
						onClick={ onMoveUp }
						icon="arrow-up-alt"
					/>
					<IconButton
						disabled={ index === numAds - 1 }
						onClick={ onMoveDown }
						icon="arrow-down-alt"
					/>
					<IconButton onClick={ onClear } icon="no-alt" />
				</div>
			</div>
		);
	}
}
export default Ad;

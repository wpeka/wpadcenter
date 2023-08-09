/*global wp*/

const { Component } = wp.element;
const { IconButton } = wp.components;
const { __ } = wp.i18n;

import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import Ad from './ad-component';

class Sortable extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			ads: this.props.ads,
		};
	}

	componentDidUpdate( prevProps ) {
		if ( prevProps.ads !== this.props.ads ) {
			this.setState( {
				ads: this.props.ads,
			} );
		}
	}

	render() {
		const updateList = () => {
			this.setState( {
				ads: this.props.ads,
			} );
		};

		const onMoveUp = ( index ) => {
			this.props.onMoveUp( index );

			updateList();
		};
		const onMoveDown = ( index ) => {
			this.props.onMoveDown( index );
			updateList();
		};
		const onClear = ( index ) => {
			this.props.onClear( index );
			updateList();
		};

		const handleOnDragEnd = ( result ) => {
			if ( ! result.source || ! result.destination ) {
				return;
			}
			if ( result.source.index === result.destination.index ) {
				return;
			}
			this.props.handleOnDragEnd(
				result.source.index,
				result.destination.index,
			);
			updateList();
		};

		return (
			<div
				style={ {
					display: 'flex',
					justifyContent: 'center',
					marginTop: '20px',
				} }
			>
				<header className="wpadcenter-sortable-component">
					<DragDropContext onDragEnd={ handleOnDragEnd }>
						<Droppable droppableId="wpadcenter-draggable-ads">
							{ ( provided ) => (
								<ul
									className="wpadcenter-draggable-ads"
									{ ...provided.droppableProps }
									ref={ provided.innerRef }
								>
									{ this.state.ads.map( ( ad, index ) => {
										return (
											<Draggable
												key={ ad.value }
												draggableId={ ad.value.toString() }
												index={ index }
											>
												{ ( provided ) => (
													<li
														ref={
															provided.innerRef
														}
														{ ...provided.draggableProps }
														{ ...provided.dragHandleProps }
													>
														<Ad
															adLabel={ ad.label }
															numAds={
																this.state.ads
																	.length
															}
															index={ index }
															onMoveUp={
																onMoveUp
															}
															onMoveDown={
																onMoveDown
															}
															onClear={ onClear }
														/>
													</li>
												) }
											</Draggable>
										);
									} ) }
									{ provided.placeholder }
								</ul>
							) }
						</Droppable>
					</DragDropContext>
				</header>
			</div>
		);
	}
}

export default Sortable;

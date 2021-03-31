<?php
/**
 * The widget-specific functionality of the plugin for adgroups.
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * The widget-specific functionality of the plugin for adgroup ads.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 * @author     WPEka Club <support@wpeka.com>
 */
class Wpadcenter_Adgroup_Widget extends \WP_Widget {

	/**
	 * Wpadcenter_Widget constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$id_base        = 'Wpadcenter_Adgroup_Widget';
		$name           = 'WPAdCenter Ad Group';
		$widget_options = array( 'description' => __( 'Display Ads from Adgroup', 'wpadcenter' ) );
		parent::__construct( $id_base, $name, $widget_options );
	}


	/**
	 * Widget display functionality.
	 *
	 * @param array $args Widget default arguments.
	 * @param array $instance Widget form input values.
	 *
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {

		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? $args['after_title'] : '';

		$adgroup_ids = empty( $instance['adgroup_ids'] ) ? '' : $instance['adgroup_ids'];
		$title       = empty( $instance['title'] ) ? '' : $instance['title'];
		$num_ads     = empty( $instance['num_ads'] ) ? 1 : $instance['num_ads'];
		$num_columns = empty( $instance['num_columns'] ) ? 1 : $instance['num_columns'];
		$alignment   = empty( $instance['alignment'] ) ? 1 : $instance['alignment'];

		echo $before_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $before_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$attributes = array(
			'adgroup_ids' => $adgroup_ids,
			'align'       => $alignment,
			'num_ads'     => $num_ads,
			'num_columns' => $num_columns,
		);

		echo Wpadcenter_Public::display_adgroup_ads( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Widget update functionality.
	 *
	 * @param array $new_instance Widget new instance.
	 * @param array $old_instance Widget old instance.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		return $new_instance;
	}

	/**
	 * Widget form.
	 *
	 * @param array $instance Widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$adgroup_ids = empty( $instance['adgroup_ids'] ) ? array() : $instance['adgroup_ids'];
		$title       = empty( $instance['title'] ) ? '' : $instance['title'];
		$num_ads     = empty( $instance['num_ads'] ) ? 1 : $instance['num_ads'];
		$num_columns = empty( $instance['num_columns'] ) ? 1 : $instance['num_columns'];
		$alignment   = empty( $instance['alignment'] ) ? 'alignnone' : $instance['alignment'];

		$single_ads = array();

		$current_time = time();

		$terms = get_terms(
			array(
				'taxonomy' => 'wpadcenter-adgroups',
			)
		);

		?>
			<p>
				<label for="title"><?php echo esc_html__( 'Title : ', 'wpadcenter' ); ?></label>
				<input class="widefat" type="text" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label><?php echo esc_html__( 'Select Ad Groups : ', 'wpadcenter' ); ?></label><br/>
				<?php
				if ( $terms ) {
					foreach ( $terms as $term ) {
						?>
					<input type="checkbox" 
					id="<?php echo esc_attr( $this->get_field_id( $term->term_id ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'adgroup_ids' ) ) . '[]'; ?>"
					value="<?php echo esc_attr( $term->term_id ); ?>"
						<?php checked( in_array( strval( $term->term_id ), (array) $adgroup_ids, true ), true ); ?>/>

					<label for="<?php echo esc_attr( $this->get_field_id( $term->term_id ) ); ?>"><?php echo esc_html( $term->name ); ?></label><br />
						<?php
					}
				} else {
					?>
						<p>
							<?php echo esc_html__( 'No Adgroups Found.', 'wpadcenter' ); ?>
						</p>
					<?php
				}

				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'num_ads' ) ); ?>"><?php echo esc_html__( 'Number of Ads : ', 'wpadcenter' ); ?></label>
				<input type="number" class="widefat" min="1" 
				name="<?php echo esc_attr( $this->get_field_name( 'num_ads' ) ); ?>"
				value="<?php echo esc_attr( $num_ads ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'num_columns' ) ); ?>"><?php echo esc_html__( 'Number of Columns : ', 'wpadcenter' ); ?></label>
				<input type="number" class="widefat" min="1" 
				name="<?php echo esc_attr( $this->get_field_name( 'num_columns' ) ); ?>"
				value="<?php echo esc_attr( $num_columns ); ?>">
			</p>
				<label><?php echo esc_html__( 'Alignment : ', 'wpadcenter' ); ?> </label><br/>
					<?php
					$alignments = array(
						'alignnone'   => __( 'None', 'wpadcenter' ),
						'alignleft'   => __( 'Left', 'wpadcenter' ),
						'aligncenter' => __( 'Center', 'wpadcenter' ),
						'alignright'  => __( 'Right', 'wpadcenter' ),
					);
					foreach ( $alignments as $value => $name ) {
						?>
						<label>
						<input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>" 
						name="<?php echo esc_attr( $this->get_field_name( 'alignment' ) ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php checked( $value === $alignment, true ); ?>  />
						<?php echo esc_html( $name ); ?>
						</label>
						<?php
					}
					?>



			<?php

	}



}

<?php
/**
 * The widget-specific functionality of the plugin for random ads.
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
class Wpadcenter_Random_Ad_Widget extends \WP_Widget {

	/**
	 * Wpadcenter_Widget constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$id_base        = 'Wpadcenter_Random_Ad_Widget';
		$name           = 'WPAdCenter Random Ad';
		$widget_options = array( 'description' => __( 'Display Random Ad from Adgroups', 'wpadcenter' ) );
		parent::__construct( $id_base, $name, $widget_options );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpadcenter_random_ad_widget_enqueue_script' ) );

	}

	/**
	 * Enqueues script.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_random_ad_widget_enqueue_script() {

		wp_enqueue_script( 'wpadcenter' );
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
		$alignment   = empty( $instance['alignment'] ) ? 1 : $instance['alignment'];
		$max_width   = empty( $instance['max_width'] ) ? 'off' : $instance['max_width'];

		if ( 'on' === $max_width ) {
			$max_width = true;
		} else {
			$max_width = false;
		}
		$max_width_px = empty( $instance['max_width_px'] ) ? 1 : $instance['max_width_px'];

		echo $before_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $before_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped		

		$attributes = array(
			'adgroup_ids'  => $adgroup_ids,
			'align'        => $alignment,
			'max_width'    => $max_width,
			'max_width_px' => $max_width_px,
		);
		echo Wpadcenter_Public::display_random_ad( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
		$adgroup_ids  = empty( $instance['adgroup_ids'] ) ? array() : $instance['adgroup_ids'];
		$title        = empty( $instance['title'] ) ? '' : $instance['title'];
		$alignment    = empty( $instance['alignment'] ) ? 'alignnone' : $instance['alignment'];
		$max_width    = empty( $instance['max_width'] ) ? 'off' : $instance['max_width'];
		$max_width_px = empty( $instance['max_width_px'] ) ? '100' : $instance['max_width_px'];

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
				<p>
				<label for="<?php echo esc_html( $this->get_field_id( 'max_width' ) ); ?>"><?php echo esc_html__( 'Enable Max Width', 'wpadcenter' ); ?></label>
				<input
					type="checkbox"
					class="wpadcenter_random_ad_widget_max_width_check"
					name="<?php echo esc_html( $this->get_field_name( 'max_width' ) ); ?>"
					id="<?php echo esc_html( $this->get_field_id( 'max_width' ) ); ?>"
					<?php checked( $max_width, 'on' ); ?>
				>			
			</p>		
				<p class="wpadcenter_random_ad_widget_max_width_px" 
				<?php
				if ( 'on' !== $max_width ) {
					?>
				style="display:none"

					<?php
				}
				?>
				>
				<label for="<?php echo esc_html( $this->get_field_id( 'max_width_px' ) ); ?>"><?php echo esc_html__( 'Max Width : ', 'wpadcenter' ); ?></label>
				<input
					type="number"
					name="<?php echo esc_html( $this->get_field_name( 'max_width_px' ) ); ?>"
					id="<?php echo esc_html( $this->get_field_id( 'max_width_px' ) ); ?>"
					value="<?php echo esc_html( $max_width_px ); ?>"
				>
				</p>	


			<?php

	}



}

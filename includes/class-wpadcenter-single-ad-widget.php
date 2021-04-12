<?php
/**
 * The widget-specific functionality of the plugin.
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * The widget-specific functionality of the plugin.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 * @author     WPEka Club <support@wpeka.com>
 */
class Wpadcenter_Single_Ad_Widget extends \WP_Widget {

	/**
	 * Wpadcenter_Widget constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$id_base        = 'Wpadcenter_Single_Ad_Widget';
		$name           = 'WPAdCenter Single Ads';
		$widget_options = array( 'description' => __( 'Display single ads in a Widget', 'wpadcenter' ) );
		parent::__construct( $id_base, $name, $widget_options );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpadcenter_singlead_widget_enqueue_script' ) );

	}

	/**
	 * Enqueues script.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_singlead_widget_enqueue_script() {

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

		$title = empty( $instance['title'] ) ? '' : $instance['title'];

		echo $before_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $before_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( 'on' === $instance['max_width'] ) {
			$instance['max_width'] = true;
		} else {
			$instance['max_width'] = false;
		}
		$attributes = array(
			'max_width'    => $instance['max_width'],
			'max_width_px' => $instance['max_width_px'],
		);
		echo Wpadcenter_Public::display_single_ad( $instance['ad_id'], $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
		$ad_id        = empty( $instance['ad_id'] ) ? '' : $instance['ad_id'];
		$title        = empty( $instance['title'] ) ? '' : $instance['title'];
		$max_width    = empty( $instance['max_width'] ) ? 'off' : $instance['max_width'];
		$max_width_px = empty( $instance['max_width_px'] ) ? '100' : $instance['max_width_px'];

		$single_ads = array();

		$current_time = time();

		$args = array(
			'post_type'   => 'wpadcenter-ads',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_query'  => array(// phpcs:ignore
				array(
					'key'     => 'wpadcenter_end_date',
					'value'   => $current_time,
					'type'    => 'numeric',
					'compare' => '>=',
				),
			),
		);

		$ads = get_posts( $args );

		if ( $ads ) {
			?>
			<p>
				<label for="title"><?php echo esc_html__( 'Title : ', 'wpadcenter' ); ?></label>
				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php
			foreach ( $ads as $ad ) {
				$single_ads[ $ad->ID ] = $ad->post_title;
			}
			?>
			<p>
				<label for="ad_id"><?php echo esc_html__( 'Select Ad : ', 'wpadcenter' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ad_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ad_id' ) ); ?>">
					<?php $this->print_combobox_options( $single_ads, $ad_id ); ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_html( $this->get_field_id( 'max_width' ) ); ?>"><?php echo esc_html__( 'Enable Max Width', 'wpadcenter' ); ?></label>
				<input
					type="checkbox"
					class="wpadcenter_singlead_widget_max_width_check"
					name="<?php echo esc_html( $this->get_field_name( 'max_width' ) ); ?>"
					id="<?php echo esc_html( $this->get_field_id( 'max_width' ) ); ?>"
					<?php checked( $max_width, 'on' ); ?>
				>			
			</p>		
				<p class="wpadcenter_singlead_widget_max_width_px" 
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

		} else {
			?>
				<p>
					No Ads Found.
				</p>
			<?php
		}

	}

	/**
	 * Prints a combobox based on options and selected=match value.
	 *
	 * @param array  $options Array of options.
	 * @param string $selected Which of those options should be selected (allows just one; is case sensitive).
	 *
	 * @since 1.0.0
	 */
	public function print_combobox_options( $options, $selected ) {
		foreach ( $options as $key => $value ) {
			echo '<option value="' . esc_html( $key ) . '"';
			if ( $key === (int) $selected ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $value ) . '</option>';
		}
	}


}

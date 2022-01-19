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

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

	}

	/**
	 * Enqueues scripts.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {
		wp_enqueue_style( 'wpadcenter-frontend', plugin_dir_url( __DIR__ ) . 'public/css/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.css', array(), '5.0.1', 'all' );
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

		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		// the below phpcs comments are added after referring the core widget codes.
		echo $before_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $before_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html( $title );
		echo $after_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( isset( $instance['max_width'] ) && 'on' === $instance['max_width'] ) {
			$instance['max_width'] = true;
		} else {
			$instance['max_width'] = false;
		}
		$instance['max_width_px'] = isset( $instance['max_width_px'] ) ? $instance['max_width_px'] : '100';
		$instance['ad_id']        = isset( $instance['ad_id'] ) ? $instance['ad_id'] : '0';

		if ( isset( $instance['devices'] ) ) {
			$key = array_search( 'set', $instance['devices'], true );
			if ( false !== $key ) {
				unset( $instance['devices'][ $key ] );
			}
		} else {
			$instance['devices'] = array( 'mobile', 'tablet', 'desktop' );
		}

		$attributes = array(
			'max_width'    => $instance['max_width'],
			'max_width_px' => $instance['max_width_px'],
			'devices'      => $instance['devices'],
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
		$ad_id        = isset( $instance['ad_id'] ) ? $instance['ad_id'] : '';
		$title        = isset( $instance['title'] ) ? $instance['title'] : '';
		$max_width    = isset( $instance['max_width'] ) ? $instance['max_width'] : 'off';
		$max_width_px = isset( $instance['max_width_px'] ) ? $instance['max_width_px'] : '100';
		$devices      = isset( $instance['devices'] ) ? $instance['devices'] : array( 'mobile', 'tablet', 'desktop' );

		$single_ads = array();

		$current_time = time();

		$args = array(
			'post_type'   => 'wpadcenter-ads',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_query'  => array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
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
			<script>
			(function ($) {
			'use strict';
			$('.wpadcenter_singlead_widget_max_width_check').change(function(){
				$('.wpadcenter_singlead_widget_max_width_px').toggle();
			});
			})( jQuery );
			</script>
			<p>
				<label for="specificDevices"><?php echo esc_html__( 'Display on specific devices : ', 'wpadcenter' ); ?></label>
			</p>
			<p>
			<ul class="wpadcenter-specific-devices-container">
			<li class="wpadcenter-specific-devices__item">
			<input type="checkbox"
			name="<?php echo esc_html( $this->get_field_name( 'devices' ) ) . '[]'; ?>"
			value="mobile" 
			<?php echo in_array( 'mobile', $devices, true ) ? 'checked' : ''; ?> 
			/>
			<span class="dashicons dashicons-smartphone"></span>
			<span class="wpadcenter-specific-devices__label">Mobile</span>
			</li>

			<li class="wpadcenter-specific-devices__item">
			<input type="checkbox"
			name="<?php echo esc_html( $this->get_field_name( 'devices' ) ) . '[]'; ?>"
			value="tablet" 
			<?php echo in_array( 'tablet', $devices, true ) ? 'checked' : ''; ?> 
			/>
			<span class="dashicons dashicons-tablet"></span>
			<span class="wpadcenter-specific-devices__label">Tablet</span>

			</li>

			<li class="wpadcenter-specific-devices__item">
			<input type="checkbox"
			name="<?php echo esc_html( $this->get_field_name( 'devices' ) ) . '[]'; ?>" 
			value="desktop" 
			<?php echo in_array( 'desktop', $devices, true ) ? 'checked' : ''; ?> 
			/>
			<span class="dashicons dashicons-desktop"></span>
			<span class="wpadcenter-specific-devices__label">Desktop</span>

			</li>
			<input type="hidden"
			name="<?php echo esc_html( $this->get_field_name( 'devices' ) ) . '[]'; ?>" 
			value="set" 
			checked 
			/>

			</ul>
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

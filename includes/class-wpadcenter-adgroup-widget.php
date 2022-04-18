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
		$name           = 'WPAdCenter Grouped Ads';
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

		$adgroup_ids = isset( $instance['adgroup_ids'] ) ? $instance['adgroup_ids'] : '';
		$title       = isset( $instance['title'] ) ? $instance['title'] : '';
		$num_ads     = isset( $instance['num_ads'] ) ? $instance['num_ads'] : 1;
		$num_columns = isset( $instance['num_columns'] ) ? $instance['num_columns'] : 1;
		$alignment   = isset( $instance['alignment'] ) ? $instance['alignment'] : 'alignnone';
		$max_width   = isset( $instance['max_width'] ) ? $instance['max_width'] : 'off';

		if ( 'on' === $max_width ) {
			$max_width = true;
		} else {
			$max_width = false;
		}
		$max_width_px = isset( $instance['max_width_px'] ) ? $instance['max_width_px'] : '100';
		if ( isset( $instance['devices'] ) ) {
			$key = array_search( 'set', $instance['devices'], true );
			if ( false !== $key ) {
				unset( $instance['devices'][ $key ] );
			}
		} else {
			$instance['devices'] = array( 'mobile', 'tablet', 'desktop' );
		}

		// the below phpcs comments are added after referring the core widget codes.
		echo $before_widget;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $before_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html( $title );
		echo $after_title;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped		

		$attributes = array(
			'adgroup_ids'  => $adgroup_ids,
			'align'        => $alignment,
			'num_ads'      => $num_ads,
			'num_columns'  => $num_columns,
			'max_width'    => $max_width,
			'max_width_px' => $max_width_px,
			'devices'      => $instance['devices'],

		);
		echo '<div class="wpadcenter-adgroup-widget-container">';
		echo Wpadcenter_Public::display_adgroup_ads( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
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
		$adgroup_ids  = isset( $instance['adgroup_ids'] ) ? $instance['adgroup_ids'] : array();
		$title        = isset( $instance['title'] ) ? $instance['title'] : '';
		$num_ads      = isset( $instance['num_ads'] ) ? $instance['num_ads'] : 1;
		$num_columns  = isset( $instance['num_columns'] ) ? $instance['num_columns'] : 1;
		$alignment    = isset( $instance['alignment'] ) ? $instance['alignment'] : 'alignnone';
		$max_width    = isset( $instance['max_width'] ) ? $instance['max_width'] : 'off';
		$max_width_px = isset( $instance['max_width_px'] ) ? $instance['max_width_px'] : '100';
		$devices      = isset( $instance['devices'] ) ? $instance['devices'] : array( 'mobile', 'tablet', 'desktop' );

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
						<input type="radio" id="<?php echo esc_attr( $this->get_field_id( $value ) ); ?>" 
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
					class="wpadcenter_adgroup_widget_max_width_check"
					name="<?php echo esc_html( $this->get_field_name( 'max_width' ) ); ?>"
					id="<?php echo esc_html( $this->get_field_id( 'max_width' ) ); ?>"
					<?php checked( $max_width, 'on' ); ?>
				>			
			</p>		
				<p class="wpadcenter_adgroup_widget_max_width_px" 
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
			$('.wpadcenter_adgroup_widget_max_width_check').change(function(){
				$('.wpadcenter_adgroup_widget_max_width_px').toggle();
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

	}



}

<?php
/**
 * Class for registering single ad elementor widget.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Class for registering single ad elementor widget.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Elementor_SingleAd_Widget extends \Elementor\Widget_Base {

	/**
	 * Name slug for widget
	 */
	public function get_name() {
		return 'wpadcenter-single-ad';
	}

	/**
	 * Title for widget
	 */
	public function get_title() {
		return 'WPAdCenter Single Ad';
	}

	/**
	 * Register icon for widget
	 */
	public function get_icon() {
		return 'fas fa-sign';
	}

	/**
	 * Group to associate widget with
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register controls for the widget
	 */
	protected function _register_controls() { // phpcs:ignore

		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'WPAdCenter Single Ad', 'wpadcenter' ),
			)
		);

		$options = $this->get_select_ads_options();

		$this->add_control(
			'ad_id',
			array(
				'label'   => __( 'Select Ad', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => array_keys( $options )[0],
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'   => __( 'Alignment', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'alignleft'   => array(
						'title' => __( 'Left', 'wpadcenter' ),
						'icon'  => 'fa fa-align-left',
					),
					'aligncenter' => array(
						'title' => __( 'Center', 'wpadcenter' ),
						'icon'  => 'fa fa-align-center',
					),
					'alignright'  => array(
						'title' => __( 'Right', 'wpadcenter' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'alignleft',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Provides the list of ads
	 *
	 * @return array $options contains list of ads.
	 */
	public function get_select_ads_options() {
		$options      = array();
		$current_time = time();
		$args         = array(
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

			foreach ( $ads as $ad ) {

				$options[ $ad->ID ] = $ad->post_title;

			}
		}
		return $options;
	}

	/**
	 * Renders ad
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$ad_id      = $settings['ad_id'];
		$attributes = array(
			'classes' => $settings['alignment'],

		);

		echo Wpadcenter_Public::display_single_ad( $ad_id, $attributes ); // phpcs:ignore

	}


}

<?php
/**
 * Class for registering adgroup elementor widget.
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
 * Class for registering adgroup elementor widget.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Elementor_Adgroup_Widget extends \Elementor\Widget_Base {

	/**
	 * Name slug for widget
	 */
	public function get_name() {
		return 'wpadcenter-adgroup';
	}

	/**
	 * Title for widget
	 */
	public function get_title() {
		return 'WPAdCenter Adgroup';
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
				'label' => __( 'WPAdCenter Adgroup', 'wpadcenter' ),
			)
		);

		$adgroup_options = $this->get_adgroup_options();

		$this->add_control(
			'adgroup_ids',
			array(
				'label'    => __( 'Select Ad Groups', 'wpadcenter' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $adgroup_options,
				'default'  => array_keys( $adgroup_options )[0],
			)
		);

		$this->add_control(
			'num_ads',
			array(
				'label'   => __( 'Number of Ads', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'step'    => 1,
				'default' => 1,
			)
		);

		$this->add_control(
			'num_columns',
			array(
				'label'   => __( 'Number of Columns', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'step'    => 1,
				'default' => 1,
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
	 * Provides the list of adgroups
	 *
	 * @return array $adgroup_options contains list of adgroups.
	 */
	public function get_adgroup_options() {
		$terms           = get_terms(
			array(
				'taxonomy' => 'wpadcenter-adgroups',
			)
		);
		$adgroup_options = array();
		foreach ( $terms as $term ) {
			$adgroup_options[ $term->term_id ] = $term->name;
		}
		return $adgroup_options;
	}

	/**
	 * Renders ad
	 */
	protected function render() {
				$settings = $this->get_settings_for_display();

		$attributes = array(
			'adgroup_ids' => $settings['adgroup_ids'],
			'align'       => $settings['alignment'],
			'num_ads'     => $settings['num_ads'],
			'num_columns' => $settings['num_columns'],
		);

		echo Wpadcenter_Public::display_adgroup_ads( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}


}

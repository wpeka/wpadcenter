<?php
/**
 * Class for registering random ad elementor widget.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;


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
class Wpadcenter_Elementor_RandomAd_Widget extends \Elementor\Widget_Base {

	/**
	 * Name slug for widget
	 */
	public function get_name() {
		return 'wpadcenter-random-ad';
	}

	/**
	 * Title for widget
	 */
	public function get_title() {
		return 'Random Ads (Deprecated)';
	}

	/**
	 * Register icon for widget
	 */
	public function get_icon() {
		return 'icon-adcenter';
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
	protected function register_controls() { // phpcs:ignore

		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'WPAdCenter Random Ads (Deprecated)', 'wpadcenter' ),
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

		$this->add_control(
			'max_width',
			array(
				'label'        => __( 'Enable Max Width', 'wpadcenter' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'wpadcenter' ),
				'label_off'    => __( 'Off', 'wpadcenter' ),
				'return_value' => 'on',
				'default'      => 'off',
			)
		);
		$this->add_control(
			'max_width_px',
			array(
				'label'   => __( 'Max Width', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'step'    => 1,
				'default' => 100,
			)
		);
		$this->add_control(
			'devices',
			array(
				'label'       => __( 'Display on Specific Devices', 'wpadcenter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => __( 'Display settings will take effect only on preview or live page, and not while editing in Elementor.', 'wpadcenter' ),
				'multiple'    => true,
				'options'     => array(
					'mobile'  => __( 'Mobile', 'wpadcenter' ),
					'tablet'  => __( 'Tablet', 'wpadcenter' ),
					'desktop' => __( 'Desktop', 'wpadcenter' ),
				),
				'default'     => array( 'mobile', 'tablet', 'desktop' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders Help Link
	 */
	public function get_custom_help_url() {
		return 'https://docs.wpeka.com/wp-adcenter/placing-ads/placing-ad-using-consolidated-block-elementor-widget';
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

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$settings['devices'] = array( 'mobile', 'tablet', 'desktop' );
		}
		if ( 'on' === $settings['max_width'] ) {
					$settings['max_width'] = true;
		} else {
					$settings['max_width'] = false;
		}

		$attributes = array(
			'adgroup_ids'  => $settings['adgroup_ids'],
			'align'        => $settings['alignment'],
			'max_width'    => $settings['max_width'],
			'max_width_px' => $settings['max_width_px'],
			'devices'      => $settings['devices'],

		);

		echo Wpadcenter_Public::display_random_ad( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}


}

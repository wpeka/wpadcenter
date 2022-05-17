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
use Elementor\Plugin;

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
class Wpadcenter_Elementor_AdTypes_Widget extends \Elementor\Widget_Base {

	/**
	 * Construct function of class.
	 *
	 * @param array $data data to be passed to parent class.
	 * @param array $args args to be passed to parent class.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_script( 'wpadcenter-elementor', plugin_dir_url( __FILE__ ) . 'wpadcenter-elementor-rotating-ad' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'elementor-frontend' ), '2.0.0', true );
	}

	/**
	 * Adds script to the list which elementor enqueues.
	 */
	public function get_script_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array( 'wpadcenter-elementor' );
		}
		return array();
	}

	/**
	 * Name slug for widget
	 */
	public function get_name() {
		return 'wpadcenter-adtype';
	}

	/**
	 * Title for widget
	 */
	public function get_title() {
		return 'Ad Widget';
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

		$repeater = new \Elementor\Repeater();

		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'WPAdCenter Ad Widget', 'wpadcenter' ),
			)
		);

		$options              = $this->get_select_ads_options();
		$ad_type_options      = $this->get_ad_type_options();
		$adgroup_options      = $this->get_adgroup_options();
		$display_type_options = array();
		$display_type_options = apply_filters( 'wpadcenter_get_display_type_options', $display_type_options );
		$this->add_control(
			'ad_type',
			array(
				'label'   => __( 'Select Ad Type', 'wpadcenter' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $ad_type_options,
				'default' => 'singlead',
			)
		);

		$this->add_control(
			'display_type',
			array(
				'label'     => __( 'Select Display Animation Type', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $display_type_options,
				'default'   => 'carousel',
				'condition' => array(
					'ad_type' => array( 'animatedad' ),
				),
			)
		);

		$this->add_control(
			'ad_id',
			array(
				'label'     => __( 'Select Ad', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $options,
				'condition' => array(
					'ad_type' => array( 'singlead' ),
				),
			)
		);

		$this->add_control(
			'adgroup_ids',
			array(
				'label'     => __( 'Select Ad Groups', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $adgroup_options,
				'condition' => array(
					'ad_type' => array( 'randomad', 'adgroup' ),
				),
			)
		);

		$this->add_control(
			'adgroup_id',
			array(
				'label'     => __( 'Select Adgroup', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $adgroup_options,
				'condition' => array(
					'ad_type' => array( 'rotatingad' ),
				),
			)
		);

		$repeater->add_control(
			'multiple_ad_id',
			array(
				'label'    => __( 'Select Ad', 'wpadcenter' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options'  => $options,
			)
		);

		$this->add_control(
			'list',
			array(
				'label'     => __( 'Select Ads', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'condition' => array(
					'ad_type' => array( 'orderedad', 'animatedad' ),
				),
			)
		);

		$this->add_control(
			'time',
			array(
				'label'     => __( 'Time', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 10,
				'condition' => array(
					'ad_type' => array( 'rotatingad' ),
				),
			)
		);

		$this->add_control(
			'order_randomly',
			array(
				'label'        => __( 'Order randomly', 'wpadcenter' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'wpadcenter' ),
				'label_off'    => __( 'Off', 'wpadcenter' ),
				'return_value' => 'on',
				'default'      => 'off',
				'condition'    => array(
					'ad_type' => array( 'rotatingad' ),
				),
			)
		);

		$this->add_control(
			'num_ads',
			array(
				'label'     => __( 'Number of Ads', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 1,
				'condition' => array(
					'ad_type' => array( 'adgroup' ),
				),
			)
		);

		$this->add_control(
			'num_columns',
			array(
				'label'     => __( 'Number of Columns', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 1,
				'condition' => array(
					'ad_type'       => array( 'adgroup', 'orderedad', 'animatedad' ),
					'display_type!' => array( 'scrollbar-top', 'scrollbar-bottom' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'wpadcenter' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'alignleft',
				'condition' => array(
					'ad_type!' => array( 'animatedad' ),
				),
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

			foreach ( $ads as $ad ) {

				$options[ $ad->ID ] = $ad->post_title;

			}
		}
		return $options;
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
	 * Provides the list of display types
	 *
	 * @return array $display_type_options contains list of display types.
	 */
	public function get_ad_type_options() {

		$ad_type_options = array(
			'singlead' => __( 'Single Ad', 'wpadcenter' ),
			'adgroup'  => __( 'AdGroup', 'wpadcenter' ),
			'randomad' => __( 'Random Ad', 'wpadcenter' ),
		);
		$ad_type_options = apply_filters( 'wpadcenter_elementor_ad_type_options', $ad_type_options );
		return $ad_type_options;
	}

	/**
	 * Renders ad
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$ad_type  = $settings['ad_type'];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$settings['devices'] = array( 'mobile', 'tablet', 'desktop' );
			if ( 'animatedad' === $ad_type ) {
				?>
			<div style="border:2px solid black;padding:5px;"><h3>
				<?php
				esc_html_e( 'Preview for animated ads is not availble in the editor, it can be seen on the preview or live page.', 'wpadcenter' );
				?>
			</h3></div>
				<?php
				return;
			}
		}

		if ( 'on' === $settings['max_width'] ) {
			$settings['max_width'] = true;
		} else {
			$settings['max_width'] = false;
		}
		if ( 'singlead' === $ad_type ) {
			$ad_id      = $settings['ad_id'];
			$attributes = array(
				'align'        => $settings['alignment'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
			);
			echo Wpadcenter_Public::display_single_ad( $ad_id, $attributes ); // phpcs:ignore
		} elseif ( 'randomad' === $ad_type ) {
			$attributes = array(
				'adgroup_ids'  => $settings['adgroup_ids'],
				'align'        => $settings['alignment'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
			);
			echo Wpadcenter_Public::display_random_ad( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	
		} elseif ( 'adgroup' === $ad_type ) {
			$attributes = array(
				'adgroup_ids'  => $settings['adgroup_ids'],
				'align'        => $settings['alignment'],
				'num_ads'      => $settings['num_ads'],
				'num_columns'  => $settings['num_columns'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
			);
			echo Wpadcenter_Public::display_adgroup_ads( $attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( 'orderedad' === $ad_type ) {
			$ad_ids = array();
			foreach ( $settings['list'] as $ad ) {
				array_push( $ad_ids, $ad['multiple_ad_id'] );
			}
			$ordered_ads_atts = array(
				'ad_ids'       => $ad_ids,
				'align'        => $settings['alignment'],
				'num_columns'  => $settings['num_columns'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
			);
			echo apply_filters( 'wpadcenter_display_elementor_ads', $ad_type, $ordered_ads_atts );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( 'animatedad' === $ad_type ) {
			$ad_ids = array();
			foreach ( $settings['list'] as $ad ) {
				array_push( $ad_ids, $ad['multiple_ad_id'] );
			}
			$animated_ads_atts = array(
				'ad_ids'       => $ad_ids,
				'num_columns'  => $settings['num_columns'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
				'display_type' => $settings['display_type'],
			);

			echo apply_filters( 'wpadcenter_display_elementor_ads', $ad_type, $animated_ads_atts );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		} elseif ( 'rotatingad' === $ad_type ) {
			$adgroup_id = $settings['adgroup_id'];
			$attributes = array(
				'time'         => $settings['time'],
				'align'        => $settings['alignment'],
				'order'        => $settings['order_randomly'],
				'max_width'    => $settings['max_width'],
				'max_width_px' => $settings['max_width_px'],
				'devices'      => $settings['devices'],
			);
			echo apply_filters( 'wpadcenter_display_elementor_ads', $ad_type, $attributes, $adgroup_id );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}
	}


}

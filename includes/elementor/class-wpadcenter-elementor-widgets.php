<?php
/**
 * Class for registering elementor widgets.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 */

/**
 * Class for registering elementor widgets.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Elementor_Widgets {

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Instance    $instance    Instance of the class.
	 */
	protected static $instance = null;

	/**
	 * Returns instace of the class
	 *
	 * @since 1.0.0
	 *
	 * @return Instance $instance instance of the class
	 */
	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Fires hooks to register widget
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		require_once plugin_dir_path( __DIR__ ) . 'elementor/class-wpadcenter-elementor-singlead-widget.php';
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}

	/**
	 * Registers widget
	 *
	 * @since 1.0.0
	 */
	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpadcenter_Elementor_SingleAd_Widget() );
	}

}

add_action( 'init', 'wpadcenter_elementor_init' );

	/**
	 * Initializes class
	 *
	 * @since 1.0.0
	 */
function wpadcenter_elementor_init() {
	Wpadcenter_Elementor_Widgets::get_instance();
}

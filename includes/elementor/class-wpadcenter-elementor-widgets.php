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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


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
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

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

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );

	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', array( $this, 'init' ) );
		}

	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			return false;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Add Plugin actions.
		require_once plugin_dir_path( __DIR__ ) . 'elementor/class-wpadcenter-elementor-singlead-widget.php';
		require_once plugin_dir_path( __DIR__ ) . 'elementor/class-wpadcenter-elementor-adgroup-widget.php';
		require_once plugin_dir_path( __DIR__ ) . 'elementor/class-wpadcenter-elementor-randomad-widget.php';
		require_once plugin_dir_path( __DIR__ ) . 'elementor/class-wpadcenter-elementor-adtypes-widget.php';

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );

	}

	/**
	 * Registers widget
	 *
	 * @since 1.0.0
	 */
	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpadcenter_Elementor_SingleAd_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpadcenter_Elementor_Adgroup_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpadcenter_Elementor_RandomAd_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpadcenter_Elementor_AdTypes_Widget() );

	}

}


	Wpadcenter_Elementor_Widgets::get_instance();

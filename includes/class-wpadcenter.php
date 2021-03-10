<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Wpadcenter_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The currently stored option settings of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array $stored_options The stored option settings of the plugin.
	 */
	private static $stored_options = array();

	const TOP = '# WPAdCenter ads.txt';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPADCENTER_VERSION' ) ) {
			$this->version = WPADCENTER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpadcenter';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpadcenter_Loader. Orchestrates the hooks of the plugin.
	 * - Wpadcenter_i18n. Defines internationalization functionality.
	 * - Wpadcenter_Admin. Defines all hooks for the admin area.
	 * - Wpadcenter_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpadcenter-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpadcenter-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-google-api.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-adsense.php';

		$this->loader = new Wpadcenter_Loader();

		/**
		 * Helper class for admin functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpadcenter-admin-helper.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpadcenter_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpadcenter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpadcenter_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'wpadcenter_register_cpt' );
		$this->loader->add_action( 'init', $plugin_admin, 'wpadcenter_register_taxonomy' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wpadcenter_admin_menu' );
		$this->loader->add_action( 'manage_edit-wpadcenter-ads_columns', $plugin_admin, 'wpadcenter_manage_edit_ads_columns' );
		$this->loader->add_action( 'manage_edit-wpadcenter-adgroups_columns', $plugin_admin, 'wpadcenter_manage_edit_adgroups_columns' );
		$this->loader->add_filter( 'plugin_action_links_' . WPADCENTER_PLUGIN_BASENAME, $plugin_admin, 'wpadcenter_plugin_action_links' );
		$this->loader->add_action( 'wp_ajax_check_ads_txt_problems', $plugin_admin, 'wpadcenter_check_ads_txt_problems' );
		$this->loader->add_action( 'wp_ajax_check_ads_txt_replace', $plugin_admin, 'wpadcenter_check_ads_txt_replace' );
		$this->loader->add_filter( 'wpadcenter_after_save_settings', $plugin_admin, 'wpadcenter_after_save_settings' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'wpadcenter_edit_form_after_title' );
		$this->loader->add_action( 'add_meta_boxes_wpadcenter-ads', $plugin_admin, 'wpadcenter_add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wpadcenter_save_ad_meta' );
		$this->loader->add_action( 'post_submitbox_start', $plugin_admin, 'wpadcenter_post_submitbox_start' );
		$this->loader->add_filter( 'manage_wpadcenter-ads_posts_custom_column', $plugin_admin, 'wpadcenter_manage_ads_column_values', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpadcenter_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'wpadcenter_init' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Wpadcenter_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns default settings.
	 * If you override the settings here, be ultra careful to use escape characters.
	 *
	 * @param string $key Return default settings for particular key.
	 * 
	 * @since 1.0.0
	 * 
	 * @return mixed|void
	 */
	public static function wpadcenter_get_default_settings( $key = '' ) {
		$settings = array(
			// General settings.
			'notification'             => false,

			'auto_refresh'             => false,
			'transition_effect'        => 'none',
			'transition_speed'         => '500',
			'transition_delay'         => '1000',

			'adblock_detector'         => false,
			'adblock_detected_message' => 'We have noticed that you have an adblocker enabled which restricts ads served on the site.',

			'enable_ads_txt'           => false,
			'ads_txt_content'          => '',
			'enable_scripts'           => false,
			'header_scripts'           => '',
			'body_scripts'             => '',
			'footer_scripts'           => '',

			'enable_advertisers'       => false,

			'geo_location'             => 'none',
			'trim_stats'               => '0',
			'hide_ads_logged'          => false,
		);
		$settings = apply_filters( 'wpadcenter_default_settings', $settings );
		return '' !== $key ? $settings[ $key ] : $settings;
	}

	/**
	 * Returns sanitised content based on field-specific rules defined here
	 * used for both read AND write operations.
	 * 
	 * @param string $key Key for the setting.
	 * @param string $value Value for the setting.
	 *
	 * @return bool|null|string
	 */
	public static function wpadcenter_sanitise_settings( $key, $value ) {
		$ret = null;
		switch ( $key ) {
			// Convert all boolean values from text to bool.
			case 'notification':
			case 'auto_refresh':
			case 'adblock_detector':
			case 'enable_scripts':
			case 'enable_advertisers':
			case 'enable_ads_txt':
			case 'hide_ads_logged':
				if ( 'true' === $value || true === $value ) {
					$ret = true;
				} elseif ( 'false' === $value || false === $value ) {
					$ret = false;
				} else {
					// Unexpected value returned from radio button, go fix the HTML.
					// Failover = assign null.
					$ret = 'fffffff';
				}
				break;
			case 'header_scripts':
			case 'body_scripts':
			case 'footer_scripts':
				$ret = trim( stripslashes( $value ) );
				break;
			case 'ads_txt_content':
				$ret = esc_textarea( $value );
				break;
			default:
				$ret = sanitize_text_field( $value );
				break;
		}
		if ( 'fffffff' === $ret ) {
			$ret = false;
		}
		return $ret;
	}

	/**
	 * Get current settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array|mixed
	 */
	public static function wpadcenter_get_settings() {
		$settings             = self::wpadcenter_get_default_settings();
		self::$stored_options = get_option( WPADCENTER_SETTINGS_FIELD );
		if ( ! empty( self::$stored_options ) ) {
			foreach ( self::$stored_options as $key => $option ) {
				$settings[ $key ] = self::wpadcenter_sanitise_settings( $key, $option );
			}
		}
		update_option( WPADCENTER_SETTINGS_FIELD, $settings );
		return $settings;
	}

	/**
	 * Generate tab head for settings page,
	 * method will translate the string to current language.
	 *
	 * @param array $title_arr Tab labels.
	 */
	public static function wpadcenter_generate_settings_tabhead( $title_arr ) {
		foreach ( $title_arr as $k => $v ) {
			if ( is_array( $v ) ) {
				$v = ( isset( $v[2] ) ? $v[2] : '' ) . esc_attr( $v[0] ) . ' ' . ( isset( $v[1] ) ? $v[1] : '' );
			} else {
				$v = esc_attr( $v );
			}
			?>
			<a class="nav-tab" href="#<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></a>
			<?php
		}
	}

	/**
	 * Envelope settings tab content with tab div.
	 *
	 * @param string $view_file View file.
	 * @param string $target_id Target tab id.
	 */
	public static function wpadcenter_envelope_settings_tab( $view_file = '', $target_id ) {
		$the_options = self::wpadcenter_get_settings();
		?>
		<div class="wpadcenter-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
			<?php
			if ( '' !== $view_file && file_exists( $view_file ) ) {
				include_once $view_file;
			}
			include plugin_dir_path( WPADCENTER_PLUGIN_FILENAME ) . 'admin/views/admin-display-save-button.php';
			?>
		</div>
		<?php
	}

	/**
	 * Connect to the filesystem.
	 *
	 * @param array $directories                  A list of directories. If any of these do
	 *                                            not exist, a WP_Error object will be returned.
	 * 
	 * @return bool|WP_Error True if able to connect, false or a WP_Error otherwise.
	 */
	public static function fs_connect( $directories = array() ) {
		global $wp_filesystem;
		$directories = ( is_array( $directories ) && count( $directories ) ) ? $directories : array( WP_CONTENT_DIR );

		// This will output a credentials form in event of failure, We don't want that, so just hide with a buffer.
		ob_start();
		$credentials = request_filesystem_credentials( '', '', false, $directories[0] );
		ob_end_clean();

		if ( false === $credentials ) {
			return false;
		}

		if ( ! WP_Filesystem( $credentials ) ) {
			$error = true;
			if ( is_object( $wp_filesystem ) && $wp_filesystem->errors->get_error_code() ) {
				$error = $wp_filesystem->errors;
			}
			// Failed to connect, Error and request again.
			ob_start();
			request_filesystem_credentials( '', '', $error, $directories[0] );
			ob_end_clean();
			return false;
		}

		if ( ! is_object( $wp_filesystem ) ) {
			return new WP_Error( 'fs_unavailable', __( 'Could not access filesystem.', 'wpadcenter' ) );
		}

		if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
			return new WP_Error( 'fs_error', __( 'Filesystem error.', 'wpadcenter' ), $wp_filesystem->errors );
		}

		foreach ( (array) $directories as $dir ) {
			switch ( $dir ) {
				case ABSPATH:
					if ( ! $wp_filesystem->abspath() ) {
						return new WP_Error( 'fs_no_root_dir', __( 'Unable to locate WordPress root directory.', 'wpadcenter' ) );
					}
					break;
				case WP_CONTENT_DIR:
					if ( ! $wp_filesystem->wp_content_dir() ) {
						return new WP_Error( 'fs_no_content_dir', __( 'Unable to locate WordPress content directory (wp-content).', 'wpadcenter' ) );
					}
					break;
				default:
					if ( ! $wp_filesystem->find_folder( $dir ) ) {
						return new WP_Error(
							'fs_no_folder',
							sprintf(
								/* translators: %s folder name */
								__( 'Unable to locate needed folder (%s).', 'wpadcenter' ),
								esc_html( basename( $dir ) )
							)
						);
					}
					break;
			}
		}

		return true;
	}
}

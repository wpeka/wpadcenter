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
			$this->version = '2.4.0';
		}
		$this->plugin_name = 'wpadcenter';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

	}

	/**
	 * To enqueue scripts for elementor custom icon.
	 */
	public function enqueue_admin_styles() {
		wp_register_style(
			'adcenter-icon',
			plugin_dir_url( __DIR__ ) . '/admin/css/adcentericon' . WPADCENTER_SCRIPT_SUFFIX . '.css',
			array(),
			$this->version,
			'all'
		);
		wp_enqueue_style( 'adcenter-icon' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpadcenter_Loader. Orchestrates the hooks of the plugin.
	 * - Wpadcenter_I18n. Defines internationalization functionality.
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
		 * The class responsible for defining single ad widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-single-ad-widget.php';

		/**
		 * The class responsible for defining adgroup widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-adgroup-widget.php';

		/**
		 * The class responsible for defining random ad widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-random-ad-widget.php';

		/**
		 * The class responsible for defining single ad elementor widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/class-wpadcenter-elementor-widgets.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpadcenter_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpadcenter_I18n();

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
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpadcenter_pro_admin_init' );
		$this->loader->add_action( 'wpadcenter_monthly_cron', $plugin_admin, 'wpadcenter_monthly_schedule_clean_stats' );
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
		$this->loader->add_filter( 'manage_wpadcenter-adgroups_custom_column', $plugin_admin, 'wpadcenter_manage_ad_groups_column_values', 10, 3 );
		$this->loader->add_action( 'wp_ajax_selected_adgroup_reports', $plugin_admin, 'wpadcenter_ad_group_selected' );
		$this->loader->add_action( 'wp_ajax_selected_ad_reports', $plugin_admin, 'wpadcenter_ad_selected' );
		$this->loader->add_action( 'wp_ajax_selected_test_report', $plugin_admin, 'wpadcenter_test_selected' );
		$this->loader->add_action( 'wp_ajax_get_roles', $plugin_admin, 'wpadcenter_get_roles' );
		$this->loader->add_action( 'wp_ajax_get_adgroups', $plugin_admin, 'wpadcenter_get_adgroups' );
		$this->loader->add_action( 'wp_ajax_get_tests', $plugin_admin, 'wpadcenter_get_tests' );
		$this->loader->add_action( 'wp_ajax_get_placements', $plugin_admin, 'wpadcenter_get_placements' );
		$this->loader->add_action( 'wp_ajax_get_ads', $plugin_admin, 'wpadcenter_get_ads' );
		$this->loader->add_action( 'admin_post_export_csv', $plugin_admin, 'wpadcenter_export_csv' );
		$this->loader->add_filter( 'style_loader_src', $plugin_admin, 'wpadcanter_dequeue_styles' );
		$this->loader->add_filter( 'print_styles_array', $plugin_admin, 'wpadcenter_remove_forms_style' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'wpadcenter_register_widgets' );
		$this->loader->add_action( 'init', $plugin_admin, 'wpadcenter_register_gutenberg_blocks' );
		$this->loader->add_filter( 'block_categories_all', $plugin_admin, 'wpadcenter_gutenberg_block_categories', 10, 1 );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'wpadcenter_register_rest_fields' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wpadcenter_remove_permalink' );
		$this->loader->add_action( 'wp_ajax_wpadcenter_adgroup_gutenberg_preview', $plugin_admin, 'wpadcenter_adgroup_gutenberg_preview' );
		$this->loader->add_action( 'wp_ajax_save_settings', $plugin_admin, 'wpadcenter_settings' );
		$this->loader->add_action( 'wp_ajax_wpadcenter_singlead_gutenberg_preview', $plugin_admin, 'wpadcenter_singlead_gutenberg_preview' );
		$this->loader->add_action( 'wp_ajax_wpadcenter_adtypes_gutenberg_preview', $plugin_admin, 'wpadcenter_adtypes_gutenberg_preview' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'wpadcenter_remove_post_row_actions', 10, 1 );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'wpadcenter_add_custom_filters' );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'wpadcenter_custom_filters_query', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpadcenter_random_ad_gutenberg_preview', $plugin_admin, 'wpadcenter_random_ad_gutenberg_preview' );
		$this->loader->add_action( 'wp_ajax_wpadcenter_pro_display_amp_warning', $plugin_admin, 'wpadcenter_pro_display_amp_warning' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'wpadcenter_mascot_on_pages' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpadcenter_admin_review_notice' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpadcenter_review_already_done', 5 );
		$this->loader->add_action( 'rest_endpoints', $plugin_admin, 'wpadcenter_rest_endpoints_args' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpadcenter_upgrade_to_pro' );
		$this->loader->add_action( 'wp_ajax_upload_html5_file', $plugin_admin, 'wpadcenter_upload_html5_file' );
		$this->loader->add_action( 'before_delete_post', $plugin_admin, 'wpadcenter_on_delete_ad' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpadcenter_blocks_widgets_deprecation_notice' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpadcenter_deprecation_already_done', 5 );
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
		if ( self::is_request( 'frontend' ) ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'init', $plugin_public, 'wpadcenter_init' );
		}
		$this->loader->add_action( 'wp_ajax_set_clicks', $plugin_public, 'wpadcenter_set_clicks' );
		$this->loader->add_action( 'wp_ajax_nopriv_set_clicks', $plugin_public, 'wpadcenter_set_clicks' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_public, 'wpadcenter_register_gutenberg_scripts' );
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
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, cron or frontend.
	 *
	 * @return bool
	 */
	public static function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ! is_admin() && ! defined( 'DOING_AJAX' ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
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
			'enable_notifications'          => false,

			'auto_refresh'                  => false,
			'transition_effect'             => 'none',
			'transition_speed'              => '500',
			'transition_delay'              => '1000',

			'adblock_detector'              => false,
			'adblock_detected_message'      => __( 'We have noticed that you have an adblocker enabled which restricts ads served on the site.', 'wpadcenter' ),
			'geo_targeting'                 => false,
			'maxmind_license_key'           => '',
			'maxmind_db_prefix'             => wp_generate_password( 32, false ),
			'maxmind_db_path'               => '',

			'enable_ads_txt'                => false,
			'ads_txt_content'               => '',
			'enable_scripts'                => false,
			'header_scripts'                => '',
			'body_scripts'                  => '',
			'footer_scripts'                => '',

			'enable_advertisers'            => false,

			'geo_location'                  => 'none',
			'trim_stats'                    => '0',
			'days_to_send_before'           => 1,
			'clicks_to_send_before'         => 100,
			'views_to_send_before'          => 100,
			'hide_ads_logged'               => false,
			'roles_selected'                => '',
			'roles_selected_visibility'     => '',
			'content_ads'                   => false,
			'link_open_in_new_tab'          => false,
			'link_nofollow'                 => false,
			'link_additional_rel_tags'      => '',
			'link_additional_css_class'     => '',
			'enable_affiliate'              => false,
			'cloaked_link_base'             => '',
			'enable_privacy'                => false,
			'consent_method'                => '',
			'cookie_name'                   => '',
			'cookie_value'                  => '',
			'cookie_non_personalized'       => false,

			'enable_click_fraud_protection' => false,
			'click_fraud_num_clicks'        => '10',
			'click_fraud_duration'          => '10',
			'click_fraud_hide_duration'     => '1',

			'enable_global_email'           => false,
			'global_email_recipients'       => '',
			'global_email_subject'          => 'AdCenter Ad Report',
			'global_email_frequency'        => 'Daily',
			'global_email_report_type'      => 'Last 7 days',
			'frequency_message'             => 'The daily report is sent at 9 AM PST',
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
			case 'enable_notifications':
			case 'auto_refresh':
			case 'adblock_detector':
			case 'geo_targeting':
			case 'enable_scripts':
			case 'enable_advertisers':
			case 'enable_ads_txt':
			case 'hide_ads_logged':
			case 'trim_statistics':
			case 'content_ads':
			case 'link_open_in_new_tab':
			case 'link_nofollow':
			case 'enable_privacy':
			case 'cookie_non_personalized':
			case 'enable_global_email':
			case 'enable_affiliate':
			case 'enable_click_fraud_protection':
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
			case 'roles_selected':
			case 'roles_selected_visibility':
			case 'header_scripts':
			case 'body_scripts':
			case 'footer_scripts':
			case 'link_additional_rel_tags':
			case 'link_additional_css_class':
			case 'cloaked_link_base':
			case 'consent_method':
			case 'cookie_value':
			case 'global_email_frequency':
			case 'global_email_subject':
			case 'global_email_recipients':
			case 'global_email_report_type':
			case 'frequency_message':
			case 'cookie_name':
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
	public static function wpadcenter_envelope_settings_tab( $view_file = '', $target_id = '' ) {
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

	/**
	 * Set impressions.
	 *
	 * @param int $ad_id Advertisement ID.
	 * @param int $placement_id Placement ID.
	 */
	public static function wpadcenter_set_impressions( $ad_id, $placement_id = '' ) {
		global $wpdb;
		$meta           = get_post_meta( $ad_id, 'wpadcenter_ads_stats', true );
		$today          = gmdate( 'Y-m-d' );
		$placement_name = '';

		if ( ! empty( $placement_id ) ) {
			$placement_meta = get_option( 'wpadcenter-pro-placements', true );
			foreach ( $placement_meta as $placement ) {
				if ( $placement['id'] === $placement_id ) {
					$placement_name = $placement['name'];
				}
			}

			$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'placements_statistics WHERE placement_date = %s and placement_id = %s', array( $today, $placement_id ) ) ); // db call ok; no-cache ok.
			if ( count( $records ) ) {
				$record      = $records[0];
				$impressions = $record->placement_impressions + 1;
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'placements_statistics SET placement_impressions = %d WHERE placement_date = %s and placement_id = %d', array( $impressions, $today, $placement_id ) ) ); // db call ok; no-cache ok.
			} else {
				$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'placements_statistics` (`placement_impressions`, `placement_date`, `placement_name`, `placement_id`) VALUES (%d,%s,%s,%s)', array( 1, $today, $placement_name, $placement_id ) ) ); // db call ok; no-cache ok.
			}
		}

		$meta['total_impressions']++;
		$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, $ad_id ) ) ); // db call ok; no-cache ok.
		if ( count( $records ) ) {
			$record      = $records[0];
			$impressions = $record->ad_impressions + 1;
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'ads_statistics SET ad_impressions = %d WHERE ad_date = %s and ad_id = %d', array( $impressions, $today, $ad_id ) ) ); // db call ok; no-cache ok.
			do_action( 'wp_adcenter_after_set_impressions', $impressions );
		} else {
			$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'ads_statistics` (`ad_impressions`, `ad_date`, `ad_id`) VALUES (%d,%s,%d)', array( 1, $today, $ad_id ) ) ); // db call ok; no-cache ok.
		}

		update_post_meta( $ad_id, 'wpadcenter_ads_stats', $meta );
	}
}

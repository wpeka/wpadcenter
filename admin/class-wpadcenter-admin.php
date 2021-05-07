<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Wpadcenter_Adsense class singleton
	 *
	 * @var \Wpeka\Adcenter\Wpadcenter_Adsense
	 */
	private $adsense;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name       The name of this plugin.
	 * @param string $version    The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->adsense     = \Wpeka\Adcenter\Wpadcenter_Adsense::get_instance();
		$this->init_admin_hooks();

	}

	/**
	 * Initialize all admin related hooks like ajax handlers
	 */
	private function init_admin_hooks() {
		add_action( 'wp_ajax_adsense_load_adcode', array( $this, 'load_google_adsense_code' ), 10 );
	}

	/**
	 * Add custom html to import from google adsense ad type
	 */
	public function render_adsense_selection() {
		$data = $this->adsense->get_ad_units();
		if ( true === $data['error'] ) {
			$url = admin_url( 'edit.php?post_type=wpadcenter-ads&page=wpadcenter-settings#adsense' );
			?>
			<a href='<?php echo esc_attr( $url ); ?>'>
				<?php esc_html_e( 'Connect to Adsense', 'wpadcenter' ); ?>
			</a>
			<?php

			return;
		}
		?>
		<?php $nonce = wp_create_nonce( 'wpeka-google-adsense' ); ?>
		<script>
			if ('undefined' == typeof window.AdsenseGAPI) {
				AdsenseGAPI = {};
			}
			AdsenseGAPI.nonce = '<?php echo esc_html( $nonce ); ?>';
		</script>
		<table class="form-table">
			<thead>
			<th><?php esc_html_e( 'Name', 'wpadcenter' ); ?></th>
			<th><?php esc_html_e( 'Status', 'wpadcenter' ); ?></th>
			</thead>
			<tbody id="adsense-adunits">

			<?php foreach ( $data['adunits'] as $unit ) : ?>

				<tr>
					<td><?php echo esc_html( $unit['name'] ); ?></td>
					<td><?php echo esc_html( $unit['status'] ); ?></td>
					<td>
						<button class="button button-primary" data-unitid="<?php echo esc_attr( $unit['id'] ); ?>"
								id="<?php echo esc_attr( $unit['code'] ); ?>">
							<?php esc_html_e( 'Load', 'wpadcenter' ); ?>
						</button>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php

		wp_enqueue_script(
			$this->plugin_name . 'ad-selection-script',
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-ad-selection' . WPADCENTER_SCRIPT_SUFFIX . '.js',
			array( 'jquery' ),
			$this->version,
			true
		);

	}

	/**
	 * Fetch adcode from the api
	 */
	public function load_google_adsense_code() {
		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		if ( ! isset( $_POST['adunit'] ) || false === wp_verify_nonce( $nonce, 'wpeka-google-adsense' ) ) {
			wp_send_json(
				array(
					'error'   => true,
					'message' => 'Invalid Nonce',
				)
			);
		}

		$code = $this->adsense->get_ad_code( sanitize_text_field( wp_unslash( $_POST['adunit'] ) ) );
		wp_send_json( $code );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpadcenter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpadcenter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style(
			$this->plugin_name . '-settings',
			plugin_dir_url(
				__FILE__
			) . 'css/wpadcenter-admin-settings' . WPADCENTER_SCRIPT_SUFFIX . '.css',
			array(),
			$this->version,
			'all'
		);
		wp_register_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.css',
			array(),
			$this->version,
			'all'
		);
		wp_register_style(
			$this->plugin_name . 'jquery-ui',
			'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css',
			array(),
			$this->version,
			'all'
		); // styles for datepicker.
		wp_register_style(
			$this->plugin_name . '-gettingstarted-css',
			plugin_dir_url( __FILE__ ) . 'css/wpadcenter-admin-gettingstarted' . WPADCENTER_SCRIPT_SUFFIX . '.css',
			array(),
			$this->version,
			'all'
		);
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpadcenter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpadcenter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script(
			$this->plugin_name . '-settings',
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin-settings' . WPADCENTER_SCRIPT_SUFFIX . '.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		wp_register_script(
			$this->plugin_name . '-main',
			plugin_dir_url( __FILE__ ) . 'js/vue/wpadcenter-admin-main.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		wp_enqueue_script(
			$this->plugin_name . '-gapi-settings',
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-gapi-settings' . WPADCENTER_SCRIPT_SUFFIX . '.js',
			array( 'jquery' ),
			$this->version,
			true
		);
		wp_register_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_register_script(
			$this->plugin_name . 'adscheduler',
			plugin_dir_url( __FILE__ ) . 'js/vue/wpadcenter-admin-adscheduler.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		wp_register_script(
			$this->plugin_name . '-gettingstarted',
			plugin_dir_url( __FILE__ ) . 'js/vue/wpadcenter-admin-gettingstarted.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		wp_register_script(
			$this->plugin_name . '-reports',
			plugin_dir_url( __FILE__ ) . 'js/vue/wpadcenter-admin-reports.js',
			array(),
			$this->version,
			false
		);
		wp_register_script(
			$this->plugin_name . '-weekly-stats',
			plugin_dir_url( __FILE__ ) . 'js/vue/wpadcenter-admin-weeklyStats.js',
			array(),
			$this->version,
			false
		);
	}

	/**
	 * Monthly schedule cron for clean stats..
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_pro_admin_init() {
		if ( ! wp_next_scheduled( 'wpadcenter_monthly_cron' ) ) {
			$date = new DateTime( 'now' );
			$date->modify( 'first day of next month' );
			wp_schedule_single_event( $date->format( 'U' ), 'wpadcenter_monthly_cron' );
		}
	}

	/**
	 * Admin init.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_monthly_schedule_clean_stats() {
		global $wpdb;
		if ( class_exists( 'Wpadcenter' ) ) {
			$the_options = Wpadcenter::wpadcenter_get_settings();
			$trim_point  = $the_options['trim_stats'];
			if ( isset( $trim_point ) && $trim_point > 0 ) {
				$stat_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT ad_id FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date < DATE_ADD( NOW() , INTERVAL -%d MONTH )', array( $trim_point ) ) ); // db call ok; no-cache ok.
				if ( is_array( $stat_ids ) && ! empty( $stat_ids ) ) {
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date < DATE_ADD( NOW() , INTERVAL -%d MONTH )', array( $trim_point ) ) ); // db call ok; no-cache ok.
				}
			}
		}
		wp_clear_scheduled_hook( 'wpadcenter_monthly_cron' );
	}

	/**
	 * Adds action links to the plugin list table.
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of plugin action links.
	 */
	public function wpadcenter_plugin_action_links( $links ) {
		if ( ! get_option( 'wpadcenter_pro_installed' ) ) {
			$links = array_merge(
				array(
					'<a href="' . esc_url( 'https://club.wpeka.com/product/wpadcenter/?utm_source=wpadcenter&utm_medium=plugins&utm_campaign=link&utm_content=upgrade-to-pro' ) . '" target="_blank" rel="noopener noreferrer"><strong style="color: #11967A; display: inline;">' . __( 'Upgrade to Pro', 'wpadcenter' ) . '</strong></a>',
				),
				$links
			);
		}
		return $links;
	}

	/**
	 * Define arguments for custom post type.
	 *
	 * @since  1.0.0
	 * @return mixed|void
	 */
	public function wpadcenter_get_cpt_args() {

		$cpt_args        = array();
		$cpt_args['ads'] = apply_filters(
			'wp_adcenter_cpt_args_ads',
			array(
				'labels'              => apply_filters(
					'wp_adcenter_cpt_args_labels_ads',
					array(
						'name'                  => __( 'WPAdCenter: Ads', 'wpadcenter' ),
						'singular_name'         => __( 'Ad', 'wpadcenter' ),
						'menu_name'             => __( 'WPAdCenter', 'wpadcenter' ),
						'all_items'             => __( 'Manage Ads', 'wpadcenter' ),
						'add_new_item'          => __( 'Create New Ad', 'wpadcenter' ),
						'add_new'               => __( 'Create Ad', 'wpadcenter' ),
						'new_item'              => __( 'New Ad', 'wpadcenter' ),
						'edit_item'             => __( 'Edit Ad', 'wpadcenter' ),
						'update_item'           => __( 'Update Ad', 'wpadcenter' ),
						'view_item'             => __( 'View Ad', 'wpadcenter' ),
						'view_items'            => __( 'View Ad', 'wpadcenter' ),
						'search_items'          => __( 'Search Ad', 'wpadcenter' ),
						'not_found'             => __( 'No Ads found', 'wpadcenter' ),
						'not_found_in_trash'    => __( 'No Ads found in Trash', 'wpadcenter' ),
						'featured_image'        => __( 'Ad Image', 'wpadcenter' ),
						'set_featured_image'    => __( 'Set ad image', 'wpadcenter' ),
						'remove_featured_image' => __( 'Remove ad image', 'wpadcenter' ),
						'use_featured_image'    => __( 'Set ad image', 'wpadcenter' ),
					)
				),
				'supports'            => array( 'title', 'thumbnail' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'publicly_queryable'  => true,
				'menu_icon'           => WPADCENTER_PLUGIN_URL . 'images/menu-icon.png',
				'rewrite'             => array( 'slug' => 'wpadcenter-ads' ),
				'capability_type'     => 'post',
			)
		);

		return apply_filters( 'wp_adcenter_cpt_args', $cpt_args );

	}

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_cpt() {
		$wpadcenter_cpt_args = $this->wpadcenter_get_cpt_args();
		foreach ( $wpadcenter_cpt_args as $key => $cpt_args ) {
			if ( ! post_type_exists( 'wpadcenter-' . $key ) ) {
				register_post_type( 'wpadcenter-' . $key, $cpt_args );
			}
		}
	}

	/**
	 * Registers taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Manage Ad Groups', 'taxonomy general name', 'wpadcenter' ),
			'singular_name'     => _x( 'Manage Ad Group', 'taxonomy singular name', 'wpadcenter' ),
			'search_items'      => __( 'Search Ad Groups', 'wpadcenter' ),
			'all_items'         => __( 'All Groups', 'wpadcenter' ),
			'parent_item'       => __( 'Parent Group', 'wpadcenter' ),
			'parent_item_colon' => __( 'Parent Group:', 'wpadcenter' ),
			'edit_item'         => __( 'Edit Group', 'wpadcenter' ),
			'update_item'       => __( 'Update Group', 'wpadcenter' ),
			'add_new_item'      => __( 'Add New Group', 'wpadcenter' ),
			'new_item_name'     => __( 'New Group Name', 'wpadcenter' ),
			'menu_name'         => __( 'Manage Ad Groups', 'wpadcenter' ),
			'not_found'         => __( 'No Ad Groups Found', 'wpadcenter' ),
			'view_item'         => __( 'View Ad Group', 'wpadcenter' ),
			'no_terms'          => __( 'No Ad Groups', 'wpadcenter' ),
		);
		$args   = array(
			'labels'             => $labels,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'wpadcenter-adgroups' ),
		);

		register_taxonomy( 'wpadcenter-adgroups', array( 'wpadcenter-ads' ), $args );
	}

	/**
	 * Returns default metafields.
	 *
	 * @since 1.0.0
	 *
	 * @return array $metafields default metafields.
	 */
	public static function get_default_metafields() {
		$metafields = array(
			'ad-type'                               => array( 'wpadcenter_ad_type', 'string' ),
			'ad-size'                               => array( 'wpadcenter_ad_size', 'string' ),
			'open-in-new-tab'                       => array( 'wpadcenter_open_in_new_tab', 'bool' ),
			'nofollow-on-link'                      => array( 'wpadcenter_nofollow_on_link', 'bool' ),
			'link-url'                              => array( 'wpadcenter_link_url', 'url' ),
			'ad-code'                               => array( 'wpadcenter_ad_code', 'raw' ),
			'external-image-link'                   => array( 'wpadcenter_external_image_link', 'url' ),
			'ad-google-adsense'                     => array( 'wpadcenter_ad_google_adsense', 'raw' ),
			'start_date'                            => array( 'wpadcenter_start_date', 'date' ),
			'end_date'                              => array( 'wpadcenter_end_date', 'date' ),
			'limit-ad-impressions-set'              => array( 'wpadcenter_limit_impressions_set', 'bool' ),
			'limit-ad-clicks-set'                   => array( 'wpadcenter_limit_clicks_set', 'bool' ),
			'limit-ad-impressions'                  => array( 'wpadcenter_limit_impressions', 'number' ),
			'limit-ad-clicks'                       => array( 'wpadcenter_limit_clicks', 'number' ),
			'amp-preference'                        => array( 'wpadcenter_amp_preference', 'bool' ),
			'wpadcenter-adsense-amp-code'           => array( 'wpadcenter_adsense_amp_code', 'raw' ),
			'wpadcenter-adsense-amp-dynamic-width'  => array( 'wpadcenter_adsense_amp_dynamic_width', 'number' ),
			'wpadcenter-adsense-amp-dynamic-height' => array( 'wpadcenter_adsense_amp_dynamic_height', 'number' ),
			'wpadcenter-adsense-amp-static-height'  => array( 'wpadcenter_adsense_amp_static_height', 'number' ),
			'wpadcenter-amp-adsense-customize'      => array( 'wpadcenter_amp_adsense_customize', 'string' ),
			'amp-attributes'                        => array( 'wpadcenter_amp_attributes', 'array' ),
			'amp-values'                            => array( 'wpadcenter_amp_values', 'array' ),
			'amp-placeholder'                       => array( 'wpadcenter_amp_placeholder', 'string' ),
			'amp-fallback'                          => array( 'wpadcenter_amp_fallback', 'string' ),
		);

		return apply_filters( 'wp_adcenter_get_default_metafields', $metafields );
	}

	/**
	 * Returns metafields and ad types relation.
	 *
	 * @since 1.0.0
	 *
	 * @return array $metabox_relation array containing relation between metafields and ad-types.
	 */
	public static function get_ad_meta_relation() {

		$ad_meta_relation = array(
			'banner_image'        => array(
				'active_meta_box' => array(
					'ad-size',
					'postimagediv',
					'ad-details',
				),
			),
			'external_image_link' => array(
				'active_meta_box' => array(
					'ad-size',
					'external-image-link',
					'ad-details',
				),
			),
			'ad_code'             => array(
				'active_meta_box' => array(
					'ad-code',
				),
			),
			'import_from_adsense' => array(
				'active_meta_box' => array(
					'ad-google-adsense',
					'amp-preference',
				),
			),
			'amp_ad'              => array(
				'active_meta_box' => array(
					'amp-attributes',
					'ad-size',
				),
			),

		);

		return apply_filters( 'wp_adcenter_get_ad_meta_relation', $ad_meta_relation );

	}

	/**
	 * Returns default ad sizes.
	 *
	 * @since 1.0.0
	 *
	 * @return array $sizes array containing default ad sizes.
	 */
	public static function get_default_ad_sizes() {
		$sizes = array(
			'none'          => array( __( 'Select Ad Size', 'wpadcenter' ), 'ad-size' ),

			'sub-heading-1' => array( __( 'Square and Rectangle', 'wpadcenter' ), 'sub-heading' ),
			'200x200'       => array( __( 'Small square (200x200)', 'wpadcenter' ), 'ad-size' ),
			'240x400'       => array( __( 'Vertical rectangle (240x400)', 'wpadcenter' ), 'ad-size' ),
			'250x250'       => array( __( 'Square (250x250)', 'wpadcenter' ), 'ad-size' ),
			'250x360'       => array( __( 'Triple widescreen (250x360)', 'wpadcenter' ), 'ad-size' ),
			'300x250'       => array( __( 'Inline rectangle (300x250)', 'wpadcenter' ), 'ad-size' ),
			'336x280'       => array( __( 'Large rectangle (336x280)', 'wpadcenter' ), 'ad-size' ),
			'580x400'       => array( __( 'Netboard (580x400)', 'wpadcenter' ), 'ad-size' ),

			'sub-heading-2' => array( __( 'Skyscraper', 'wpadcenter' ), 'sub-heading' ),
			'120x600'       => array( __( 'Skyscraper (120x600)', 'wpadcenter' ), 'ad-size' ),
			'160x600'       => array( __( 'Wide skyscraper (160x600)', 'wpadcenter' ), 'ad-size' ),
			'300x600'       => array( __( 'Half-page ad (300x600)', 'wpadcenter' ), 'ad-size' ),
			'300x1050'      => array( __( 'Portrait (300x1050)', 'wpadcenter' ), 'ad-size' ),

			'sub-heading-3' => array( __( 'Leaderboard', 'wpadcenter' ), 'sub-heading' ),
			'468x60'        => array( __( 'Banner (468x60)', 'wpadcenter' ), 'ad-size' ),
			'728x90'        => array( __( 'Leaderboard (728x90)', 'wpadcenter' ), 'ad-size' ),
			'930x180'       => array( __( 'Top banner (930x180)', 'wpadcenter' ), 'ad-size' ),
			'970x90'        => array( __( 'Large leaderboard (970x90)', 'wpadcenter' ), 'ad-size' ),
			'970x250'       => array( __( 'Billboard (970x250)', 'wpadcenter' ), 'ad-size' ),
			'980x120'       => array( __( 'Panorama (980x120)', 'wpadcenter' ), 'ad-size' ),

			'sub-heading-4' => array( __( 'Button', 'wpadcenter' ), 'sub-heading' ),
			'120x60'        => array( __( 'Button 1 (120x60)', 'wpadcenter' ), 'ad-size' ),
			'120x90'        => array( __( 'Button 2 (120x90)', 'wpadcenter' ), 'ad-size' ),
			'125x125'       => array( __( 'Square button (125x125)', 'wpadcenter' ), 'ad-size' ),

			'sub-heading-5' => array( __( 'Mobile', 'wpadcenter' ), 'sub-heading' ),
			'300x50'        => array( __( 'Mobile banner 1 (300x50)', 'wpadcenter' ), 'ad-size' ),
			'320x50'        => array( __( 'Mobile banner 2 (320x50)', 'wpadcenter' ), 'ad-size' ),
			'320x100'       => array( __( 'Large mobile banner (320x100)', 'wpadcenter' ), 'ad-size' ),

		);
		return apply_filters( 'wp_adcenter_get_default_ad_sizes', $sizes );
	}

	/**
	 * Returns default ad types.
	 *
	 * @since 1.0.0
	 *
	 * @return array $ array containing default ad sizes.
	 */
	public static function get_default_ad_types() {
		$ad_types = array(
			'banner_image'        => __( 'Banner Image', 'wpadcenter' ),
			'external_image_link' => __( 'External Image Link', 'wpadcenter' ),
			'ad_code'             => __( 'Ad Code', 'wpadcenter' ),
			'import_from_adsense' => __( 'Import from Adsense', 'wpadcenter' ),			
			'amp_ad'              => __( 'AMP', 'wpadcenter' ),
		);

		return apply_filters( 'wp_adcenter_get_default_ad_types', $ad_types );
	}

	/**
	 * Register the menu for the plugin.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_admin_menu() {
		// Reports - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Reports',
			__( 'Reports', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-reports',
			array( $this, 'wpadcenter_reports' )
		);
		// Settings - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Settings',
			__( 'Settings', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-settings',
			array( $this, 'wpadcenter_settings' )
		);
		// Getting Started - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Getting Started',
			__( 'Getting Started', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-getting-started',
			array( $this, 'wpadcenter_getting_started' )
		);
		do_action( 'wp_adcenter_admin_menu', 'edit.php?post_type=wpadcenter-ads', 'manage_options' ); // action to add submenu pages for pro versions
		// Getting Started - submenu.
		if ( ! get_option( 'wpadcenter_pro_active' ) ) {
			add_submenu_page(
				'edit.php?post_type=wpadcenter-ads',
				'Go Pro',
				__( 'Go Pro', 'wpadcenter' ),
				'manage_options',
				'https://club.wpeka.com/product/wpadcenter/'
			);
		}
	}

	/**
	 * Manage Ads table columns.
	 *
	 * @since  1.0.0
	 * @return array|void
	 */
	public function wpadcenter_manage_edit_ads_columns() {
		global $current_screen;
		if ( 'wpadcenter-ads' !== $current_screen->post_type ) {
			return;
		}
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'title'           => __( 'Ad Title', 'wpadcenter' ),
			'ad-type'         => __( 'Ad Type', 'wpadcenter' ),
			'ad-dimensions'   => __( 'Ad Dimensions', 'wpadcenter' ),
			'ad-group'        => __( 'Ad Group', 'wpadcenter' ),
			'shortcode'       => __( 'Shortcode', 'wpadcenter' ),
			'template-tag'    => __( 'Template Tag', 'wpadcenter' ),
			'stats-for-today' => __( 'Stats for Today', 'wpadcenter' ),
			'start-date'      => __( 'Start Date', 'wpadcenter' ),
			'end-date'        => __( 'End Date', 'wpadcenter' ),
		);

		return apply_filters( 'wp_adcenter_manage_edit_ads_columns', $columns );
	}

	/**
	 * Manage groupads columns.
	 *
	 * @since  1.0.0
	 * @return array|void
	 */
	public function wpadcenter_manage_edit_adgroups_columns() {
		$columns = array(
			'cb'                   => '<input type="checkbox" />',
			'name'                 => __( 'Name', 'wpadcenter' ),
			'shortcode'            => __( 'Shortcode', 'wpadcenter' ),
			'template-tag'         => __( 'Template Tag', 'wpadcenter' ),
			'number-of-ads'        => __( 'Number of Ads', 'wpadcenter' ),
			'number-of-active-ads' => __( 'Number of Active Ads', 'wpadcenter' ),
		);

		return apply_filters( 'wpadcenter_manage_edit_adgroups_columns', $columns );
	}
	/**
	 * Callback function for reports menu.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_reports() {
		wp_enqueue_script( $this->plugin_name . '-reports' );
		// reports data..
		$args         = array(
			'post_type'      => 'wpadcenter-ads',
			'post_status'    => 'publish',
			'posts_per_page' => '-1',
		);
		$the_query    = new WP_Query( $args );
		$return_array = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$temp_array             = array();
				$temp_array['ad_id']    = get_the_ID();
				$temp_array['ad_title'] = ! empty( get_the_title() ) ? get_the_title() : __( '(no title)', 'wpadcenter' );
				$temp_array['ad_meta']  = get_post_meta( get_the_ID(), 'wpadcenter_ads_stats', true );
				if ( is_array( $temp_array['ad_meta'] ) ) :
					array_push( $return_array, $temp_array );
				endif;
			}
		}
		wp_localize_script( $this->plugin_name . '-reports', 'reportsArray', $return_array );
		require_once plugin_dir_path( __FILE__ ) . 'views/admin-display-reports.php';
	}



	/**
	 * Callback function for Settings menu.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_settings() {
		// Lock out non-admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		wp_enqueue_style( $this->plugin_name . '-settings' );
		wp_enqueue_script( $this->plugin_name . '-settings' );
		$the_options = Wpadcenter::wpadcenter_get_settings();
		// Check if form has been set.
		if ( isset( $_POST['update_admin_settings_form'] ) || ( isset( $_POST['wpadcenter_settings_ajax_update'] ) ) ) {
			// Check nonce.
			check_admin_referer( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
			do_action( 'wp_adcenter_save_settings', $_POST );
			if ( 'update_admin_settings_form' === $_POST['wpadcenter_settings_ajax_update'] ) {
				foreach ( $the_options as $key => $value ) {
					if ( 'ads_txt_content' === $key ) {
						continue;
					}
					if ( isset( $_POST[ $key . '_field' ] ) ) {
						// Store sanitised values only.
						$the_options[ $key ] = Wpadcenter::wpadcenter_sanitise_settings(
							$key,
							wp_unslash(
								$_POST[ $key . '_field' ]
							)
						); // phpcs:ignore input var ok, CSRF ok, sanitization ok.
					}
				}
				$the_options = apply_filters( 'wpadcenter_after_save_settings', $the_options );
				update_option( WPADCENTER_SETTINGS_FIELD, $the_options );
				echo '<div class="updated"><p><strong>' . esc_attr__(
					'Settings Updated.',
					'wpadcenter'
				) . '</strong></p></div>';
			}
		}

		require_once plugin_dir_path( __FILE__ ) . 'partials/wpadcenter-admin-display.php';
	}

	/**
	 * Prints a combobox based on options and selected=match value.
	 *
	 * @param array  $options Array of options.
	 * @param string $selected Which of those options should be selected (allows just one; is case sensitive).
	 */
	public function print_combobox_options( $options, $selected ) {
		foreach ( $options as $key => $value ) {
			echo '<option value="' . esc_html( $value ) . '"';
			if ( $value === $selected ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $key ) . '</option>';
		}
	}

	/**
	 * Return transition effect options.
	 *
	 * @since 1.0.0
	 */
	public function get_transition_effect_options() {
		return apply_filters(
			'wpadcenter_transition_effect_options',
			array(
				__( 'None', 'wpadcenter' )                 => 'none',
				__( 'Fade', 'wpadcenter' )                 => 'fade',
				__( 'Fade Out', 'wpadcenter' )             => 'fadeout',
				__( 'Scroll Right to Left', 'wpadcenter' ) => 'scrollHorz',
				__( 'Scroll Left to Right', 'wpadcenter' ) => 'scrollHorzReverse',
				__( 'Scroll Top to Bottom', 'wpadcenter' ) => 'scrollVert',
				__( 'Scroll Bottom to Top', 'wpadcenter' ) => 'scrollVertReverse',
			)
		);
	}

	/**
	 * Process ads.txt content.
	 *
	 * @param Array $the_options setting options.
	 *
	 * @return mixed
	 */
	public function wpadcenter_after_save_settings( $the_options ) {
		if ( isset( $_POST['ads_txt_tab'] ) ) {
			check_admin_referer( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
			$ads_txt_tab = isset( $_POST['ads_txt_tab'] ) ? sanitize_text_field( wp_unslash( $_POST['ads_txt_tab'] ) ) : '0';
			if ( '1' === $ads_txt_tab && isset( $the_options['enable_ads_txt'] ) ) {
				// process ads.txt content.
				$ads_txt_content = isset( $_POST['ads_txt_content_field'] ) ? esc_textarea(
					$_POST['ads_txt_content_field']
				) : ''; // phpcs:ignore input var ok, CSRF ok, sanitization ok.
				if ( isset( $ads_txt_content ) ) {
					$ads_txt_content                = explode( "\n", $ads_txt_content );
					$ads_txt_content                = array_filter( array_map( 'trim', $ads_txt_content ) );
					$ads_txt_content                = implode( "\n", $ads_txt_content );
					$the_options['ads_txt_content'] = $ads_txt_content;
				}
			}
		}
		return $the_options;
	}

	/**
	 * Check for ads.txt problems.
	 */
	public function wpadcenter_check_ads_txt_problems() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'check_ads_txt_problems', 'security' );
		}
		$notices = $this->wpadcenter_get_notices();
		echo wp_json_encode( $notices );
		wp_die();
	}

	/**
	 * Check for existing ads.txt file replace.
	 */
	public function wpadcenter_check_ads_txt_replace() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'check_ads_txt_replace', 'security' );
		}
		$action_notices = array();
		$remove         = $this->wpadcenter_ads_txt_replace();
		if ( is_wp_error( $remove ) ) {
			$action_notices['response']              = false;
			$action_notices['replace_error_message'] = $remove->get_error_message();
		} else {
			$the_options                     = Wpadcenter::wpadcenter_get_settings();
			$action_notices['response']      = true;
			$action_notices['file_imported'] = __( 'The ads.txt is now managed with WPAdCenter.', 'wpadcenter' );
			$action_notices['file_content']  = $the_options['ads_txt_content'];
		}

		$notices = $this->wpadcenter_get_notices();
		$notices = array_merge( $notices, $action_notices );
		echo wp_json_encode( $notices );
		exit();
	}

	/**
	 * Replace existing ads.txt file.
	 *
	 * @return bool|string|WP_Error
	 */
	public function wpadcenter_ads_txt_replace() {
		if ( ! is_super_admin() ) {
			new WP_Error( 'not_main_site', __( 'Not the main blog', 'wpadcenter' ) );
		}
		$fs_connect = '';
		if ( is_wp_error( $this->wpadcenter_fs_connect() ) ) {
			return $fs_connect;
		}
		global $wp_filesystem;
		$abspath = trailingslashit( $wp_filesystem->abspath() );
		$file    = $abspath . 'ads.txt';
		if ( $wp_filesystem->exists( $file ) && $wp_filesystem->is_file( $file ) ) {
			$the_options    = Wpadcenter::wpadcenter_get_settings();
			$data           = $wp_filesystem->get_contents( $file );
			$file_records   = $this->wpadcenter_parse_file( $data );
			$plugin_records = $this->wpadcenter_parse_file( $the_options['ads_txt_content'] );
			foreach ( $file_records as $k => $record ) {
				foreach ( $plugin_records as $r ) {
					if ( $record[0] === $r[0] ) {
						unset( $file_records[ $k ] );
					}
				}
			}
			$file_records = array_merge( $file_records, $plugin_records );
			$r            = '';
			foreach ( $file_records as $rec ) {
				if ( ! empty( $rec[1] ) ) {
					foreach ( $rec[1] as $rec1 ) {
						$r .= $rec1 . "\n";
					}
				}
				$r .= $rec[0] . "\n";
			}
			$the_options['ads_txt_content'] = $r;
			update_option( WPADCENTER_SETTINGS_FIELD, $the_options );
			if ( $wp_filesystem->delete( $file ) ) {
				return true;
			} else {
				return new WP_Error( 'could_not_delete', __( 'Could not delete the existing ads.txt file', 'wpadcenter' ) );
			}
		} else {
			return new WP_Error( 'not_found', __( 'Could not find the existing ads.txt file', 'wpadcenter' ) );
		}
	}

	/**
	 * Parse file data.
	 *
	 * @param Object $file File.
	 *
	 * @return array
	 */
	public function wpadcenter_parse_file( $file ) {
		$lines    = preg_split( '/\r\n|\r|\n/', $file );
		$comments = array();
		$records  = array();
		foreach ( $lines as $line ) {
			$line    = explode( '#', $line );
			$comment = trim( $line[1] );
			if ( ! empty( $line[1] ) && $comment ) {
				$comments[] = '# ' . $comment;
			}

			if ( ! trim( $line[0] ) ) {
				continue;
			}

			$rec  = explode( ',', $line[0] );
			$data = array();

			foreach ( $rec as $k => $r ) {
				$r = trim( $r, " \n\r\t," );
				if ( $r ) {
					$data[] = $r;
				}
			}

			if ( $data ) {
				// Add the record and comments that were placed above or to the right of it.
				$records[] = array( implode( ', ', $data ), $comments );
			}

			$comments = array();
		}
		return $records;
	}

	/**
	 * File connect function.
	 *
	 * @return bool|WP_Error
	 */
	public function wpadcenter_fs_connect() {
		global $wp_filesystem;
		$fs_connect = Wpadcenter::fs_connect( array( ABSPATH ) );

		if ( false === $fs_connect || is_wp_error( $fs_connect ) ) {
			$message = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'wpadcenter' );

			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error(
				$wp_filesystem->errors
			) && $wp_filesystem->errors->get_error_code() ) {
				$message = esc_html( $wp_filesystem->errors->get_error_message() );
			}
			if ( is_wp_error( $fs_connect ) && $fs_connect->get_error_code() ) {
				$message = esc_html( $fs_connect->get_error_message() );
			}
			return new WP_Error( 'can_not_connect', $message );
		}
		return true;
	}

	/**
	 * Get ads.txt notices.
	 *
	 * @return array
	 */
	public function wpadcenter_get_notices() {
		$notices     = array(
			'response'      => false,
			'error_message' => esc_html__( 'Something went wrong.', 'wpadcenter' ),
		);
		$the_options = Wpadcenter::wpadcenter_get_settings();
		$url         = home_url( '/' );
		$parsed_url  = wp_parse_url( $url );
		if ( ! isset( $parsed_url['scheme'] ) || ! isset( $parsed_url['host'] ) ) {
			return $notices;
		}
		$link = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', esc_url( $url . 'ads.txt' ) );
		if ( $this->wpadcenter_is_subdir() ) {
			$notices['error_message'] = sprintf(
				/* translators: %s main site file path */
				esc_html__(
					'The ads.txt file cannot be placed because the URL contains a subdirectory. You need to make the file available at %s',
					'wpadcenter'
				),
				sprintf(
					'<a href="%1$s" target="_blank">%1$s</a>',
					esc_url( $parsed_url['scheme'] . '://' . $parsed_url['host'] )
				)
			);
		} else {
			$notices['error_message'] = '';
			$file                     = $this->wpadcenter_get_file_info( $url );
			if ( ! is_wp_error( $file ) ) {
				$notices['response'] = true;
				if ( $file['exists'] ) {
					$notices['file_available'] = '<p>' . sprintf(
						/* translators: %s file path */
						esc_html__( 'The file is available on %s.', 'wpadcenter' ),
						$link
					) . '</p>';
				} else {
					$notices['file_available'] = '<p>' . esc_html__( 'The file was not created.', 'wpadcenter' ) . '</p>';
				}

				if ( $file['is_third_party'] ) {
					$message = sprintf(
						/* translators: %s third party file path */
						esc_html__( 'A third-party file exists: %s', 'wpadcenter' ),
						$link
					);

					if ( is_super_admin() ) {
						$button   = ' <input type="button" class="button" style="vertical-align: middle;" name="replace_ads_txt_file" value="%s" />';
						$message .= sprintf( $button, __( 'Import & Replace', 'wpadcenter' ) );
						$message .= '<p class="wpadcenter_form_help">'
							. __(
								'Move the content of the existing ads.txt file into WPAdCenter and remove it.',
								'wpadcenter'
							)
							. '</p>';
					}
					$notices['is_third_party'] = $message;
				}
			} else {
				$notices['error_message'] = sprintf(
					/* translators: %s default file errors */
					esc_html__( 'An error occured: %s.', 'wpadcenter' ),
					esc_html( $file->get_error_message() )
				);
			}
			if ( $this->wpadcenter_get_root_domain_info() ) {
				$notices['domain_error_message'] = '<p>' . sprintf(
					/* translators: %s the line that may need to be added manually */
					esc_html__(
						'If your site is located on a subdomain, you need to add the following line to the ads.txt file of the root domain: %s',
						'wpadcenter'
					),
					// Without http://.
						'<code>subdomain=' . esc_html( $parsed_url['host'] ) . '</code>'
				) . '</p>';
			}
		}
		return $notices;
	}

	/**
	 * Get root domain info.
	 *
	 * @param null $url URL.
	 *
	 * @return bool
	 */
	public function wpadcenter_get_root_domain_info( $url = null ) {
		$url        = $url ? $url : home_url( '/' );
		$parsed_url = wp_parse_url( $url );
		if ( ! isset( $parsed_url['host'] ) ) {
			return false;
		}
		$host = $parsed_url['host'];
		if ( WP_Http::is_ip_address( $host ) ) {
			return false;
		}
		$host_parts = explode( '.', $host );
		$count      = count( $host_parts );
		if ( $count < 3 ) {
			return false;
		}
		if ( 3 === $count ) {
			// Example: `http://one.{net/org/gov/edu/co}.two`.
			$suffixes = array( 'net', 'org', 'gov', 'edu', 'co' );
			if ( in_array( $host_parts[ $count - 2 ], $suffixes, true ) ) {
				return false;
			}
			// Example: `one.com.au'.
			$suffix_and_tld = implode( '.', array_slice( $host_parts, 1 ) );
			if ( in_array( $suffix_and_tld, array( 'com.au', 'com.br', 'com.pl' ), true ) ) {
				return false;
			}
			// `http://www.one.two` will only be crawled if `http://one.two` redirects to it.
			// Check if such redirect exists.
			if ( 'www' === $host_parts[0] ) {
				/*
				 * Do not append `/ads.txt` because otherwise the redirect will not happen.
				 */
				$no_www_url = $parsed_url['scheme'] . '://' . trailingslashit( $host_parts[1] . '.' . $host_parts[2] );

				add_action( 'requests-requests.before_redirect', array( $this, 'collect_locations' ) );
				wp_remote_get(
					$no_www_url,
					array(
						'timeout'     => 5,
						'redirection' => 3,
					)
				);
				remove_action( 'requests-requests.before_redirect', array( $this, 'collect_locations' ) );

				$no_www_url_parsed = wp_parse_url( $this->location );
				if ( isset( $no_www_url_parsed['host'] ) && $no_www_url_parsed['host'] === $host ) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Set location.
	 *
	 * @param string $location Location.
	 */
	public function wpadcenter_collect_locations( $location ) {
		$this->location = $location;
	}

	/**
	 * Get File info.
	 *
	 * @param null $url URL.
	 *
	 * @return array|WP_Error
	 */
	public function wpadcenter_get_file_info( $url = null ) {
		$url = $url ? $url : home_url( '/' );

		// Disable ssl verification to prevent errors on servers that are not properly configured with its https certificates.
		/**
 * This filter is documented in wp-includes/class-wp-http-streams.php
*/
		$sslverify    = apply_filters( 'https_local_ssl_verify', false );
		$response     = wp_remote_get(
			trailingslashit( $url ) . 'ads.txt',
			array(
				'timeout'   => 3,
				'sslverify' => $sslverify,
				'headers'   => array(
					'Cache-Control' => 'no-cache',
				),
			)
		);
		$code         = wp_remote_retrieve_response_code( $response );
		$content      = wp_remote_retrieve_body( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$file_exists   = ! is_wp_error( $response ) && 404 !== $code && ( false !== stripos( $content_type, 'text/plain' ) );
		$header_exists = false !== strpos( $content, Wpadcenter::TOP );

		$res = array(
			'exists'         => $file_exists && $header_exists,
			'is_third_party' => $file_exists && ! $header_exists,
		);
		return $res;
	}

	/**
	 * Check if si sub-directory.
	 *
	 * @param null $url URL.
	 *
	 * @return bool
	 */
	public function wpadcenter_is_subdir( $url = null ) {
		$url        = $url ? $url : home_url( '/' );
		$parsed_url = wp_parse_url( $url );
		if ( ! empty( $parsed_url['path'] ) && '/' !== $parsed_url['path'] ) {
			return true;
		}
		return false;

	}
	/**
	 * Display values in manage ads columns.
	 *
	 * @param string  $column column name.
	 * @param integer $ad_id Id of the ad.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_manage_ads_column_values( $column = '', $ad_id = 1 ) {

		$sizes_list    = $this->get_default_ad_sizes();
		$ad_types_list = $this->get_default_ad_types();
		switch ( $column ) {
			case 'ad-type':
				$ad_type = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
				if ( $ad_type ) {
					echo esc_html( $ad_types_list[ $ad_type ] );
				} else {
					echo '-';
				}
				break;
			case 'ad-dimensions':
				$ad_size = get_post_meta( $ad_id, 'wpadcenter_ad_size', true );
				if ( $ad_size && 'none' !== $ad_size ) {
					$size_data = $sizes_list[ $ad_size ];
					echo esc_html( $size_data[0] );
				} else {
					echo '-';
				}
				break;
			case 'start-date':
				$current_start_date = get_post_meta( $ad_id, 'wpadcenter_start_date', true );
				if ( $current_start_date ) {
					echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $current_start_date ) );// get format from WordPress settings.
				}
				break;
			case 'end-date':
				$expire_limit     = '1924905600'; // unix timestamp for 31 dec 2030.
				$current_end_date = get_post_meta( $ad_id, 'wpadcenter_end_date', true );
				if ( $current_end_date && $current_end_date === $expire_limit ) {
					echo esc_html__( 'Forever', 'wpadcenter' );
				} elseif ( $current_end_date ) {
					echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $current_end_date ) );// get format from WordPress settings.
				}
				break;
			case 'ad-group':
				$names = wp_get_post_terms( $ad_id, 'wpadcenter-adgroups', array( 'fields' => 'names' ) );
				if ( ! count( $names ) ) {
					echo esc_html( '-' );
				} else {
					echo esc_html( implode( ', ', $names ) );
				}
				break;
			case 'shortcode':
				echo sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="[wpadcenter_ad id=%d align=\'none\']">[shortcode]</a>', intval( $ad_id ) );
				break;
			case 'template-tag':
				echo sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="wpadcenter_display_ad( array( \'id\' => %d, \'align\' => \'none\' ) );">&lt;?php</a>', intval( $ad_id ) );
				break;
			case 'stats-for-today':
				$today = gmdate( 'Y-m-d' );
				global $wpdb;
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics where ad_date=%s AND ad_id=%d', array( $today, $ad_id ) ) ); // phpcs:ignore
				if ( ! count( $results ) ) {
					echo '0 clicks / 0 views / 0.00% CTR';
				} else {
					$record = $results[0];
					$ctr    = number_format( (float) ( $record->ad_clicks / $record->ad_impressions ) * 100, 2, '.', '' ) . '%';
					echo sprintf( '%d clicks / %d views / %s CTR', esc_html( $record->ad_clicks ), esc_html( $record->ad_impressions ), esc_html( $ctr ) );
				}
				break;
		}
		do_action( 'wp_adcenter_manage_ads_column_values', $column, $ad_id );
	}


	/**
	 * Display values in manage ads columns.
	 *
	 * @param string  $value echo for the column value.
	 * @param string  $column column name.
	 * @param integer $term_id Id of the term - wpadcenter-adgroup.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_manage_ad_groups_column_values( $value, $column, $term_id ) {
		$output = '';
		switch ( $column ) {
			case 'shortcode':
				$output = sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="[wpadcenter_adgroup adgroup_ids=%d align=\'none\' num_ads=1 num_columns=1]">[shortcode]</a>', intval( $term_id ) );
				break;
			case 'template-tag':
				$output = sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="wpadcenter_display_adgroup( array( \'adgroup_ids\' => %d, \'align\' => \'none\', \'num_ads\' => 1, \'num_columns\' => 1 ) );">&lt;?php</a>', intval( $term_id ) );
				break;
			case 'number-of-ads':
				$args      = array(
					'post_type' => 'wpadcenter-ads',
					'tax_query' => array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => 'wpadcenter-adgroups',
							'field'    => 'term_id',
							'terms'    => $term_id,
						),
					),
				);
				$output    = 0;
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$output++;
					}
				}
				break;
			case 'number-of-active-ads':
				$args      = array(
					'post_type' => 'wpadcenter-ads',
					'tax_query' => array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => 'wpadcenter-adgroups',
							'field'    => 'term_id',
							'terms'    => $term_id,
						),
					),
				);
				$output    = 0;
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$ad_id        = get_the_ID();
						$current_time = time();
						$start_date   = get_post_meta( $ad_id, 'wpadcenter_start_date', true );
						$end_date     = get_post_meta( $ad_id, 'wpadcenter_end_date', true );
						if ( $current_time < $start_date || $current_time > $end_date ) {
							continue;
						} else {
							$output++;
						}
					}
				}
				break;
		}
		return $output;
	}



	/**
	 * Add meta boxes to create ads page.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_add_meta_boxes( $post ) {
		$wpadcenter_api_key = get_option( 'wc_am_client_wpadcenter_pro_activated' );
		add_meta_box(
			'ad-type',
			__( 'Ad Type', 'wpadcenter' ),
			array( $this, 'wpadcenter_ad_type' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);

		add_meta_box(
			'ad-size',
			__( 'Ad Size', 'wpadcenter' ),
			array( $this, 'wpadcenter_ad_size_metabox' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);
		add_meta_box(
			'postimagediv',
			__( 'Ad Image', 'wpadcenter' ),
			'post_thumbnail_meta_box',
			null,
			'normal',
			'high'
		);
		add_meta_box(
			'ad-details',
			__( 'Ad details', 'wpadcenter' ),
			array( $this, 'wpadcenter_ad_detail_metabox' ),
			'wpadcenter-ads',
			'normal',
			'low'
		);
		add_meta_box(
			'ad-code',
			__( 'Ad Code', 'wpadcenter' ),
			array( $this, 'wpadcenter_ad_code_metabox' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);
		add_meta_box(
			'external-image-link',
			__( 'External Image Link', 'wpadcenter' ),
			array( $this, 'wpadcenter_external_image_link_metabox' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);
		add_meta_box(
			'ad-google-adsense',
			__( 'Ad Google Adsense', 'wpadcenter' ),
			array( $this, 'wpadcenter_ad_google_adsense' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);
		if ( 'publish' === $post->post_status ) {
			add_meta_box(
				'ad-stats',
				__( 'Ad Statistics', 'wpadcenter' ),
				array( $this, 'wpadcenter_ad_statistics' ),
				'wpadcenter-ads',
				'normal',
				'low'
			);
		}
		if ( get_option( 'wpadcenter_pro_active' ) && 'Activated' === $wpadcenter_api_key ) {
			add_meta_box(
				'ad-limits',
				__( 'Limit Impressions / Clicks', 'wpadcenter' ),
				array( $this, 'wpadcenter_limit_impressions_clicks' ),
				'wpadcenter-ads',
				'normal',
				'low'
			);
		}
		add_meta_box(
			'amp-preference',
			__( 'Amp Preference', 'wpadcenter' ),
			array( $this, 'wpadcenter_pro_amp_preference_metabox' ),
			'wpadcenter-ads',
			'normal',
			'core'
		);
		add_meta_box(
			'amp-attributes',
			__( 'Amp Ad Parameters', 'wpadcenter' ),
			array( $this, 'wpadcenter_pro_amp_attributes_metabox' ),
			'wpadcenter-ads',
			'normal',
			'high'
		);
		do_action( 'wp_adcenter_add_meta_boxes', $post );

	}
	/**
	 * Shows Ads Stats for last 7 days.
	 *
	 * @param WP_POST $post post object.
	 */
	public function wpadcenter_ad_statistics( $post ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date > now() - interval 7 day and ad_id = %d;', $post->ID ) );//phpcs:ignore
		if ( empty( $results ) ) {
			$results = array();
		}
		$dates = array();
		$today = strtotime( 'now' );
		array_push( $dates, gmdate( 'Y-m-d' ) );
		for ( $i = 1; $i < 7; $i++ ) {
			array_push( $dates, gmdate( 'Y-m-d', strtotime( '-' . $i . ' day', $today ) ) );
		}
		array_push( $results, $dates );
		wp_enqueue_script( $this->plugin_name . '-weekly-stats' );
		wp_localize_script( $this->plugin_name . '-weekly-stats', 'returnArray', $results );
		?>
			<div id="wpadcenter-weekly-stats">
				<h4><?php esc_html_e( 'Stats Summary for past 7 days', 'wpadcenter' ); ?></h4>
				<p>{{ totalClicks }} <?php esc_html_e( 'Total Clicks', 'wpadcenter' ); ?> | {{ totalViews }} <?php esc_html_e( 'Total Views', 'wpadcenter' ); ?> | {{ totalCTR }} <?php esc_html_e( 'Total CTR', 'wpadcenter' ); ?> </p>
				<div class="chart-container">
					<line-chart :chart-data="chartData" :options="chartOptions"></line-chart>
				</div>
			</div>
		<?php
	}

	/**
	 * Shows Ads Stats for last 7 days.
	 *
	 * @param WP_POST $post post object.
	 */
	public function wpadcenter_limit_impressions_clicks( $post ) {
		$impressions_set = get_post_meta( $post->ID, 'wpadcenter_limit_impressions_set', true );
		$clicks_set      = get_post_meta( $post->ID, 'wpadcenter_limit_clicks_set', true );
		$impressions     = get_post_meta( $post->ID, 'wpadcenter_limit_impressions', true );
		$clicks          = get_post_meta( $post->ID, 'wpadcenter_limit_clicks', true );
		?>
			<div class="wpadcenter_impressions">
				<label for="limit-ad-impressions-set"><?php esc_html_e( 'Limit Impressions', 'wpadcenter' ); ?></label>
				<input type="checkbox" style="margin-left:5px" name="limit-ad-impressions-set" id="limit-ad-impressions-set" <?php checked( '1', $impressions_set, true ); ?> value="1" class="make_radio">
				<p><?php esc_html_e( "Limit an ad's display to a set number of impressions all-time.", 'wpadcenter' ); ?></p>
				<div id="impressions_number">
					<label for="limit-ad-impressions-set"><?php esc_html_e( 'Impression Limit: ', 'wpadcenter' ); ?></label>
					<input type="number" style="margin-left:5px" name="limit-ad-impressions" id="limit-ad-impressions" value="<?php echo esc_attr( $impressions ); ?>" min="0">
				</div>
			</div><br><br>
			<div class="wpadcenter_clicks">
				<label for="limit-ad-clicks-set"><?php esc_html_e( 'Limit Clicks', 'wpadcenter' ); ?></label>
				<input type="checkbox" style="margin-left:5px" name="limit-ad-clicks-set" id="limit-ad-clicks-set" value="1" <?php checked( '1', $clicks_set, true ); ?> class="make_radio">
				<p><?php esc_html_e( "Limit an ad's display to a set number of clicks all-time.", 'wpadcenter' ); ?></p>
				<div id="clicks_number">
					<label for="limit-ad-clicks-set"><?php esc_html_e( 'Clicks Limit: ', 'wpadcenter' ); ?></label>
					<input type="number" style="margin-left:5px" name="limit-ad-clicks" id="limit-ad-clicks" value="<?php echo esc_attr( $clicks ); ?>" min="0">
				</div>
			</div>
		<?php
	}


	/**
	 * Ad-size meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_size_metabox( $post ) {

		$sizes_list = $this->get_default_ad_sizes();

		$default_size = apply_filters( 'wpadcenter_ad_size_default', 'none' );

		$size = get_post_meta( $post->ID, 'wpadcenter_ad_size', true );
		echo '<select name="ad-size" id="size" size="1">';
		foreach ( $sizes_list as $val => $data ) {
			if ( 'sub-heading' === $data[1] ) {
				echo sprintf(
					'<optgroup label=%s>',
					esc_html( $data[0] )
				);
			} else {
				echo sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $val ),
					( empty( $size ) && $val === $default_size ? 'selected="selected"' : selected( $size, $val ) ),
					esc_html( $data[0] )
				);
			}
		}
		echo '</select>';
	}

	/**
	 * Ad-detail meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_detail_metabox( $post ) {
		$open_in_new_tab  = get_post_meta( $post->ID, 'wpadcenter_open_in_new_tab', true );
		$nofollow_on_link = get_post_meta( $post->ID, 'wpadcenter_nofollow_on_link', true );
		$url              = get_post_meta( $post->ID, 'wpadcenter_link_url', true );
		echo '
		<div>
		<label for="open-in-new-tab"><input name="open-in-new-tab" type="checkbox" value="1" id="open-in-new-tab" ' . checked( '1', $open_in_new_tab, false ) . '> ' . esc_html__( 'Open Link In a New Tab', 'wpadcenter' ) . '</label>
		<label for="nofollow-on-link" style="margin-left:20px"><input name="nofollow-on-link" type="checkbox" value="1" id="nofollow-on-link" ' . checked( '1', $nofollow_on_link, false ) . '>' . esc_html__( 'Use nofollow on Link', 'wpadcenter' ) . '</label>
		</div>
		<div style="margin-top:10px">
		<label for="link-url"><strong>' . esc_html__( 'Link URL', 'wpadcenter' ) . '</strong></label>
		<input type="text" name="link-url" value="' . esc_attr( $url ) . '" id="link-url" style="width:100%" >
		</div>
		';
	}


	/**
	 * Ad-code meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_code_metabox( $post ) {
		$ad_code = get_post_meta( $post->ID, 'wpadcenter_ad_code', true );
		echo '<textarea name="ad-code" style="width:100%;height:200px" >' . esc_textarea( $ad_code ) . '</textarea>';
	}

	/**
	 * Ad Google Adsense meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_google_adsense( $post ) {
		$ad_google_adsense = get_post_meta( $post->ID, 'wpadcenter_ad_google_adsense', true );
		echo '<textarea name="ad-google-adsense" id="wpadcenter-google-adsense-code" style="width:100%;height:200px" >' . esc_textarea( $ad_google_adsense ) . '</textarea>';
		$this->render_adsense_selection();
	}

	/**
	 * External Image Link meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_external_image_link_metabox( $post ) {
		$image_link          = get_post_meta( $post->ID, 'wpadcenter_external_image_link', true );
		$external_image_link = ! empty( $image_link ) ? $image_link : '';
		echo '<input name="external-image-link" type="text" value="' . esc_url( $external_image_link ) . '" style="width:100%">';
	}

	/**
	 * Ad type meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_type( $post ) {

		wp_nonce_field( 'wpadcenter_save_ad', 'wpadcenter_save_ad_nonce' );

		$ad_types_list = $this->get_default_ad_types();

		$ad_type = get_post_meta( $post->ID, 'wpadcenter_ad_type', true );

		$default_ad_type = apply_filters( 'wpadcenter_ad_type_default', 'banner_image' );

		echo '<select name="ad-type" id="ad-type">';
		foreach ( $ad_types_list as $val => $name ) {

			echo sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $val ),
				( empty( $ad_type ) && $val === $default_ad_type ? 'selected="selected"' : selected( $ad_type, $val ) ),
				esc_html( $name )
			);

		}

		echo '</select>';

	}

	/**
	 * Call back function for the amp preference metabox.
	 *
	 * @param WP_POST $post Post object.
	 */
	public function wpadcenter_pro_amp_preference_metabox( $post ) {

		$amp_preference             = get_post_meta( $post->ID, 'wpadcenter_amp_preference', true );
		$amp_adsense_code           = get_post_meta( $post->ID, 'wpadcenter_adsense_amp_code', true );
		$amp_adsense_dynamic_width  = get_post_meta( $post->ID, 'wpadcenter_adsense_amp_dynamic_width', true );
		$amp_adsense_dynamic_height = get_post_meta( $post->ID, 'wpadcenter_adsense_amp_dynamic_height', true );
		$amp_adsense_static_height  = get_post_meta( $post->ID, 'wpadcenter_adsense_amp_static_height', true );
		$amp_adsense_customize      = get_post_meta( $post->ID, 'wpadcenter_amp_adsense_customize', true );

		$amp_adsense_customize      = $amp_adsense_customize ? $amp_adsense_customize : 'auto';
		$amp_adsense_dynamic_width  = $amp_adsense_dynamic_width ? $amp_adsense_dynamic_width : '300';
		$amp_adsense_dynamic_height = $amp_adsense_dynamic_height ? $amp_adsense_dynamic_height : '250';
		$amp_adsense_static_height  = $amp_adsense_static_height ? $amp_adsense_static_height : '100';

		echo '<div>
		<label for="ampPreference"><input name="amp-preference" type="checkbox" value="1" id="ampPreference" ' . checked( '1', $amp_preference, false ) . '> ' . esc_html__( 'Enable AMP support', 'wpadcenter' ) . ' <span style="color:grey">( ' . esc_html__( 'If enabled, automatically converts to AMP ad on AMP websites.','wpadcenter' ) . ' )</span></label>
		</div>';

		echo '<div class="wpadcenterAmpCustomizeSettings" >
		<br><label>
		<input id="wpadcenterAmpCustomizeAuto" name="wpadcenter-amp-adsense-customize" class="wpadcenterAmpCustomize" type="radio" value="auto" ' . checked( $amp_adsense_customize, 'auto', false ) . ' />' . esc_html__( 'Automatically convert to AMP', 'wpadcenter' ) . '
		</label><br><br>
		<label>
		<input id="wpadcenterAmpCustomizeDynamic" name="wpadcenter-amp-adsense-customize" class="wpadcenterAmpCustomize" type="radio" value="dynamic" ' . checked( $amp_adsense_customize, 'dynamic', false ) . ' />' . esc_html__( 'Use dynamic size corresponding to ratio', 'wpadcenter' ) . ' <input id="wpadcenterAmpCustomizeDynamicWidth" class="wpadcenterAmpCustomize" name="wpadcenter-adsense-amp-dynamic-width" type="number" min=1 value="' . esc_attr( $amp_adsense_dynamic_width ) . '" > X <input id="wpadcenterAmpCustomizeDynamicHeight" name="wpadcenter-adsense-amp-dynamic-height" class="wpadcenterAmpCustomize" type="number" min=1 value="' . esc_attr( $amp_adsense_dynamic_height ) . '" >
		</label><br><br>
		<label>
		<input id="wpadcenterAmpCustomizeStatic" name="wpadcenter-amp-adsense-customize" class="wpadcenterAmpCustomize" type="radio" value="static" ' . checked( $amp_adsense_customize, 'static', false ) . ' />' . esc_html__( 'Use responsive width and static height of', 'wpadcenter' ) . ' <input id="wpadcenterAmpCustomizeStaticHeight" name="wpadcenter-adsense-amp-static-height" class="wpadcenterAmpCustomize" type="number" min=1 value="' . esc_attr( $amp_adsense_static_height ) . '" >
		</label><br><br>
		</div>
		';
		echo '<div class="wpadcenterAmpCustomizeSettings" >
		<br><label for="wpadcenterAdsenseAmpCode"><strong>AMP Code Preview: </strong></label>

		<textarea id="wpadcenterAdsenseAmpCode" name="wpadcenter-adsense-amp-code" style="width:100%;height:100px">' . $amp_adsense_code . '</textarea></div> '; //phpcs:ignore
	}

	/**
	 * Callback function for the Amp attributes metabox.
	 *
	 * @param WP_POST $post Post object.
	 */
	public function wpadcenter_pro_amp_attributes_metabox( $post ) {
		wp_enqueue_style( $this->plugin_name );

		echo '<div id="wpadcenter-amp-attributes-container">';

		$saved_amp_attributes  = get_post_meta( $post->ID, 'wpadcenter_amp_attributes', true );
		$saved_amp_values      = get_post_meta( $post->ID, 'wpadcenter_amp_values', true );
		$saved_amp_placeholder = get_post_meta( $post->ID, 'wpadcenter_amp_placeholder', true );
		$saved_amp_fallback    = get_post_meta( $post->ID, 'wpadcenter_amp_fallback', true );

		if ( ! empty( $saved_amp_attributes ) ) {
			$index = 0;
			foreach ( $saved_amp_attributes as $attribute ) {
				echo '<div>
						<label >Attribute : </label><input  name="amp-attributes[] " value="' . esc_attr( $attribute ) . '" /> =
						<label >Value : </label><input  name="amp-values[] " value="' . esc_attr( $saved_amp_values[ $index ] ) . '" />
						<button class="wpadcenter-amp-delete-attr-button">' . esc_html__( 'Remove', 'wpadcenter' ) . '</button>

						<br><br></div>';
				$index++;
			}
		} else {
			$default_attributes = array(
				'type',
				'width',
				'height',
			);
			foreach ( $default_attributes as $attribute ) {
				echo '<div>
						<label >' . esc_html__( 'Attribute :', 'wpadcenter' ) . '</label><input name="amp-attributes[] " value="' . esc_attr( $attribute ) . '"/> =
						<label >' . esc_html__( 'Value :', 'wpadcenter' ) . '</label><input name="amp-values[] " />
						<button class="wpadcenter-amp-delete-attr-button">' . esc_html__( 'Remove', 'wpadcenter' ) . '</button>

					<br><br>
					</div>';
			}
		}

		echo '</div><br><button class="button-secondary" id="wpadcenter-amp-add-attr-button">' . esc_html__( 'Add Attribute', 'wpadcenter' ) . '</button><br><br><hr>';
		echo '<br><label style="display:block" ><strong>' . esc_html__( 'Placeholder :     ', 'wpadcenter' ) . '</strong></label><input name="amp-placeholder" class="wpadcenter-amp-parameter-input" value="' . esc_attr( $saved_amp_placeholder ) . '" size="50" /><br><span style="color:grey">( ' . esc_html__( 'If supported by the ad network, this text is shown until the ad is available for viewing.', 'wpadcenter' ) . ' )</span><br><br><hr>';
		echo '<br><label style="display:block" ><strong>' . esc_html__( 'Fallback :     ', 'wpadcenter' ) . '</strong></label><input name="amp-fallback" class="wpadcenter-amp-parameter-input" value="' . esc_attr( $saved_amp_fallback ) . '" size="50" /><br><span style="color:grey">( ' . esc_html__( 'If supported by the ad network, this text is shown if no ad is available for the ad slot.', 'wpadcenter' ) . ' )</span>';
	}

	/**
	 * Save ad meta data.
	 *
	 * @param integer $post_id post id of post being saved.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_save_ad_meta( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$nonce_field = 'wpadcenter_save_ad_nonce';
		if (
			! isset( $_POST[ $nonce_field ] ) ||
			! wp_verify_nonce( sanitize_key( $_POST[ $nonce_field ] ), 'wpadcenter_save_ad' )
		) {
			return;
		}
		if ( isset( $_POST['ad-type'] ) ) {
			if ( 'ad_code' === $_POST['ad-type'] || 'import_from_adsense' === $_POST['ad-type'] ) {
				$_POST['ad-size'] = 'none';
			}
		}
		$raw_data = $_POST;

		$metafields = $this->get_default_metafields();

		foreach ( $metafields as $meta_name => $meta_data ) {

			$sanitized_data = false;

			switch ( $meta_data[1] ) {

				case 'string':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? sanitize_text_field( $raw_data[ $meta_name ] ) : false;
					break;
				case 'bool':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? (bool) $raw_data[ $meta_name ] : 0;
					break;
				case 'raw':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? $raw_data[ $meta_name ] : false;
					break;
				case 'url':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? esc_url_raw( $raw_data[ $meta_name ] ) : false;
					break;
				case 'date':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? intval( $raw_data[ $meta_name ] ) : false;
					break;
				case 'number':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? intval( $raw_data[ $meta_name ] ) : false;
					break;
				case 'array':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) && is_array( $raw_data[ $meta_name ] ) && ! empty( $raw_data[ $meta_name ] ) ? $raw_data[ $meta_name ] : array();
					break;
			}

			if ( true === (bool) $sanitized_data || empty( $sanitized_data ) ) {

				update_post_meta( $post_id, $meta_data[0], $sanitized_data );

			} elseif ( ! isset( $raw_data[ $meta_name ] ) && 'bool' === $meta_data[1] ) {

				delete_post_meta( $post_id, $meta_data[0] );

			}
			if ( ! get_post_meta( $post_id, 'wpadcenter_ads_stats' ) ) {
				$meta = array(
					'total_impressions' => 0,
					'total_clicks'      => 0,
				);
				update_post_meta( $post_id, 'wpadcenter_ads_stats', $meta );
			}
		}

		do_action( 'wp_adcenter_save_ad_meta', $raw_data, $post_id );

	}


	/**
	 * Renders meta boxes as per ad-type.
	 *
	 * @param WP_POST $post post being edited.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_edit_form_after_title( $post ) {

		$ad_meta_relation = $this->get_ad_meta_relation();

		$current_ad_type = get_post_meta( $post->ID, 'wpadcenter_ad_type', true );
		$current_ad_type = ! empty( $current_ad_type ) ? $current_ad_type : 'banner_image';

		wp_enqueue_style(
			$this->plugin_name . '-select2',
			WPADCENTER_PLUGIN_URL . 'vendor/select2/select2/dist/css/select2.min.css',
			array(),
			$this->version,
			'all'
		);
		wp_enqueue_script(
			$this->plugin_name . '-select2',
			WPADCENTER_PLUGIN_URL . 'vendor/select2/select2/dist/js/select2.min.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_localize_script( $this->plugin_name, 'wpadcenter_render_metaboxes', array( $ad_meta_relation, $current_ad_type ) );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Adds options to set start and end date for ads.
	 *
	 * @param WP_POST $post post being edited.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_post_submitbox_start( $post ) {
		if ( get_post_type() === 'wpadcenter-ads' || get_query_var( 'post_type' ) === 'wpadcenter-ads' ) {

			global $wp_locale;

			$ad_scheduler = array(
				'gmt_offset'      => get_option( 'gmt_offset' ),
				'timezone_string' => get_option( 'timezone_string' ),
				'months'          => $wp_locale->month,
				'expire_limit'    => 1924905600, // unix timestamp for 31 dec 2030.
				'expires_message' => __( 'Ad Expires on', 'wpadcenter' ),
				'forever_message' => sprintf(
					'%s <strong>%s</strong> ',
					__( 'Publish', 'wpadcenter' ),
					__( 'forever', 'wpadcenter' )
				),
			);

			wp_localize_script( $this->plugin_name . 'adscheduler', 'wpadcenter_ad_scheduler', $ad_scheduler );

			wp_enqueue_style( $this->plugin_name );

			wp_enqueue_style( $this->plugin_name . 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_enqueue_script( $this->plugin_name . 'adscheduler' );

			$start_date = get_post_meta( $post->ID, 'wpadcenter_start_date', true );
			$end_date   = get_post_meta( $post->ID, 'wpadcenter_end_date', true );

			echo '<div class="misc-pub-section curtime misc-pub-curtime">

		<span id="timestamp">
		 <span id="publish-text"></span>
		</span>
		<a href="#" id="edit-ad-schedule">Edit</a>


		</div>';

			echo '<div id="ad-schedule-show" class="ad-schedule-box" style="display:none">';
			// start date code starts.
			echo '<label for="start-date">' . esc_html__( 'Starts From', 'wpadcenter' ) . '</label>';
			echo '<div>';
			echo '<input id="start-date" type="text" class="wpadcenter-date-input" autocomplete="off">';
			echo '</div>';
			// time in hours and minutes.
			echo '<div class="wpadcenter-time-container">';

			echo '<select id="start-hours" class="wpadcenter-time-input">';
			for ( $i = 0; $i < 24; ++$i ) {

				$hour = str_pad( $i, 2, '0', STR_PAD_LEFT );

				printf(
					'<option value="%1$s">%1$s</option>',
					esc_attr( $hour )
				);
			}
			echo '</select>';
			echo ' : ';

			echo '<select id="start-minutes" class="wpadcenter-time-input">';
			for ( $i = 0; $i < 60; ++$i ) {
				$minutes = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%1$s</option>',
					esc_attr( $minutes )
				);
			}
			echo '</select>';

			echo '</div>';

			$start_time = ! $start_date ? time() : $start_date;

			printf(
				'<input type="hidden" name="start_date" id="start_date" value="%s">',
				esc_attr( $start_time )
			);

			// start date code ends.

			// end date code starts.
			echo '<label for="end-date">' . esc_html__( 'Expires On', 'wpadcenter' ) . '</label>';
			echo '<div>';
			echo '<input id="end-date" type="text" class="wpadcenter-date-input" autocomplete="off">';
			echo '</div>';
			// time in hours and minutes.
			echo '<div class="wpadcenter-time-container">';

			echo '<select id="end-hours" class="wpadcenter-time-input">';
			for ( $i = 0; $i < 24; ++$i ) {

				$hour = str_pad( $i, 2, '0', STR_PAD_LEFT );

				printf(
					'<option value="%1$s">%1$s</option>',
					esc_attr( $hour )
				);
			}
			echo '</select> ';
			echo ' : ';

			echo '<select id="end-minutes" class="wpadcenter-time-input">';
			for ( $i = 0; $i < 60; ++$i ) {
				$minutes = str_pad( $i, 2, '0', STR_PAD_LEFT );
				printf(
					'<option value="%1$s">%1$s</option>',
					esc_attr( $minutes )
				);
			}
			echo '</select>';

			echo '</div>';

			$end_time = ! $end_date ? $ad_scheduler['expire_limit'] : $end_date;

			printf(
				'<input type="hidden" name="end_date" id="end_date" value="%s">',
				esc_attr( $end_time )
			);

			// end date code ends.

			echo '<a href="#" id="ad-publish-forever" class="button">Publish Forever</a>';
			echo '<a href="#" id="ad-schedule-ok" class="wpadcenter-schedule-ok button">OK</a>';

			echo '</div>';
		}

	}





	/**
	 * Getting Started Page
	 */
	public function wpadcenter_getting_started() {
		// Lock out non-admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		$is_pro   = get_option( 'wpadcenter_pro_active' );
		$disabled = get_option( 'wpadcenter_pro_woo_integrated' );
		if ( $is_pro ) {
			$support_url = 'https://club.wpeka.com/my-account/orders/?utm_source=wpadcenter&utm_medium=help-mascot&utm_campaign=link&utm_content=support';
		} else {
			$support_url = 'https://wordpress.org/support/plugin/wpadcenter/?utm_source=wpadcenter&utm_medium=help-mascot&utm_campaign=link&utm_content=forums';
		}
		wp_enqueue_style( $this->plugin_name . '-gettingstarted-css' );
		wp_enqueue_script( $this->plugin_name . '-gettingstarted' );
		wp_localize_script(
			$this->plugin_name . '-gettingstarted',
			'obj',
			array(
				'menu_items'          => array(
					'support_text'       => __( 'Support', 'wpadcenter' ),
					'support_url'        => $support_url,
					'documentation_text' => __( 'Documentation', 'wpadcenter' ),
					'documentation_url'  => 'https://docs.wpeka.com/wp-adcenter/?utm_source=wpadcenter&utm_medium=help-mascot&utm_campaign=link&utm_content=documentation',
					'faq_text'           => __( 'FAQ', 'wpadcenter' ),
					'faq_url'            => 'https://docs.wpeka.com/wp-adcenter/faq/?utm_source=wpadcenter&utm_medium=help-mascot&utm_campaign=link&utm_content=faq',
					'upgrade_text'       => __( 'Upgrade to Pro &raquo;', 'wpadcenter' ),
					'upgrade_url'        => 'https://club.wpeka.com/product/wpadcenter/?utm_source=wpadcenter&utm_medium=help-mascot&utm_campaign=link&utm_content=upgrade-to-pro',
				),
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'          => wp_create_nonce( 'admin-ajax-nonce' ),
				'is_pro'              => $is_pro,
				'disabled'            => $disabled,
				'welcome_text'        => __( 'Welcome to WP AdCenter!', 'wpadcenter' ),
				'welcome_subtext'     => __( 'Complete Ad Management Plugin.', 'wpadcenter' ),
				'welcome_description' => __( 'Thank you for choosing WP AdCenter plugin - the powerful WordPress ads plugin.', 'wpadcenter' ),
				'welcome_sub_desc'    => __( 'You can control every aspect of ads on your website. Place ads or Ad scripts anywhere on your website. Compatible with Gutenberg & Popular Page Builders.', 'wpadcenter' ),
				'quick_links_text'    => __( 'See Quick Links', 'wpadcenter' ),
				'separator_text'      => __( '--- OR ---', 'wpadcenter' ),
				'configure'           => array(
					'text'           => __( 'Configure WP AdCenter Settings including:', 'wpadcenter' ),
					'button_text'    => __( 'Configure WP AdCenter', 'wpadcenter' ),
					'url'            => admin_url() . 'edit.php?post_type=wpadcenter-ads&page=wpadcenter-settings',
					'settings_items' => apply_filters(
						'wpadcenter_settings_items',
						array(
							__( 'Disable tracking for Admin and other user roles.', 'wpadcenter' ),
							__( 'Scripts', 'wpadcenter' ),
							__( 'ads.txt', 'wpadcenter' ),
							__( 'Integrate AdSense Account', 'wpadcenter' ),
						)
					),
				),
			)
		);
		?>
		<div id="adc-gettingstarted-app"></div>
		<div id="adc-mascot-app"></div>
		<?php
	}

	/**
	 * Ajax when ad group is selected in reports page.
	 */
	public function wpadcenter_ad_group_selected() {
		// check nonce security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'adgroups_security', 'security' );
		}
		if ( 'selected_adgroup_reports' === $_POST['action'] ) {
			$selected_ad_group = isset( $_POST['selected_ad_group'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_ad_group'] ) ) : '';
			$args              = array(
				'post_type' => 'wpadcenter-ads',
				'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'wpadcenter-adgroups',
						'terms'    => $selected_ad_group,
					),
				),
			);
			$the_query         = new WP_Query( $args );
			$return_array      = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$ad_id    = get_the_ID();
					$ad_title = ! empty( get_the_title() ) ? get_the_title() : __( '(no title)', 'wpadcenter' );
					$ad_meta  = get_post_meta( $ad_id, 'wpadcenter_ads_stats', true );
					$temp     = array(
						'ad_id'    => $ad_id,
						'ad_title' => $ad_title,
						'ad_meta'  => $ad_meta,
					);
					array_push( $return_array, $temp );
				}
			}
			// echo reports data as per ad group selected and die.
			echo wp_json_encode( $return_array );
			wp_die();
		}
	}

	/**
	 * Ajax when ad is selected in reports custom-reports page.
	 */
	public function wpadcenter_ad_selected() {
		// check nonce security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'selectad_security', 'security' );
		}

		if ( 'selected_ad_reports' === $_POST['action'] ) {
			$start_date = isset( $_POST['start_date'] ) ? gmdate( 'Y-m-d', intval( $_POST['start_date'] ) ) : '';
			$end_date   = isset( $_POST['end_date'] ) ? gmdate( 'Y-m-d', intval( $_POST['end_date'] ) ) : '';
			$ads = $_POST['selected_ad']; // phpcs:ignore
			$ad_ids     = array();
			if ( is_array( $ads ) ) {
				foreach ( $ads as $ad ) {
					$ad_id = intval( $ad['ad_id'] );
					array_push( $ad_ids, $ad_id );
				}
			}

			if ( '' === $start_date || '' === $end_date || ! count( $ad_ids ) ) {
				$return_array = array( 'error' => 'Error' );
				echo wp_json_encode( $return_array );
			}
			global $wpdb;
			$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date BETWEEN %s AND %s AND ad_id IN (' . implode( ',', $ad_ids ) . ')', array( $start_date, $end_date ) ) ); // phpcs:ignore
			if ( is_array( $records ) ) {
				foreach ( $records as $record ) {
					$record->ad_title = ! empty( get_the_title( intval( $record->ad_id ) ) ) ? get_the_title( intval( $record->ad_id ) ) : __( '(no title)', 'wpadcenter' );
				}
				echo wp_json_encode( $records );
			}
			wp_die();
		}
	}

	/**
	 * Post request when export csv is called on custom-reports page.
	 */
	public function wpadcenter_export_csv() {
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'exportcsv_security', 'security' );
		}
		$filename       = $this->plugin_name . '-stats';
		$generated_date = gmdate( 'd-m-Y His' );
		$csv_string     = isset( $_POST['csv_data'] ) ? sanitize_textarea_field( wp_unslash( $_POST['csv_data'] ) ) : '';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . ' ' . $generated_date . '.csv";' );
		echo wp_kses_data( $csv_string );
		die();
	}

	/**
	 * Dequeue forms.css.
	 *
	 * @param string $href url of styles in loop.
	 */
	public function wpadcanter_dequeue_styles( $href ) {
		if ( is_admin() ) {
			$my_current_screen = get_current_screen();
			if ( isset( $my_current_screen->post_type ) && ( 'wpadcenter-ads_page_wpadcenter-reports' === $my_current_screen->base || 'wpadcenter-ads_page_wpadcenter-settings' === $my_current_screen->base ) ) {
				if ( strpos( $href, 'forms.css' ) !== false || strpos( $href, 'revisions' ) ) {
					return false;
				}
			}
		}
		return $href;
	}
	/**
	 * Dequeue forms.css && revisions.css for newer version of WordPress.
	 *
	 * @param array $to_dos .
	 */
	public function wpadcenter_remove_forms_style( $to_dos ) {
		if ( is_admin() ) {
			$my_current_screen = get_current_screen();

			if ( isset( $my_current_screen->post_type ) && ( 'wpadcenter-ads_page_wpadcenter-reports' === $my_current_screen->base || 'wpadcenter-ads_page_wpadcenter-settings' === $my_current_screen->base ) && ( in_array( 'forms', $to_dos, true ) || in_array( 'revisions', $to_dos, true ) ) ) {
				$key = array_search( 'forms', $to_dos, true );
				unset( $to_dos[ $key ] );
				$key = array_search( 'revisions', $to_dos, true );
				unset( $to_dos[ $key ] );
			}
		}
		return $to_dos;
	}

	/**
	 * Registers gutenberg block for single ads.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_gutenberg_blocks() {

		wp_register_script(
			'wpadcenter-gutenberg-single-ad',
			plugin_dir_url( __DIR__ ) . 'admin/js/gutenberg-blocks/wpadcenter-gutenberg-singlead.js',
			array( 'wp-blocks', 'wp-api-fetch', 'wp-components', 'wp-i18n' ),
			$this->version,
			false
		);
		wp_localize_script( 'wpadcenter-gutenberg-single-ad', 'wpadcenter_singlead_verify', array( 'singlead_nonce' => wp_create_nonce( 'singlead_nonce' ) ) );
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				'wpadcenter/single-ad',
				array(
					'editor_script'   => 'wpadcenter-gutenberg-single-ad',
					'attributes'      => array(
						'ad_id'           => array(
							'type' => 'number',
						),
						'ad_alignment'    => array(
							'type' => 'string',
						),
						'max_width_check' => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'max_width_px'    => array(
							'type'    => 'string',
							'default' => '100',
						),

					),
					'render_callback' => array( $this, 'gutenberg_display_single_ad_cb' ),
				)
			);
		}
		wp_register_script(
			'wpadcenter-gutenberg-adgroup',
			plugin_dir_url( __DIR__ ) . 'admin/js/gutenberg-blocks/wpadcenter-gutenberg-adgroup.js',
			array( 'wp-blocks', 'wp-api-fetch', 'wp-components', 'wp-i18n' ),
			$this->version,
			false
		);
		wp_localize_script( 'wpadcenter-gutenberg-adgroup', 'wpadcenter_adgroup_verify', array( 'adgroup_nonce' => wp_create_nonce( 'adgroup_nonce' ) ) );
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				'wpadcenter/adgroup',
				array(
					'editor_script'   => 'wpadcenter-gutenberg-adgroup',
					'attributes'      => array(
						'ad_ids'            => array(
							'type' => 'array',
						),
						'adgroup_ids'       => array(
							'type' => 'array',
						),
						'adroups'           => array(
							'type' => 'array',
						),
						'adgroup_alignment' => array(
							'type' => 'string',
						),
						'num_ads'           => array(
							'type' => 'string',
						),
						'num_columns'       => array(
							'type' => 'string',
						),
						'max_width_check'   => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'max_width_px'      => array(
							'type'    => 'string',
							'default' => '100',
						),

					),
					'render_callback' => array( $this, 'gutenberg_display_adgroup_cb' ),
				)
			);
		}

		wp_register_script(
			'wpadcenter-gutenberg-random-ad',
			plugin_dir_url( __DIR__ ) . 'admin/js/gutenberg-blocks/wpadcenter-gutenberg-randomad.js',
			array( 'wp-blocks', 'wp-api-fetch', 'wp-components', 'wp-i18n' ),
			$this->version,
			false
		);
		wp_localize_script( 'wpadcenter-gutenberg-random-ad', 'wpadcenter_random_ad_verify', array( 'random_ad_nonce' => wp_create_nonce( 'random_ad_nonce' ) ) );
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				'wpadcenter/random-ad',
				array(
					'editor_script'   => 'wpadcenter-gutenberg-random-ad',
					'attributes'      => array(
						'adgroup_ids'       => array(
							'type' => 'array',
						),
						'adroups'           => array(
							'type' => 'array',
						),
						'adgroup_alignment' => array(
							'type' => 'string',
						),
						'max_width_check'   => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'max_width_px'      => array(
							'type'    => 'string',
							'default' => '100',
						),

					),
					'render_callback' => array( $this, 'gutenberg_display_random_ad_cb' ),
				)
			);
		}

	}


	/**
	 * Renders gutenberg single ad on frontend.
	 *
	 * @param array $attributes contains attributes of the ads.
	 *
	 * @since 1.0.0
	 */
	public function gutenberg_display_single_ad_cb( $attributes ) {

		$ad_id = 0;
		if ( array_key_exists( 'ad_id', $attributes ) ) {
			$ad_id = $attributes['ad_id'];
		}

		$ad_attributes = array();
		$ad_alignment  = 'alignnone';
		if ( array_key_exists( 'ad_alignment', $attributes ) ) {
			$ad_alignment = $attributes['ad_alignment'];
		}
		$max_width_check = false;
		if ( array_key_exists( 'max_width_check', $attributes ) ) {

			$max_width_check = boolval( $attributes['max_width_check'] );
		}
		$max_width_px = '100';
		if ( array_key_exists( 'max_width_px', $attributes ) ) {
			$max_width_px = $attributes['max_width_px'];
		}

		$ad_attributes = array(
			'align'        => $ad_alignment,
			'max_width'    => $max_width_check,
			'max_width_px' => $max_width_px,
		);

		return Wpadcenter_Public::display_single_ad( $ad_id, $ad_attributes );
	}

	/**
	 * Renders gutenberg adgroup on frontend.
	 *
	 * @param array $attributes contains attributes of the ads.
	 *
	 * @since 1.0.0
	 */
	public function gutenberg_display_adgroup_cb( $attributes ) {
		$adgroup_ids = array();
		if ( array_key_exists( 'adgroup_ids', $attributes ) ) {
			$adgroup_ids = $attributes['adgroup_ids'];
		}
		$adgroup_alignment = 'alignnone';
		if ( array_key_exists( 'adgroup_alignment', $attributes ) ) {
			$adgroup_alignment = $attributes['adgroup_alignment'];
		}
			$num_ads = '1';
		if ( array_key_exists( 'num_ads', $attributes ) ) {
			$num_ads = $attributes['num_ads'];
		}
			$num_columns = '1';
		if ( array_key_exists( 'num_columns', $attributes ) ) {
			$num_columns = $attributes['num_columns'];
		}
		$max_width_check = false;
		if ( array_key_exists( 'max_width_check', $attributes ) ) {

			$max_width_check = boolval( $attributes['max_width_check'] );
		}
		$max_width_px = '100';
		if ( array_key_exists( 'max_width_px', $attributes ) ) {
			$max_width_px = $attributes['max_width_px'];
		}
			$adgroup_attributes = array(
				'adgroup_ids'  => $adgroup_ids,
				'align'        => $adgroup_alignment,
				'num_ads'      => $num_ads,
				'num_columns'  => $num_columns,
				'max_width'    => $max_width_check,
				'max_width_px' => $max_width_px,
			);
			return Wpadcenter_Public::display_adgroup_ads( $adgroup_attributes );

	}

	/**
	 * Renders gutenberg random ad on frontend.
	 *
	 * @param array $attributes contains attributes of the ads.
	 *
	 * @since 1.0.0
	 */
	public function gutenberg_display_random_ad_cb( $attributes ) {
		$adgroup_ids = array();
		if ( array_key_exists( 'adgroup_ids', $attributes ) ) {
			$adgroup_ids = $attributes['adgroup_ids'];
		}
		$adgroup_alignment = 'alignnone';
		if ( array_key_exists( 'adgroup_alignment', $attributes ) ) {
			$adgroup_alignment = $attributes['adgroup_alignment'];
		}

		$max_width_check = false;
		if ( array_key_exists( 'max_width_check', $attributes ) ) {

			$max_width_check = boolval( $attributes['max_width_check'] );
		}
		$max_width_px = '100';
		if ( array_key_exists( 'max_width_px', $attributes ) ) {
			$max_width_px = $attributes['max_width_px'];
		}
			$random_ad_attributes = array(
				'adgroup_ids'  => $adgroup_ids,
				'align'        => $adgroup_alignment,
				'max_width'    => $max_width_check,
				'max_width_px' => $max_width_px,
			);
			return Wpadcenter_Public::display_random_ad( $random_ad_attributes );

	}



	/**
	 * Registers single ads widget.
	 *
	 * @param array $categories contains categories of gutenberg block.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_gutenberg_block_categories( $categories ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'wpadcenter',
					'title' => __( 'WPAdCenter', 'wpadcenter' ),
				),
			)
		);
	}

	/**
	 * Registers rest api field for wpadceter-ads.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_rest_fields() {
		register_rest_field(
			'wpadcenter-ads',
			'ad_html',
			array(
				'get_callback' => array( $this, 'wpadcenter_ad_html_rest_field_cb' ),
				'schema'       => null,
			)
		);
		register_rest_field(
			'wpadcenter-adgroups',
			'ad_ids',
			array(
				'get_callback' => array( $this, 'wpadcenter_ad_ids_rest_field_cb' ),
				'schema'       => null,
			)
		);
		register_rest_field(
			'wpadcenter-ads',
			'ad_type',
			array(
				'get_callback' => array( $this, 'wpadcenter_ad_type_rest_field_cb' ),
				'schema'       => null,
			)
		);
		register_rest_field(
			'wpadcenter-ads',
			'ad_size',
			array(
				'get_callback' => array( $this, 'wpadcenter_ad_size_rest_field_cb' ),
				'schema'       => null,
			)
		);
	}

	/**
	 * Assigns value to the rest api filed ad_html.
	 *
	 * @param object $object contains the post inforamtion.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_html_rest_field_cb( $object ) {
		$ad_id = $object['id'];
		return Wpadcenter_Public::display_single_ad( $ad_id );
	}

	/**
	 * Assigns ad ids of the adgroup to the rest api field .
	 *
	 * @param object $object contains the post inforamtion.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_ids_rest_field_cb( $object ) {

		$current_time = time();

		$args = array(
			'post_type'     => 'wpadcenter-ads',
			'tax_query'      => array( //phpcs:ignore
				array(
					'taxonomy' => 'wpadcenter-adgroups',
					'field'    => 'id',
					'terms'    => $object['id'],
				),
			),
			'meta_query'     => array( //phpcs:ignore
				array(
					'key'     => 'wpadcenter_start_date',
					'value'   => $current_time,
					'type'    => 'numeric',
					'compare' => '<=',
				),
				array(
					'key'     => 'wpadcenter_end_date',
					'value'   => $current_time,
					'type'    => 'numeric',
					'compare' => '>=',
				),
			),

			'no_found_rows' => true,
		);
		$ad_ids = array();
		$ads    = new WP_Query( $args );
		if ( $ads->have_posts() ) {
			while ( $ads->have_posts() ) {

				$ads->the_post();
				array_push( $ad_ids, get_the_ID() );
			}
			return $ad_ids;
		} else {
			return;
		}

	}

	/**
	 * Assigns value to the rest api filed ad_type.
	 *
	 * @param object $object contains the post inforamtion.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_type_rest_field_cb( $object ) {
		$ad_id          = $object['id'];
		$ad_type        = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
		$ad_types_array = self::get_default_ad_types();
		return $ad_types_array[ $ad_type ];
	}

	/**
	 * Assigns value to the rest api filed ad_size.
	 *
	 * @param object $object contains the post inforamtion.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_size_rest_field_cb( $object ) {
		$ad_id   = $object['id'];
		$ad_size = get_post_meta( $ad_id, 'wpadcenter_ad_size', true );	
		return $ad_size;
	}

	/**
	 * Ajax when ad is selected in reports custom-reports page.
	 */
	public function wpadcenter_get_roles() {
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'roles_security', 'security' );
		}
		$return_array = array();
		$roles        = get_editable_roles();
		if ( is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				array_push( $return_array, $role['name'] );
			}
		}
		$the_options = Wpadcenter::wpadcenter_get_settings();
		array_push( $return_array, $the_options['roles_selected'] );
		array_push( $return_array, $the_options['roles_selected_visibility'] );
		echo wp_json_encode( $return_array );
		wp_die();
	}

	/**
	 * Ajax for getting ad groups from server.
	 */
	public function wpadcenter_get_adgroups() {
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'adgroups_security', 'security' );
		}
		$array = get_terms( 'wpadcenter-adgroups', array( 'hide_empty' => false ) );
		echo wp_json_encode( $array );
		wp_die();
	}

	/**
	 * Header, body and footer scripts meta boxes on pages and/or posts.
	 */
	public function wpadcenter_page_posts_scripts() {
		$screens = array( 'post', 'page' );

		foreach ( $screens as $screen ) {

			add_meta_box(
				'wpadcenter_scripts',
				__( 'WPAdCenter Scripts', 'wpadcenter' ),
				array( $this, 'wpadcenter_page_posts_metabox_render' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Header, body and footer scripts meta boxes render on pages and/or posts.
	 *
	 * @param Object $post Post object.
	 */
	public function wpadcenter_page_posts_metabox_render( $post ) {
		$array = get_post_meta( $post->ID, 'scripts', true );
		wp_enqueue_style( $this->plugin_name );
		wp_nonce_field( 'action', 'nonce' );
		?>
			<table class="wpadcenter-table">
				<tr>
					<td class="wpadcenter-left-cell"><label for="disable_global_scripts"><?php esc_html_e( 'Disable Global Scripts', 'wpadcenter' ); ?></label></td>
					<td class="wpadcenter-right-cell"><input type="checkbox" id="disable_global_scripts" name="disable_global_scripts" <?php checked( isset( $array['disable_global_scripts'] ) ? $array['disable_global_scripts'] : false, 'on' ); ?>></td>
				</tr>
				<tr class="wpadcenter-table-tr">
					<td class="wpadcenter-left-cell"><label for="header_scripts"><?php esc_html_e( 'Header Scripts', 'wpadcenter' ); ?></label></td>
					<td class="wpadcenter-right-cell">
						<textarea name="header_scripts" id="header_scripts" rows="6"><?php echo( isset( $array['header_scripts'] ) ? esc_attr( $array['header_scripts'] ) : '' ); ?></textarea>
						<small><?php esc_html_e( 'These scripts will be printed in head section.', 'wpadcenter' ); ?></small>
					</td>
				</tr>
				<tr class="wpadcenter-table-tr">
					<td class="wpadcenter-left-cell"><label for="body_scripts"><?php esc_html_e( 'Body Scripts', 'wpadcenter' ); ?></label></td>
					<td class="wpadcenter-right-cell">
						<textarea name="body_scripts" id="body_scripts" rows="6"><?php echo( isset( $array['body_scripts'] ) ? esc_attr( $array['body_scripts'] ) : '' ); ?></textarea>
						<small><?php esc_html_e( 'These scripts will be printed in body section.', 'wpadcenter' ); ?></small>
					</td>
				</tr>
				<tr class="wpadcenter-table-tr">
					<td class="wpadcenter-left-cell"><label for="footer_scripts"><?php esc_html_e( 'Footer Scripts', 'wpadcenter' ); ?></label></td>
					<td class="wpadcenter-right-cell">
						<textarea name="footer_scripts" id="footer_scripts" rows="6"><?php echo( isset( $array['footer_scripts'] ) ? esc_attr( $array['footer_scripts'] ) : '' ); ?></textarea>
						<small><?php esc_html_e( 'These scripts will be printed in footer section.', 'wpadcenter' ); ?></small>
					</td>
				</tr>
			</table>
		<?php
	}
	/**
	 * Save scripts.
	 *
	 * @param int $post_id post id of the post getting saved.
	 */
	public function wpadcenter_save_scripts( $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$nonce_checker = empty( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
		if ( ! wp_verify_nonce( $nonce_checker, 'action' ) ) {
			return;
		}

		$disable_global_scripts = isset( $_POST['disable_global_scripts'] ) ? sanitize_text_field( wp_unslash( $_POST['disable_global_scripts'] ) ) : 'off';
		$header_scripts         = isset( $_POST['header_scripts'] ) ? wp_unslash( $_POST['header_scripts'] ) : ''; // phpcs:ignore
		$body_scripts           = isset( $_POST['body_scripts'] ) ? wp_unslash( $_POST['body_scripts'] ) : ''; // phpcs:ignore
		$footer_scripts         = isset( $_POST['footer_scripts'] ) ? wp_unslash( $_POST['footer_scripts'] ) : ''; // phpcs:ignore

		$array = array(
			'disable_global_scripts' => $disable_global_scripts,
			'header_scripts'         => $header_scripts,
			'body_scripts'           => $body_scripts,
			'footer_scripts'         => $footer_scripts,
		);
		update_post_meta( $post_id, 'scripts', $array );
	}

	/**
	 * Registers wpadcenter widgets.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_widgets() {
		register_widget( 'Wpadcenter_Single_Ad_Widget' );
		register_widget( 'Wpadcenter_Adgroup_Widget' );
		register_widget( 'Wpadcenter_Random_Ad_Widget' );

	}

	/**
	 * Remove permalink from create ad page.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_remove_permalink() {

		global $post_type;

		if ( 'wpadcenter-ads' === $post_type ) {
			echo '<style>#edit-slug-box {display:none;}</style>';
		}
	}

	/**
	 * Provides adgroup html for gutenberg preview.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_adgroup_gutenberg_preview() {
		if ( ! isset( $_POST['adgroup_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['adgroup_nonce'] ), 'adgroup_nonce' ) ) {
			wp_die();
		}
		$adgroup_ids = array();
		if ( ! empty( $_POST['ad_groups'] ) ) {
			$adgroup_ids = $_POST['ad_groups']; //phpcs:ignore
		}
		$adgroup_alignment = 'alignnone';
		if ( ! empty( $_POST['alignment'] ) ) {
			$adgroup_alignment = sanitize_text_field( wp_unslash( $_POST['alignment'] ) );
		}
			$num_ads = '1';
		if ( ! empty( $_POST['num_ads'] ) ) {
			$num_ads = sanitize_text_field( wp_unslash( $_POST['num_ads'] ) );
		}
			$num_columns = '1';
		if ( ! empty( $_POST['num_columns'] ) ) {
			$num_columns = sanitize_text_field( wp_unslash( $_POST['num_columns'] ) );
		}
		$max_width_check = false;
		if ( ! empty( $_POST['max_width_check'] ) ) {

			$checked = sanitize_text_field( wp_unslash( $_POST['max_width_check'] ) );
			if ( 'true' === $checked ) {
				$max_width_check = true;
			} else {
				$max_width_check = false;
			}
		}
		$max_width_px = '100';
		if ( ! empty( $_POST['max_width_px'] ) ) {
			$max_width_px = sanitize_text_field( wp_unslash( $_POST['max_width_px'] ) );
		}
			$adgroup_attributes = array(
				'adgroup_ids'  => $adgroup_ids,
				'align'        => $adgroup_alignment,
				'num_ads'      => $num_ads,
				'num_columns'  => $num_columns,
				'max_width'    => $max_width_check,
				'max_width_px' => $max_width_px,

			);
			echo Wpadcenter_Public::display_adgroup_ads( $adgroup_attributes ); //phpcs:ignore
			wp_die();
	}

	/**
	 * Provides singlead html for gutenberg preview.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_singlead_gutenberg_preview() {
		if ( ! isset( $_POST['singlead_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['singlead_nonce'] ), 'singlead_nonce' ) ) {
			wp_die();
		}

		$ad_id = 0;
		if ( ! empty( $_POST['ad_id'] ) ) {
			$ad_id = sanitize_text_field( wp_unslash( $_POST['ad_id'] ) );
		}
		$ad_alignment = 'alignnone';
		if ( ! empty( $_POST['alignment'] ) ) {
			$ad_alignment = sanitize_text_field( wp_unslash( $_POST['alignment'] ) );
		}
		$max_width_check = false;
		if ( ! empty( $_POST['max_width_check'] ) ) {

			$checked = sanitize_text_field( wp_unslash( $_POST['max_width_check'] ) );
			if ( 'true' === $checked ) {
				$max_width_check = true;
			} else {
				$max_width_check = false;
			}
		}
		$max_width_px = '100';
		if ( ! empty( $_POST['max_width_px'] ) ) {
			$max_width_px = sanitize_text_field( wp_unslash( $_POST['max_width_px'] ) );
		}
			$singlead_attributes = array(
				'align'        => $ad_alignment,
				'max_width'    => $max_width_check,
				'max_width_px' => $max_width_px,
			);
			echo Wpadcenter_Public::display_single_ad( $ad_id,$singlead_attributes ); //phpcs:ignore
			wp_die();
	}

	/**
	 * Provides random ad html for gutenberg preview.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_random_ad_gutenberg_preview() {

		if ( ! isset( $_POST['random_ad_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['random_ad_nonce'] ), 'random_ad_nonce' ) ) {
			wp_die();
		}
		$adgroup_ids = array();
		if ( ! empty( $_POST['ad_groups'] ) ) {
			$adgroup_ids = $_POST['ad_groups']; //phpcs:ignore
		}
		$adgroup_alignment = 'alignnone';
		if ( ! empty( $_POST['alignment'] ) ) {
			$adgroup_alignment = sanitize_text_field( wp_unslash( $_POST['alignment'] ) );
		}

		$max_width_check = false;
		if ( ! empty( $_POST['max_width_check'] ) ) {

			$checked = sanitize_text_field( wp_unslash( $_POST['max_width_check'] ) );
			if ( 'true' === $checked ) {
				$max_width_check = true;
			} else {
				$max_width_check = false;
			}
		}
		$max_width_px = '100';
		if ( ! empty( $_POST['max_width_px'] ) ) {
			$max_width_px = sanitize_text_field( wp_unslash( $_POST['max_width_px'] ) );
		}
			$random_ad_attributes = array(
				'adgroup_ids'  => $adgroup_ids,
				'align'        => $adgroup_alignment,
				'max_width'    => $max_width_check,
				'max_width_px' => $max_width_px,

			);
			echo Wpadcenter_Public::display_random_ad( $random_ad_attributes ); //phpcs:ignore
			wp_die();
	}

	/**
	 * Removes quick edit and views option from the manage ads page.
	 *
	 * @param array $actions contains the post row actions.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_remove_post_row_actions( $actions ) {
		global $current_screen;

		if ( 'wpadcenter-ads' === $current_screen->post_type ) {
			unset( $actions['view'] );
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Adds custom filter to manage ads page.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_add_custom_filters() {
		global $current_screen;

		$type = $current_screen->post_type;

		if ( 'wpadcenter-ads' === $type ) {

			wp_nonce_field( 'wpadcenter_add_custom_filter', 'wpadcenter_add_custom_filter_nonce' );
			$ad_types = $this->get_default_ad_types();
			?>
			<select name="ADMIN_FILTER_FIELD_AD_TYPE">
			<option value=""><?php esc_html_e( 'All ad types', 'wpadcenter' ); ?></option>
			<?php
				$current_v = isset( $_GET['ADMIN_FILTER_FIELD_AD_TYPE'] ) ? sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_TYPE'] ) ) : ''; //phpcs:ignore
			foreach ( $ad_types as $value => $label ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $value ),
					$value === $current_v ? ' selected="selected"' : '',
					esc_html( $label )
				);
			}
			?>
			</select>
			<?php
			$ad_sizes = $this->get_default_ad_sizes();
			?>
			<select name="ADMIN_FILTER_FIELD_AD_SIZE">
			<option value=""><?php esc_html_e( 'All ad dimensions', 'wpadcenter' ); ?></option>
			<?php
				$current_v = isset( $_GET['ADMIN_FILTER_FIELD_AD_SIZE'] ) ? sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_SIZE'] ) ) : '';//phpcs:ignore
			foreach ( $ad_sizes as $size => $data ) {
				if ( 'none' === $size ) {
					printf(
						'<option value="%s"%s>%s</option>',
						esc_attr( $size ),
						$size === $current_v ? ' selected="selected"' : '',
						esc_html__( 'default', 'wpadcenter' )
					);
				} elseif ( 'ad-size' === $data[1] ) {
					printf(
						'<option value="%s"%s>%s</option>',
						esc_attr( $size ),
						$size === $current_v ? ' selected="selected"' : '',
						esc_html( $data[0] )
					);
				}
			}

			?>
			</select>
			<?php
			$terms = get_terms(
				array(
					'taxonomy' => 'wpadcenter-adgroups',
				)
			);
			?>
			<select name="ADMIN_FILTER_FIELD_AD_GROUP">
			<option value=""><?php esc_html_e( 'All ad groups', 'wpadcenter' ); ?></option>
			<?php
				$current_v = isset( $_GET['ADMIN_FILTER_FIELD_AD_GROUP'] ) ? sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_GROUP'] ) ) : '';//phpcs:ignore
			foreach ( $terms as $term ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $term->term_id ),
					$term->term_id === $current_v ? ' selected="selected"' : '',
					esc_html( $term->name )
				);
			}

			?>
			</select>
			<?php

			$the_options = \Wpadcenter::wpadcenter_get_settings();

			if ( get_option( 'wpadcenter_pro_active' ) && $the_options['enable_advertisers'] ) {

				$advertisers = get_users( array( 'role' => 'advertiser' ) );

				?>
			<select name="ADMIN_FILTER_FIELD_ADVERTISER">
			<option value=""><?php esc_html_e( 'All advertisers', 'wpadcenter' ); ?></option>
				<?php
				$current_v = isset( $_GET['ADMIN_FILTER_FIELD_ADVERTISER'] ) ? sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_ADVERTISER'] ) ) : '';//phpcs:ignore
				foreach ( $advertisers as $advertiser ) {
					printf(
						'<option value="%s"%s>%s</option>',
						esc_attr( $advertiser->data->ID ),
						$advertiser->data->ID === $current_v ? ' selected="selected"' : '',
						esc_html( $advertiser->data->display_name )
					);
				}

				?>
			</select>
				<?php
			}
		}
	}

	/**
	 * Filters ads.
	 *
	 * @param object $query contains the query to be made for posts on manage ads page.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_custom_filters_query( $query ) {

		if ( ! isset( $_GET['wpadcenter_add_custom_filter_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_key( $_GET['wpadcenter_add_custom_filter_nonce'] ), 'wpadcenter_add_custom_filter' ) ) {
			return;
		}

		global $pagenow;
		$type = 'post';
		if ( isset( $_GET['post_type'] ) ) {
			$type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) );
		}
		if ( 'wpadcenter-ads' === $type && is_admin() && 'edit.php' === $pagenow ) {
			if ( isset( $_GET['ADMIN_FILTER_FIELD_AD_TYPE'] ) && '' !== $_GET['ADMIN_FILTER_FIELD_AD_TYPE'] ) {
				$query->query_vars['meta_query'][] =
				array(
					'key'     => 'wpadcenter_ad_type',
					'value'   => sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_TYPE'] ) ),
					'compare' => 'LIKE',
				);

			}
			if ( isset( $_GET['ADMIN_FILTER_FIELD_AD_SIZE'] ) && '' !== $_GET['ADMIN_FILTER_FIELD_AD_SIZE'] ) {

				$query->query_vars['meta_query'][] = array(
					'key'     => 'wpadcenter_ad_size',
					'value'   => sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_SIZE'] ) ),
					'compare' => 'LIKE',
				);

			}
			if ( isset( $_GET['ADMIN_FILTER_FIELD_AD_GROUP'] ) && '' !== $_GET['ADMIN_FILTER_FIELD_AD_GROUP'] ) {
				$query->query_vars['tax_query'][] =
					array(
						'taxonomy' => 'wpadcenter-adgroups',
						'field'    => 'term_id',
						'terms'    => sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_AD_GROUP'] ) ),
					);

			}

			if ( isset( $_GET['ADMIN_FILTER_FIELD_ADVERTISER'] ) && '' !== $_GET['ADMIN_FILTER_FIELD_ADVERTISER'] ) {

				$query->query_vars['meta_query'][] = array(
					'key'     => 'wpadcenter_advertiser',
					'value'   => sanitize_text_field( wp_unslash( $_GET['ADMIN_FILTER_FIELD_ADVERTISER'] ) ),
					'compare' => 'LIKE',
				);

			}
		}

	}

	/**
	 * Adds amp admin notice in the front end.
	 *
	 * @since 4.0.0
	 */
	public function wpadcenter_pro_display_amp_warning() {
		$amp_plugin_installed = function_exists( 'is_amp_endpoint' ) || function_exists( 'is_wp_amp' ) || function_exists( 'ampforwp_is_amp_endpoint' );

		if ( ! $amp_plugin_installed ) {
				echo '<div id="wpadcenter_amp_warning" class="notice notice-warning">
						<p>Please activate an AMP plugin. AMP ads are only visible on AMP pages.</p>
					</div>';
		}
			wp_die();
	}

}

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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css', array(), $this->version, 'all' ); // styles for datepicker.
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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'jquery' ), $this->version, false );

		wp_register_script( $this->plugin_name . 'moment', plugin_dir_url( __FILE__ ) . 'lib/moment/moment.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . 'moment-timezone', plugin_dir_url( __FILE__ ) . 'lib/moment/moment-timezone.min.js', array( 'jquery' ), $this->version, false );

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
			'wpadcenter_cpt_args_ads',
			array(
				'labels'              => apply_filters(
					'wpadcenter_cpt_args_labels_ads',
					array(
						'name'               => __( 'WPAdCenter: Ads', 'wpadcenter' ),
						'singular_name'      => __( 'Ad', 'wpadcenter' ),
						'menu_name'          => __( 'WPAdCenter', 'wpadcenter' ),
						'all_items'          => __( 'Manage Ads', 'wpadcenter' ),
						'add_new_item'       => __( 'Create New Ad', 'wpadcenter' ),
						'add_new'            => __( 'Create New', 'wpadcenter' ),
						'new_item'           => __( 'New Ad', 'wpadcenter' ),
						'edit_item'          => __( 'Edit Ad', 'wpadcenter' ),
						'update_item'        => __( 'Update Ad', 'wpadcenter' ),
						'view_item'          => __( 'View Ad', 'wpadcenter' ),
						'view_items'         => __( 'View Ad', 'wpadcenter' ),
						'search_items'       => __( 'Search Ad', 'wpadcenter' ),
						'not_found'          => __( 'No Ads found', 'wpadcenter' ),
						'not_found_in_trash' => __( 'No Ads found in Trash', 'wpadcenter' ),
					)
				),
				'supports'            => array( 'title' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_in_rest'        => false,
				'publicly_queryable'  => false,
				'menu_icon'           => WPADCENTER_PLUGIN_URL . 'images/menu-icon.png',
				'rewrite'             => array( 'slug' => 'wpadcenter-ads' ),
				'capability_type'     => 'post',
			)
		);

		return apply_filters( 'wpadcenter_cpt_args', $cpt_args );

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
			'labels'       => $labels,
			'hierarchical' => true,
			'show_ui'      => true,
			'show_in_rest' => false,
			'rewrite'      => array( 'slug' => 'wpadcenter-adgroups' ),
		);

		register_taxonomy( 'wpadcenter-adgroups', array( 'wpadcenter-ads' ), $args );
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
			'wpadcenter-reports'
		);
		// Settings - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Settings',
			__( 'Settings', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-settings'
		);
		// Getting Started - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Getting Started',
			__( 'Getting Started', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-getting-started'
		);
		do_action( 'wpadcenter_admin_menu', 'edit.php?post_type=wpadcenter-ads', 'manage_options' ); // action to add submenu pages for pro versions
		// Getting Started - submenu.
		if ( ! get_option( 'wpadcenter_pro_active' ) ) {
			add_submenu_page(
				'edit.php?post_type=wpadcenter-ads',
				'Go Pro',
				__( 'Go Pro', 'wpadcenter' ),
				'manage_options',
				'wpadcenter-go-pro'
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
			'stats-for-today' => __( 'Stats for today', 'wpadcenter' ),
			'start-date'      => __( 'Start Date', 'wpadcenter' ),
			'end-date'        => __( 'End Date', 'wpadcenter' ),
		);
		if ( get_option( 'wpadcenter_pro_active' ) ) {
			$columns['advertiser'] = __( 'Advertiser', 'wpadcenter' );
		}
		return $columns;
	}

	/**
	 * Manage groupads columns.
	 *
	 * @since  1.0.0
	 * @return array|void
	 */
	public function wpadcenter_manage_edit_adgroups_columns() {
		global $current_screen;
		if ( 'wpadcenter-ads' !== $current_screen->post_type ) {
			return;
		}
		$columns = array(
			'cb'                   => '<input type="checkbox" />',
			'name'                 => __( 'Name', 'wpadcenter' ),
			'shortcode'            => __( 'Shortcode', 'wpadcenter' ),
			'template-tag'         => __( 'Template tag', 'wpadcenter' ),
			'number-of-ads'        => __( 'Number of ads', 'wpadcenter' ),
			'number-of-active-ads' => __( 'Number of active ads', 'wpadcenter' ),
		);
		if ( get_option( 'wpadcenter_pro_active' ) ) {
			$columns['advertiser'] = __( 'Advertiser', 'wpadcenter' );
		}
		return $columns;
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

		$sizes_list    = Wpadcenter_Admin_Helper::get_default_ad_sizes();
		$ad_types_list = Wpadcenter_Admin_Helper::get_default_ad_types();

		if ( 'ad-type' === $column ) {
			$ad_type = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
			echo esc_html( $ad_types_list[ $ad_type ] );
		}
		if ( 'ad-dimensions' === $column ) {
			$ad_size = get_post_meta( $ad_id, 'wpadcenter_ad_size', true );
			echo esc_html( $sizes_list[ $ad_size ] );
		}
		if ( 'start-date' === $column ) {
			$current_start_date = get_post_meta( $ad_id, 'wpadcenter_start_date', true );
			if ( $current_start_date ) {
				echo esc_html( gmdate( 'm/d/Y H:i:s', $current_start_date ) );
			}
		}
		if ( 'end-date' === $column ) {

			$current_end_date = get_post_meta( $ad_id, 'wpadcenter_end_date', true );
			if ( $current_end_date ) {
				echo esc_html( gmdate( 'm/d/Y H:i:s', $current_end_date ) );
			}
		}

	}



	/**
	 * Add meta boxes to create ads page.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_add_meta_boxes( $post ) {

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
			'high'
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

	}


	/**
	 * Ad-size meta box.
	 *
	 * @param WP_POST $post post object.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_size_metabox( $post ) {

		$sizes_list = Wpadcenter_Admin_Helper::get_default_ad_sizes();

		$default_size = apply_filters( 'wpadcenter_ad_size_default', '300x250' );

		$size = get_post_meta( $post->ID, 'wpadcenter_ad_size', true );
		echo '<select name="ad-size" id="size" size="1">';
		foreach ( $sizes_list as $val => $name ) {

			echo sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $val ),
				( empty( $size ) && $val === $default_size ? 'selected="selected"' : selected( $size, $val ) ),
				esc_html( $name )
			);

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
		echo '
		<div>
		<label for="open-in-new-tab"><input name="open-in-new-tab" type="checkbox" value="1" id="open-in-new-tab" ' . checked( '1', $open_in_new_tab, false ) . '> ' . esc_html__( 'Open Link In a New Tab', 'wpadcenter' ) . '</label>
		<label for="nofollow-on-link" style="margin-left:20px"><input name="nofollow-on-link" type="checkbox" value="1" id="nofollow-on-link" ' . checked( '1', $nofollow_on_link, false ) . '>' . esc_html__( 'Use nofollow on Link', 'wpadcenter' ) . '</label>
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
		echo '<textarea name="ad-google-adsense" style="width:100%;height:200px" >' . esc_textarea( $ad_google_adsense ) . '</textarea>';
		echo '<a href=# >Connect to adsense</a>';
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

		$ad_types_list = Wpadcenter_Admin_Helper::get_default_ad_types();

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
	 * Save ad meta data.
	 *
	 * @param integer $post_id post id of post being saved.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_save_ad_meta( $post_id ) {

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

		$raw_data = $_POST;

		$metafields = Wpadcenter_Admin_Helper::get_default_metafields();

		foreach ( $metafields as $meta_name => $meta_data ) {

			$sanitized_data = false;

			switch ( $meta_data[1] ) {

				case 'string':
					$sanitized_data = sanitize_text_field( $raw_data[ $meta_name ] );
					break;
				case 'bool':
					$sanitized_data = isset( $raw_data[ $meta_name ] ) ? (bool) $raw_data[ $meta_name ] : 0;
					break;
				case 'raw':
					$sanitized_data = $raw_data[ $meta_name ];
					break;
				case 'url':
					$sanitized_data = esc_url_raw( $raw_data[ $meta_name ] );
					break;
				case 'date':
					$sanitized_data = intval( $raw_data[ $meta_name ] );
					break;
			}

			if ( true === (bool) $sanitized_data || empty( $sanitized_data ) ) {

				update_post_meta( $post_id, $meta_data[0], $sanitized_data );

			} elseif ( ! isset( $raw_data[ $meta_name ] ) && 'bool' === $meta_data[1] ) {

				delete_post_meta( $post_id, $meta_data[0] );

			}
		}

	}


	/**
	 * Renders meta boxes as per ad-type.
	 *
	 * @param WP_POST $post post being edited.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_edit_form_after_title( $post ) {

		$ad_meta_relation = Wpadcenter_Admin_Helper::get_ad_meta_relation();

		$current_ad_type = get_post_meta( $post->ID, 'wpadcenter_ad_type', true );
		$current_ad_type = ! empty( $current_ad_type ) ? $current_ad_type : 'banner_image';

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

			wp_localize_script( $this->plugin_name, 'wpadcenter_ad_scheduler', $ad_scheduler );
			wp_enqueue_script( $this->plugin_name );
			wp_enqueue_style( $this->plugin_name );

			wp_enqueue_style( $this->plugin_name . 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_enqueue_script( $this->plugin_name . 'moment' );
			wp_enqueue_script( $this->plugin_name . 'moment-timezone' );

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

			$end_time = ! $end_date ? time() : $end_date;

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




}

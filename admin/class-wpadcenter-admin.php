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
	 * @param string    $plugin_name       The name of this plugin.
	 * @param string    $version    The version of this plugin.
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->adsense     = \Wpeka\Adcenter\Wpadcenter_Adsense::get_instance();
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.css', array(), $this->version, 'all' );
		wp_register_style(
			$this->plugin_name . '-settings',
			plugin_dir_url(
				__FILE__
			) . 'css/wpadcenter-admin-settings' . WPADCENTER_SCRIPT_SUFFIX . '.css',
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'jquery' ), $this->version, false );
		wp_register_script(
			$this->plugin_name . '-settings',
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin-settings' . WPADCENTER_SCRIPT_SUFFIX . '.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		wp_register_script(
			$this->plugin_name . '-main',
			plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin-main' . WPADCENTER_SCRIPT_SUFFIX . '.js',
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
			'wpadcenter-settings',
			array( $this, 'wpadcenter_settings' )
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
			'ad-title'        => __( 'Ad Title', 'wpadcenter' ),
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
		exit();
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
}

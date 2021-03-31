<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/public
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Public {

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
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		if ( ! shortcode_exists( 'wpadcenter_ad' ) ) {
			add_shortcode( 'wpadcenter_ad', array( $this, 'wpadcenter_ad_shortcode' ) );
		}
		if ( ! shortcode_exists( 'wpadcenter_adgroup' ) ) {
			add_shortcode( 'wpadcenter_adgroup', array( $this, 'wpadcenter_adgroup_shortcode' ) );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_register_style( $this->plugin_name . '-frontend', plugin_dir_url( __FILE__ ) . 'css/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_register_script( $this->plugin_name . '-frontend', plugin_dir_url( __FILE__ ) . 'js/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Ads.txt file frontend output.
	 */
	public function wpadcenter_init() {
		if ( isset( $_SERVER['REQUEST_URI'] ) && '/ads.txt' === $_SERVER['REQUEST_URI'] ) { // phpcs:ignore input var ok, CSRF ok, sanitization ok.
			if ( is_multisite() ) {
				global $current_blog;
				$domain = $current_blog->domain;
				// Get all sites that include the current domain as part of their domains.
				$sites = get_sites(
					array(
						'search'         => $domain,
						'search_columns' => array( 'domain' ),
					)
				);
				// Uses `subdomain=` variable.
				$referrals = array();
				// Included to the ads.txt file of the current domain.
				$not_refferals = array();

				foreach ( $sites as $site ) {
					if ( get_current_blog_id() === (int) $site->blog_id ) {
						// Current domain, no need to refer.
						$not_refferals[] = $site->blog_id;
						continue;
					}

					if ( $this->wpadcenter_get_root_domain_info() ) {
						// Subdomains cannot refer to other subdomains.
						$not_refferals[] = $site->blog_id;
						continue;
					}

					if ( '/' !== $site->path ) {
						// We can refer to domains, not domains plus path.
						$not_refferals[] = $site->blog_id;
						continue;
					}

					$referrals[ $site->blog_id ] = $site->domain;
				}
				$content = '';
				if ( ! empty( $not_refferals ) ) {
					foreach ( $not_refferals as $blog_id ) {
						$the_options   = get_blog_option( $blog_id, WPADCENTER_SETTINGS_FIELD );
						$plugin_active = get_blog_option( $blog_id, 'wpadcenter_active' );
						if ( $the_options['enable_ads_txt'] && $plugin_active ) {
							$content .= '# blog_id : ' . $blog_id . "\n";
							$content .= $the_options['ads_txt_content'] . "\n";
							if ( get_current_blog_id() === $blog_id ) {
								// Refer to other subdomains.
								foreach ( $referrals  as $blog_id => $referral ) {
									$content .= '# refer to blog_id : ' . $blog_id . "\nsubdomain=" . $referral . "\n";
								}
							}
						}
					}
				}
			} else {
				$the_options = Wpadcenter::wpadcenter_get_settings();
				if ( $the_options['enable_ads_txt'] ) {
					$content = $the_options['ads_txt_content'];
				}
			}
			if ( isset( $content ) && ! empty( $content ) ) {
				header( 'Content-Type: text/plain; charset=utf-8' );
				$content = Wpadcenter::TOP . "\n" . $content;
				$content = esc_html( $content );
				echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				exit;
			}
		}
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
	 * Shortcode for single ad.
	 *
	 * @param int $atts attributes.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_ad_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'id'    => 0,
				'align' => 'none',
			),
			$atts
		);

		$ad_id      = $atts['id'];
		$attributes = array(
			'classes' => 'align' . $atts['align'],
		);
		return $this->display_single_ad( $atts['id'], $attributes ); // phpcs:ignore

	}

	/**
	 * Get single ad html.
	 *
	 * @param int   $ad_id Id of the ad that is displayed.
	 * @param array $attributes contains attributes for display function.
	 *
	 * @since 1.0.0
	 *
	 * @return string $single_ad_html html for the ad to be displayed.
	 */
	public static function display_single_ad( $ad_id, $attributes = array() ) {
		$current_url = isset( $_SERVER['REQUEST_URI'] ) ? home_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';
		if ( strstr( $current_url, 'ads.txt' ) ) {
			return;
		}

		if ( 'publish' !== get_post_status( $ad_id ) ) {
			return;
		}
		wp_enqueue_style( 'wpadcenter-frontend' );
		wp_enqueue_script( 'wpadcenter-frontend' );
		wp_localize_script(
			'wpadcenter-frontend',
			'ajax_url',
			array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'wpadcenter_set_clicks' ),
			)
		);
		$default_attributes = array(
			'classes' => '',

		);
		$attributes   = wp_parse_args( $attributes, $default_attributes );
		$current_time = time();
		$start_date   = get_post_meta( $ad_id, 'wpadcenter_start_date', true );
		$end_date     = get_post_meta( $ad_id, 'wpadcenter_end_date', true );
		if ( $current_time < $start_date || $current_time > $end_date ) {
			return;
		}
		$ad_size         = get_post_meta( $ad_id, 'wpadcenter_ad_size', true );
		$ad_type         = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
		$link_url        = get_post_meta( $ad_id, 'wpadcenter_link_url', true );
		$open_in_new_tab = get_post_meta( $ad_id, 'wpadcenter_open_in_new_tab', true );
		$nofollow        = get_post_meta( $ad_id, 'wpadcenter_nofollow_on_link', true );
		$link_target     = '_self';
		if ( true === (bool) $open_in_new_tab ) {
			$link_target = '_blank';
		}
		$width  = '';
		$height = '';
		if ( 'none' !== $ad_size ) {
			$ad_size = explode( 'x', $ad_size );
			$width   = $ad_size[0];
			$height  = $ad_size[1];
		}
		$single_ad_html  = '';
		$single_ad_html .= '<div ';
		if ( $attributes['classes'] ) {
			$single_ad_html .= 'class="wpadcenter-single-ad-default ' . $attributes['classes'] . '" ';
		}
		$single_ad_html .= '>';
		$single_ad_html .= '<div>';
		$single_ad_html .= '<a id="wpadcenter_ad" data-value=' . $ad_id . ' href="' . $link_url . '" target="' . $link_target . '" ';
		if ( true === (bool) $nofollow ) {
			$single_ad_html .= 'rel="nofollow"';
		}
		$single_ad_html .= '>';
		switch ( $ad_type ) {
			case 'banner_image':
				$banner_img      = get_the_post_thumbnail( $ad_id, array( $width, $height ) );
				$single_ad_html .= $banner_img;
				break;
			case 'external_image_link':
				$external_img_link = get_post_meta( $ad_id, 'wpadcenter_external_image_link', true );
				$single_ad_html   .= '<img width="' . $width . '" height="' . $height . '" src="' . esc_url( $external_img_link ) . '"/>';
				break;
			case 'ad_code':
				$ad_code         = get_post_meta( $ad_id, 'wpadcenter_ad_code', true );
				$single_ad_html .= '<span class="wpadcenter-ad-code">' . $ad_code . '</span>';
				break;
			case 'import_from_adsense':
				$adsense_code    = get_post_meta( $ad_id, 'wpadcenter_ad_google_adsense', true );
				$single_ad_html .= '<div style="min-height:200px;min-width: 200px" class="wpadcenter-ad-code">' . $adsense_code . '</div>';
				break;
		}
		$single_ad_html .= '</a>';
		$single_ad_html .= '</div>';
		$single_ad_html .= '</div>';

		if ( self::wpadcenter_check_exclude_roles() && Wpadcenter::is_request( 'frontend' ) ) {
			Wpadcenter::wpadcenter_set_impressions( $ad_id );
		}
		return $single_ad_html;
	}

	/**
	 * Set tracking for ad click.
	 *
	 * @since 1.0.0
	 */
	public static function wpadcenter_set_clicks() {
		global $wpdb;
		if ( isset( $_POST['action'] ) && self::wpadcenter_check_exclude_roles() && Wpadcenter::is_request( 'frontend' ) ) {
			$security = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			if ( wp_verify_nonce( $security, 'wpadcenter_set_clicks' ) ) {
				if ( isset( $_POST['ad_id'] ) && ! empty( $_POST['ad_id'] ) ) {
					$ad_id = sanitize_text_field( wp_unslash( $_POST['ad_id'] ) );
					$meta  = get_post_meta( $ad_id, 'wpadcenter_ads_stats', true );
					$today = gmdate( 'Y-m-d' );
					$meta['total_clicks']++;
					$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, $ad_id ) ) ); // db call ok; no-cache ok.
					if ( count( $records ) ) {
						$record = $records[0];
						$clicks = $record->ad_clicks + 1;
						$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'ads_statistics SET ad_clicks = %d WHERE ad_date = %s and ad_id = %d', array( $clicks, $today, $ad_id ) ) ); // db call ok; no-cache ok.
						do_action( 'wpadcenter_after_set_impressions', $clicks );
					} else {
						$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'ads_statistics` (`ad_clicks`, `ad_date`, `ad_id`) VALUES (%d,%s,%d)', array( 1, $today, $ad_id ) ) ); // db call ok; no-cache ok.
					}
					update_post_meta( $ad_id, 'wpadcenter_ads_stats', $meta );
				}
			}
		}
	}

	/**
	 * Check to exclude tracking as per settings role.
	 */
	public static function wpadcenter_check_exclude_roles() {
		global $current_user;
		$the_options = Wpadcenter::wpadcenter_get_settings();

		$user_roles = $current_user->roles;
		$user_role  = array_shift( $user_roles );
		$user_role  = '/' . $user_role . '/i';
		// if current user is not signed in else if - check for excluded roles.
		if ( '//i' === $user_role ) {
			return true;
		} elseif ( ! preg_match( $user_role, $the_options['roles_selected'] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Template redirect for header, body and footer scripts.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_template_redirect() {
		global $wp_query;
		$post_id     = isset( $wp_query->post->ID ) ? $wp_query->post->ID : 0;
		$the_options = Wpadcenter::wpadcenter_get_settings();

		if ( is_admin() || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) || ! $the_options['enable_scripts'] ) {
			return;
		}

		$body_open_supported = function_exists( 'wp_body_open' ) && version_compare( get_bloginfo( 'version' ), '5.2', '>=' );

		if ( is_singular() ) {
			$disable_global_scripts = get_post_meta( $post_id, 'scripts', true );

			add_action( 'wp_head', array( $this, 'wpadcenter_output_header_post' ) );
			if ( $body_open_supported ) {
				add_action( 'wp_body_open', array( $this, 'wpadcenter_output_body_post' ) );
			}
			add_action( 'wp_footer', array( $this, 'wpadcenter_output_footer_post' ) );
			if ( ! $disable_global_scripts || ( isset( $disable_global_scripts['disable_global_scripts'] ) && 'off' === $disable_global_scripts['disable_global_scripts'] ) ) {
				add_action( 'wp_head', array( $this, 'wpadcenter_output_header_global' ) );
				if ( $body_open_supported ) {
					add_action( 'wp_body_open', array( $this, 'wpadcenter_output_body_global' ) );
				}
				add_action( 'wp_footer', array( $this, 'wpadcenter_output_footer_global' ) );
			}
		}
		$body_open_supported = function_exists( 'wp_body_open' ) && version_compare( get_bloginfo( 'version' ), '5.2', '>=' );
		if ( is_home() ) {
			add_action( 'wp_head', array( $this, 'wpadcenter_output_header_global' ) );
			if ( $body_open_supported ) {
				add_action( 'wp_body_open', array( $this, 'wpadcenter_output_body_global' ) );
			}
			add_action( 'wp_footer', array( $this, 'wpadcenter_output_footer_global' ) );
		}
	}

	/**
	 * Output header scripts for post.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_header_post() {
		global $wp_query;
		if ( isset( $wp_query->post->ID ) ) {
			$post_id = $wp_query->post->ID; // gets the current post id displayed on screen.
			$array   = get_post_meta( $post_id, 'scripts', true );
			if ( isset( $array['header_scripts'] ) ) {
				echo "\r\n" . $array['header_scripts'] . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Output body scripts for post.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_body_post() {
		global $wp_query;
		if ( isset( $wp_query->post->ID ) ) {
			$post_id = $wp_query->post->ID; // gets the current post id displayed on screen.
			$array   = get_post_meta( $post_id, 'scripts', true );
			if ( isset( $array['body_scripts'] ) ) {
				echo "\r\n" . $array['body_scripts'] . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Output footer scripts for post.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_footer_post() {
		global $wp_query;
		if ( isset( $wp_query->post->ID ) ) {
			$post_id = $wp_query->post->ID; // gets the current post id displayed on screen.
			$array   = get_post_meta( $post_id, 'scripts', true );
			if ( isset( $array['footer_scripts'] ) ) {
				echo "\r\n" . $array['footer_scripts'] . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Output global header scripts.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_header_global() {
		$the_options    = Wpadcenter::wpadcenter_get_settings();
		$header_scripts = $the_options['header_scripts'];
		if ( $header_scripts ) {
			echo "\r\n" . $header_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output global body scripts.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_body_global() {
		$the_options  = Wpadcenter::wpadcenter_get_settings();
		$body_scripts = $the_options['body_scripts'];
		if ( $body_scripts ) {
			echo "\r\n" . $body_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output global footer scripts.
	 *
	 * @since 1.0.1
	 */
	public function wpadcenter_output_footer_global() {
		$the_options    = Wpadcenter::wpadcenter_get_settings();
		$footer_scripts = $the_options['footer_scripts'];
		if ( $footer_scripts ) {
			echo "\r\n" . $footer_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Shortcode for adgroup ads.
	 *
	 * @param int $atts attributes.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_adgroup_shortcode( $atts ) {

		$atts                = shortcode_atts(
			array(
				'adgroup_ids' => '',
				'align'       => 'none',
				'num_ads'     => 1,
				'num_columns' => 1,
			),
			$atts
		);
		$atts['adgroup_ids'] = explode( ',', $atts['adgroup_ids'] );
		$atts['align']       = 'align' . $atts['align'];

		return $this->display_adgroup_ads( $atts ); // phpcs:ignore
	}

	/**
	 * Get html for displaying ads from adgroups.
	 *
	 * @param array $attributes contains attributes for display function.
	 *
	 * @since 1.0.0
	 *
	 * @return string $adgroup_html html for the ads to be displayed.
	 */
	public static function display_adgroup_ads( $attributes = array() ) {

		wp_enqueue_style( 'wpadcenter-frontend' );

		$default_attributes = array(
			'adgroup_ids' => array(),
			'align'       => 'alignnone',
			'num_ads'     => 1,
			'num_columns' => 1,
		);

		$attributes = wp_parse_args( $attributes, $default_attributes );

		$current_time = time();

		$args = array(
			'post_type'      => 'wpadcenter-ads',
			'posts_per_page' => $attributes['num_ads'],
			'tax_query'      => array( //phpcs:ignore
				array(
					'taxonomy' => 'wpadcenter-adgroups',
					'field'    => 'id',
					'terms'    => $attributes['adgroup_ids'],
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

			'no_found_rows'  => true,
		);

		$ads = new WP_Query( $args );

		if ( $ads->have_posts() ) {

			$adgroup_html  = '';
			$adgroup_html .= '<div class=' . $attributes['align'] . '>';

			$col_count = 0;
			$ad_count  = 0;
			while ( $ads->have_posts() ) {

				$ads->the_post();

				if ( 0 === $col_count || intval( $attributes['num_columns'] ) === $col_count ) {
					$adgroup_html .= '<div class="wpadcenter-adgroup-row">';
				}

				$ad_id                = get_the_ID();
				$single_ad_attributes = array(
					'classes' => 'wpadcenter-ad-spacing',
				);
				$adgroup_html        .= self::display_single_ad( $ad_id, $single_ad_attributes );
				$ad_count++;
				$col_count++;
				if ( intval( $attributes['num_ads'] ) === $ad_count || intval( $attributes['num_columns'] ) === $col_count ) {
					$adgroup_html .= '</div>';

				}

				if ( intval( $attributes['num_columns'] ) === $col_count ) {
					$col_count = 0;
				}
			}
			$adgroup_html .= '</div>';

			return $adgroup_html;
		} else {
			return;

		}

	}
}

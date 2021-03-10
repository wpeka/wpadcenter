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
	 * @param string    $plugin_name       The name of the plugin.
	 * @param string    $version    The version of this plugin.
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'jquery' ), $this->version, false );

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

}

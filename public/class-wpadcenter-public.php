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
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	public static $released_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		self::$released_version = $version;
		if ( ! shortcode_exists( 'wpadcenter_ad' ) ) {
			add_shortcode( 'wpadcenter_ad', array( $this, 'wpadcenter_ad_shortcode' ) );
		}
		if ( ! shortcode_exists( 'wpadcenter_adgroup' ) ) {
			add_shortcode( 'wpadcenter_adgroup', array( $this, 'wpadcenter_adgroup_shortcode' ) );
		}
		if ( ! shortcode_exists( 'wpadcenter_random_ad' ) ) {
			add_shortcode( 'wpadcenter_random_ad', array( $this, 'wpadcenter_random_ad_shortcode' ) );
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
		if ( isset( $_SERVER['REQUEST_URI'] ) && '/ads.txt' === $_SERVER['REQUEST_URI'] ) {
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
				echo esc_html( $content );
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
				'id'              => 0,
				'align'           => 'none',
				'max_width'       => 'off',
				'max_width_value' => '100',
				'devices'         => '',
				'placement_id'    => '',
			),
			$atts
		);
		if ( 'on' === $atts['max_width'] ) {
			$atts['max_width'] = true;
		} else {
			$atts['max_width'] = false;
		}

		$ad_id = $atts['id'];

		$atts['devices'] = ! $atts['devices'] ? array( 'mobile', 'tablet', 'desktop' ) : explode( ',', $atts['devices'] );

		$attributes = array(
			'align'        => 'align' . $atts['align'],
			'max_width'    => $atts['max_width'],
			'max_width_px' => $atts['max_width_value'],
			'devices'      => $atts['devices'],
			'placement_id' => $atts['placement_id'],
		);
		return self::display_single_ad( $atts['id'], $attributes );

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

		$options = Wpadcenter::wpadcenter_get_settings();

		if ( $options['enable_click_fraud_protection'] && isset( $_COOKIE['wpadcenter_hide_ads'] ) ) {
			return;
		}
		$current_url = isset( $_SERVER['REQUEST_URI'] ) ? home_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';
		if ( strstr( $current_url, 'ads.txt' ) ) {
			return;
		}

		if ( 'publish' !== get_post_status( $ad_id ) ) {
			return;
		}
		$display_ad = true;

		if ( Wpadcenter::is_request( 'frontend' ) ) {
			// check for cookie consent policy to show ads in front end.
			$display_ad = self::wpadcenter_check_cookie_consent( $display_ad, $ad_id );
		}

		if ( ! $display_ad ) {
			return;
		}

		$display_ad = apply_filters( 'wpadcenter_display_single_ad', $ad_id );

		if ( ! $display_ad ) {
			return;
		}

		wp_enqueue_style( 'wpadcenter-frontend' );

		$lazy_load_enabled = apply_filters( 'wpadcenter_lazy_load_enabled', $ad_id );
		$is_frontend       = WPAdcenter::is_request( 'frontend' );
		apply_filters( 'wpadcenter_add_custom_ad_sizes_css', 'wpadcenter-frontend' );
		wp_enqueue_script( 'wpadcenter-frontend', plugin_dir_url( __FILE__ ) . 'js/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.js', array( 'jquery' ), self::$released_version, false );
		wp_localize_script(
			'wpadcenter-frontend',
			'ajax_url',
			array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'wpadcenter_set_clicks' ),
			)
		);
		$default_attributes = array(
			'align'               => 'alignnone',
			'classes'             => '',
			'max_width'           => false,
			'max_width_px'        => '100',
			'display_adgroup'     => false,
			'display_rotating_ad' => false,
			'devices'             => array( 'mobile', 'desktop', 'tablet' ),
			'placement_id'        => '',
		);

		$attributes = wp_parse_args( $attributes, $default_attributes );

		if ( ! self::wpadcenter_verify_device( $attributes['devices'] ) ) {
			return;
		}
		$current_time = time();
		$start_date   = get_post_meta( $ad_id, 'wpadcenter_start_date', true );
		$end_date     = get_post_meta( $ad_id, 'wpadcenter_end_date', true );
		if ( $current_time < $start_date || $current_time > $end_date ) {
			return;
		}
		$ad_size  = get_post_meta( $ad_id, 'wpadcenter_ad_size', true );
		$ad_type  = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
		$link_url = get_post_meta( $ad_id, 'wpadcenter_link_url', true );
		if ( get_option( 'wpadcenter_pro_active' ) && get_option( 'wc_am_client_wpadcenter_pro_activated' ) === 'Activated' ) {
			$cloaked_link = apply_filters( 'wp_adcenter_modify_single_ad_link_url', $ad_id, $link_url );
			if ( intval( $cloaked_link ) !== intval( $ad_id ) ) {
				$link_url = $cloaked_link;
			}
		}
		$open_in_new_tab               = get_post_meta( $ad_id, 'wpadcenter_open_in_new_tab', true );
		$global_open_in_new_tab        = $options['link_open_in_new_tab'];
		$nofollow                      = get_post_meta( $ad_id, 'wpadcenter_nofollow_on_link', true );
		$global_nofollow               = $options['link_nofollow'];
		$additional_rel_tags           = get_post_meta( $ad_id, 'wpadcenter_additional_rel_tags', true );
		$additional_rel_tags           = $additional_rel_tags ? implode( ' ', $additional_rel_tags ) : '';
		$global_additional_rel_tags    = str_replace( ',', ' ', $options['link_additional_rel_tags'] );
		$additional_css_classes        = get_post_meta( $ad_id, 'wpadcenter_additional_css_classes', true );
		$global_additional_css_classes = $options['link_additional_css_class'];

		$global_additional_rel_tags_preference  = get_post_meta( $ad_id, 'wpadcenter_global_additional_rel_tags_preference', true );
		$global_additional_css_class_preference = get_post_meta( $ad_id, 'wpadcenter_global_additional_css_class_preference', true );

		// For compatibility with previous version ( <= 2.1.0 ).
		if ( '1' === $open_in_new_tab ) {
			$open_in_new_tab = 'yes';
		} elseif ( '0' === $open_in_new_tab ) {
			$open_in_new_tab = 'no';
		}
		if ( '1' === $nofollow ) {
			$nofollow = 'yes';
		} elseif ( '0' === $nofollow ) {
			$nofollow = 'no';
		}
		$global_additional_rel_tags_preference  = '' !== $global_additional_rel_tags_preference ? $global_additional_rel_tags_preference : '1';
		$global_additional_css_class_preference = '' !== $global_additional_css_class_preference ? $global_additional_css_class_preference : '1';

		$text_ad_bg_color     = get_post_meta( $ad_id, 'wpadcenter_text_ad_background_color', true );
		$text_ad_border_color = get_post_meta( $ad_id, 'wpadcenter_text_ad_border_color', true );
		$text_ad_border_width = get_post_meta( $ad_id, 'wpadcenter_text_ad_border_width', true );
		$text_ad_align_center = get_post_meta( $ad_id, 'wpadcenter_text_ad_align_vertically', true );

		$link_target = '_self';
		if ( 'global' === $open_in_new_tab && $global_open_in_new_tab ) {
			$link_target = '_blank';
		} elseif ( 'yes' === $open_in_new_tab ) {
			$link_target = '_blank';
		}
		$width  = '';
		$height = '';
		if ( 'none' !== $ad_size ) {
			$ad_size                = explode( 'x', $ad_size );
			$width                  = $ad_size[0];
			$height                 = $ad_size[1];
			$attributes['classes'] .= 'ad-' . $width . 'x' . $height;
			$attributes['classes'] .= ' wpadcenter-' . $width . 'x' . $height;

		}
		$attributes['classes'] .= ' ad-placement ';

		if ( 'text_ad' === $ad_type && $text_ad_align_center ) {
			$attributes['classes'] .= ' wpadcenter-text-ad-align-center';
		} elseif ( 'text_ad' === $ad_type && ! $text_ad_align_center ) {
			$attributes['classes'] .= ' wpadcenter-text-ad-align-none';
		}

		$amp_page = false;
		if ( function_exists( 'is_amp_endpoint' ) ) {
				$amp_page = is_amp_endpoint();
		}

		$security = wp_create_nonce( 'wpadcenter_set_clicks' );

		$amp_preference = get_post_meta( $ad_id, 'wpadcenter_amp_preference', true );

		$amp_preference = ! empty( $amp_preference ) ? boolval( $amp_preference ) : false;

		$single_ad_html = '';

		if ( ! $attributes['display_adgroup'] ) {
			$attributes['classes'] .= ' wpadcenter-' . $attributes['align'] . ' ' . $attributes['align'];
			if ( ! $attributes['display_rotating_ad'] ) {
				$single_ad_html .= '<!-- Ad space powered by WP AdCenter v' . self::$released_version . ' - https://wpadcenter.com/ -->';
			}
		}

		$single_ad_html .= '<div class="wpadcenter-ad-container" ';

		if ( 'text_ad' === $ad_type ) {
			$single_ad_html .= 'style="overflow:visible" ';
		}

		$single_ad_html .= '>';

		$single_ad_html .= '<div ';
		$single_ad_html .= 'id="wpadcenter-ad-' . $ad_id . '" ';
		$inline_styles   = '';
		if ( $attributes['max_width'] ) {
			$inline_styles         .= 'max-width:' . $attributes['max_width_px'] . 'px;';
			$attributes['classes'] .= ' wpadcenter-maxwidth';
		}

		if ( $attributes['classes'] ) {
			$single_ad_html .= 'class="' . $attributes['classes'] . '"';
		}

		if ( 'text_ad' === $ad_type ) {
			$inline_styles .= ' background-color:' . $text_ad_bg_color . ';border:' . $text_ad_border_width . 'px solid ' . $text_ad_border_color . '; ';
		}
		if ( $inline_styles ) {
			$single_ad_html .= ' style="' . $inline_styles . '"';
		}

		$single_ad_html .= '>';
		$single_ad_html .= '<div class="wpadcenter-ad-inner" >';

		if ( 'text_ad' !== $ad_type ) {

			$single_ad_html .= '<a id="wpadcenter_ad" data-value=' . $ad_id . ' data-placement="' . $attributes['placement_id'] . '" href="' . $link_url . '" target="' . $link_target . '" ';

			// adding classes to link.
			$single_ad_html .= 'class="wpadcenter-ad-inner__item';

			if ( $global_additional_css_class_preference ) {
				$single_ad_html .= $global_additional_css_classes ? ' ' . $global_additional_css_classes : '';
			} elseif ( $additional_css_classes ) {
				$single_ad_html .= ' ' . $additional_css_classes;
			}

			$single_ad_html .= '" ';

			// adding rel tags to link.
			$rel_tags = '';
			if ( 'global' === $nofollow && $global_nofollow ) {
				$rel_tags .= 'nofollow';
			} elseif ( 'yes' === $nofollow ) {
				$rel_tags .= 'nofollow';
			}

			if ( $global_additional_rel_tags_preference ) {
				$rel_tags .= $global_additional_rel_tags ? ' ' . $global_additional_rel_tags : '';
			} elseif ( $additional_rel_tags ) {
				$rel_tags .= ' ' . $additional_rel_tags;
			}

			if ( $rel_tags ) {
				$single_ad_html .= 'rel="' . trim( $rel_tags ) . '"';
			}
			$single_ad_html .= '>';

		}

		switch ( $ad_type ) {
			case 'banner_image':
				if ( $is_frontend && 'yes' === $lazy_load_enabled ) {
					$banner_img      = get_the_post_thumbnail_url( $ad_id );
					$single_ad_html .= '<img class="wpadcenter-lazy-load-ad" width="' . $width . '" height="' . $height . '" data-src="' . esc_url( $banner_img ) . '"/>';
				} else {
					$banner_img      = get_the_post_thumbnail( $ad_id );
					$single_ad_html .= $banner_img;
				}
				break;
			case 'external_image_link':
				$external_img_link = get_post_meta( $ad_id, 'wpadcenter_external_image_link', true );
				if ( $is_frontend && 'yes' === $lazy_load_enabled ) {
					$single_ad_html .= '<img class="wpadcenter-lazy-load-ad" width="' . $width . '" height="' . $height . '" data-src="' . esc_url( $external_img_link ) . '"/>';
				} else {
					$single_ad_html .= '<img width="' . $width . '" height="' . $height . '" src="' . esc_url( $external_img_link ) . '"/>';
				}
				break;
			case 'text_ad':
				$text_ad_code = get_post_meta( $ad_id, 'wpadcenter_text_ad_code', true );

				$single_ad_html .= '<div class="wpadcenter-text-ad-code" >' . $text_ad_code . '</div>';
				break;
			case 'ad_code':
				$ad_code         = get_post_meta( $ad_id, 'wpadcenter_ad_code', true );
				$single_ad_html .= '<span class="wpadcenter-ad-code">' . $ad_code . '</span>';
				break;
			case 'import_from_adsense':
				if ( $amp_page && $amp_preference ) {
					$adsense_code = get_post_meta( $ad_id, 'wpadcenter_adsense_amp_code', true );
				} else {
					$adsense_code = get_post_meta( $ad_id, 'wpadcenter_ad_google_adsense', true );
				}

				$single_ad_html .= '<div style="min-height:200px;min-width: 200px" class="wpadcenter-ad-code">' . $adsense_code . '</div>';
				break;
			case 'amp_ad':
				$amp_ad_attributes  = get_post_meta( $ad_id, 'wpadcenter_amp_attributes', true );
				$amp_ad_values      = get_post_meta( $ad_id, 'wpadcenter_amp_values', true );
				$amp_ad_placeholder = get_post_meta( $ad_id, 'wpadcenter_amp_placeholder', true );
				$amp_ad_fallback    = get_post_meta( $ad_id, 'wpadcenter_amp_fallback', true );

				$amp_ad_code = '<span class="wpadcenter-ad-code">';

				if ( ! empty( $amp_ad_attributes ) ) {

					$amp_ad_code .= '<amp-ad ';
					$index        = 0;
					foreach ( $amp_ad_attributes as $attribute ) {
						$amp_ad_code .= $attribute . '="' . $amp_ad_values[ $index ] . '" ';
						$index++;
					}
					$amp_ad_code .= '>';
					if ( $amp_page ) {
						$amp_ad_code .= '  <div placeholder>' . $amp_ad_placeholder . '</div>';
						$amp_ad_code .= '  <div fallback>' . $amp_ad_fallback . '</div>';
					}

					$amp_ad_code .= '</amp-ad>';
				}
				$amp_ad_code .= '</span>';

				$single_ad_html .= $amp_ad_code;

				break;
			case 'html5':
				$html5_url       = get_post_meta( $ad_id, 'wpadcenter_html5_ad_url', true );
				$single_ad_html .= '<div>';
				if ( $is_frontend && 'yes' === $lazy_load_enabled ) {
					$single_ad_html .= '<iframe class="wpadcenter-lazy-load-ad" data-src="' . $html5_url . '" height="' . $height . '" width="' . $width . '" frameborder="0" scrolling="no"></iframe>';
				} else {
					$single_ad_html .= '<iframe src="' . $html5_url . '" height="' . $height . '" width="' . $width . '" frameborder="0" scrolling="no"></iframe>';
				}
				$single_ad_html .= '</div>';
				break;
			case 'video_ad':
				$video_ad_url   = get_post_meta( $ad_id, 'wpadcenter_video_ad_url', true );
				$video_autoplay = get_post_meta( $ad_id, 'wpadcenter_video_autoplay', true );
				$autoplay       = $video_autoplay ? 'autoplay' : '';
				$muted          = $video_autoplay ? 'muted = "muted"' : '';

				if ( '' !== $video_ad_url ) {
					$single_ad_html .= '<div>';
					if ( $is_frontend && 'yes' === $lazy_load_enabled ) {
						$single_ad_html .= '<video id="wpadcenter_video" class="wpadcenter-lazy-video" preload="none" height="' . $height . '" width="' . $width . '" controls ' . $autoplay . ' ' . $muted . ' disablepictureinpicture controlslist="nodownload nofullscreen noplaybackrate">
						<source  data-src="' . $video_ad_url . '" type="video/mp4" >
					</video>';
					} else {
						$single_ad_html .= '<video id="wpadcenter_video"  height="' . $height . '" width="' . $width . '" controls ' . $autoplay . ' ' . $muted . ' disablepictureinpicture controlslist="nodownload nofullscreen noplaybackrate">
						<source src="' . $video_ad_url . '" type="video/mp4" >
					</video>';

					}
					$single_ad_html .= '</div>';
				}
				break;
			default:
				return;
		}

		if ( 'text_ad' !== $ad_type ) {
			$single_ad_html .= '</a>';
		}

		$single_ad_html .= '</div>';
		$single_ad_html .= '</div>';
		$single_ad_html .= '</div>';
		if ( 'text_ad' !== $ad_type && 'import_from_adsense' !== $ad_type && 'amp_ad' !== $ad_type && 'ad_code' !== $ad_type && ! $is_frontend && 'yes' === $lazy_load_enabled ) {
			$single_ad_html .= '<p>The preview is not lazy loaded</p>';
		}

		if ( self::wpadcenter_check_exclude_roles() && Wpadcenter::is_request( 'frontend' ) ) {
			Wpadcenter::wpadcenter_set_impressions( $ad_id, $attributes ['placement_id'] );
		}

		$single_ad_html = apply_filters( 'before_returning_single_ad', $single_ad_html, $ad_id );
		return $single_ad_html;
	}

	/**
	 * Check consent.
	 *
	 * @param boolean $display_ad boolean true or false.
	 * @param int     $ad_id Id of the ad that is displayed.
	 *
	 * @since 1.0.0
	 */
	public static function wpadcenter_check_cookie_consent( $display_ad, $ad_id ) {
		$the_options = Wpadcenter::wpadcenter_get_settings();

		if ( ! $the_options['enable_privacy'] ) {
			return true;
		}

		if ( 'show-all-ads-without' === $the_options['consent_method'] ) {
			return true;
		}

		if ( 'cookie' !== $the_options['consent_method'] ) {
			return true;
		}

		$ad_type = get_post_meta( $ad_id, 'wpadcenter_ad_type', true );
		if ( 'import_from_adsense' === $ad_type ) {
			if ( $the_options['cookie_non_personalized'] ) {
				echo '<script>(adsbygoogle=window.adsbygoogle||[]).requestNonPersonalizedAds=1;</script>';
				return true;
			} else {
				echo '<script>(adsbygoogle=window.adsbygoogle||[]).requestNonPersonalizedAds=0;</script>';
			}
		}

		$cookie_values = array(
			'borlabs-cookie'           => 'please find the detailed instructions below',
			'complianz_consent_status' => 'allow',
			'cmplz_marketing'          => 'allow',
			'catAccCookies'            => '1',
			'cookie_notice_accepted'   => 'true',
			'viewed_cookie_policy'     => 'yes',
			'ginger-cookie'            => 'Y',
			'wpl_viewed_cookie'        => 'yes',
		);

		if ( array_key_exists( $the_options['cookie_name'], $cookie_values ) ) {
			if ( $cookie_values[ $the_options['cookie_name'] ] === $the_options['cookie_value'] ) {
				if ( array_key_exists( $the_options['cookie_name'], $_COOKIE ) ) {
					if ( $_COOKIE[ $the_options['cookie_name'] ] === $the_options['cookie_value'] ) {
						return true;
					}
				}
			}
		} elseif ( array_key_exists( 'borlabsCookie', $_COOKIE ) && 'borlabsCookie' === $the_options['cookie_name'] ) {
			if ( ',all' === $the_options['cookie_value'] || 'first-party' === $the_options['cookie_value'] ) {
				if ( ',all' === $_COOKIE['borlabsCookie'] || 'first-party' === $_COOKIE['borlabsCookie'] ) {
					return true;
				}
			}
		} elseif ( array_key_exists( 'euconsent', $_COOKIE ) && 'euconsent' === $the_options['cookie_name'] ) {
			if ( preg_match( '/BOzOg5COzOg5CAKAABENDJ-AAAAvhr/', sanitize_text_field( wp_unslash( $_COOKIE['euconsent'] ) ) ) && preg_match( '/BOzOg5COzOg5CAKAABENDJ-AAAAvhr/', $the_options['cookie_value'] ) ) {
				return true;
			}
		} elseif ( array_key_exists( 'moove_gdpr_popup', $_COOKIE ) && 'moove_gdpr_popup' === $the_options['cookie_name'] ) {
			if ( preg_match( '/thirdparty/', $the_options['cookie_value'] ) && preg_match( '/thirdparty/', sanitize_text_field( wp_unslash( $_COOKIE['moove_gdpr_popup'] ) ) ) ) {
				return true;
			}
		} elseif ( preg_match( '/wpgdprc-consent-/', $the_options['cookie_name'] ) ) {
			foreach ( array_keys( $_COOKIE ) as $k ) {
				if ( preg_match( '/wpgdprc-consent-/', $k ) ) {
					if ( isset( $_COOKIE[ $k ] ) && 'accept' === sanitize_text_field( wp_unslash( $_COOKIE[ $k ] ) ) ) {
						return true;
					}
					$match = '/' . $the_options['cookie_value'] . '/';
					if ( preg_match( $match, sanitize_text_field( wp_unslash( $_COOKIE[ $k ] ) ) ) ) {
						return true;
					}
				}
			}
		}
		if ( array_key_exists( $the_options['cookie_name'], $_COOKIE ) ) {
			if ( $_COOKIE[ $the_options['cookie_name'] ] === $the_options['cookie_value'] ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Set tracking for ad click.
	 *
	 * @since 1.0.0
	 */
	public static function wpadcenter_set_clicks() {
		global $wpdb;

		if ( ( isset( $_POST['action'] ) || isset( $_GET['action'] ) ) && self::wpadcenter_check_exclude_roles() ) {

			if ( isset( $_POST['security'] ) ) {
				$security = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			}
			if ( isset( $_GET['security'] ) ) {
				$security = isset( $_GET['security'] ) ? sanitize_text_field( wp_unslash( $_GET['security'] ) ) : '';
			}

			if ( wp_verify_nonce( $security, 'wpadcenter_set_clicks' ) ) {
				if ( ( isset( $_POST['ad_id'] ) && ! empty( $_POST['ad_id'] ) ) || ( isset( $_GET['ad_id'] ) && ! empty( $_GET['ad_id'] ) ) ) {
					if ( isset( $_GET['ad_id'] ) ) {
						$ad_id = sanitize_text_field( wp_unslash( $_GET['ad_id'] ) );
					}
					if ( isset( $_POST['ad_id'] ) ) {
						$ad_id = sanitize_text_field( wp_unslash( $_POST['ad_id'] ) );
					}
					if ( isset( $_POST['placement_id'] ) ) {
						$placement_id = sanitize_text_field( wp_unslash( $_POST['placement_id'] ) );
					}
					if ( isset( $_GET['placement_id'] ) ) {
						$placement_id = sanitize_text_field( wp_unslash( $_GET['placement_id'] ) );
					}
					$meta = get_post_meta( $ad_id, 'wpadcenter_ads_stats', true );

					$today          = gmdate( 'Y-m-d' );
					$placement_name = '';
					// Create new placement meta for ad.
					if ( ! empty( $placement_id ) ) {

						$placement_meta = get_option( 'wpadcenter-pro-placements', true );
						foreach ( $placement_meta as $placement ) {
							if ( $placement['id'] === $placement_id ) {
								$placement_name = $placement['name'];
							}
						}
						$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'placements_statistics WHERE placement_date = %s and placement_id = %s', array( $today, $placement_id ) ) ); // db call ok; no-cache ok.

						if ( count( $records ) ) {
							$record = $records[0];
							$clicks = $record->placement_clicks + 1;
							$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'placements_statistics SET placement_clicks = %d WHERE placement_date = %s and placement_id = %s', array( $clicks, $today, $placement_id ) ) ); // db call ok; no-cache ok.
						} else {
							$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'placements_statistics` (`placement_clicks`, `placement_date`, `placement_name`, `placement_id`) VALUES (%d,%s,%s,%s)', array( 1, $today, $placement_name, $placement_id ) ) ); // db call ok; no-cache ok.
						}
					}

					$meta['total_clicks']++;
					$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, $ad_id ) ) ); // db call ok; no-cache ok.
					if ( count( $records ) ) {
						$record = $records[0];
						$clicks = $record->ad_clicks + 1;
						$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'ads_statistics SET ad_clicks = %d WHERE ad_date = %s and ad_id = %d', array( $clicks, $today, $ad_id ) ) ); // db call ok; no-cache ok.
						do_action( 'wp_adcenter_after_set_impressions', $clicks );
					} else {

						$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'ads_statistics` (`ad_clicks`, `ad_date`, `ad_id`) VALUES (%d,%s,%d)', array( 1, $today, $ad_id ) ) ); // db call ok; no-cache ok.
					}
					update_post_meta( $ad_id, 'wpadcenter_ads_stats', $meta );

					do_action( 'wpadcenter_on_ad_click', $ad_id );
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
	 * Shortcode for adgroup ads.
	 *
	 * @param int $atts attributes.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_adgroup_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'adgroup_ids'     => '',
				'align'           => 'none',
				'num_ads'         => 1,
				'num_columns'     => 1,
				'max_width'       => 'off',
				'max_width_value' => '100',
				'devices'         => '',
				'placement_id'    => '',
			),
			$atts
		);
		if ( 'on' === $atts['max_width'] ) {
			$atts['max_width'] = true;
		} else {
			$atts['max_width'] = false;
		}
		$atts['adgroup_ids'] = explode( ',', $atts['adgroup_ids'] );
		$atts['align']       = 'align' . $atts['align'];

		$atts['devices'] = ! $atts['devices'] ? array( 'mobile', 'tablet', 'desktop' ) : explode( ',', $atts['devices'] );

		$adgroup_atts = array(
			'adgroup_ids'  => $atts['adgroup_ids'],
			'align'        => $atts['align'],
			'num_ads'      => $atts['num_ads'],
			'num_columns'  => $atts['num_columns'],
			'max_width'    => $atts['max_width'],
			'max_width_px' => $atts['max_width_value'],
			'devices'      => $atts['devices'],
			'placement_id' => $atts['placement_id'],

		);
		return self::display_adgroup_ads( $adgroup_atts );
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

		$options = Wpadcenter::wpadcenter_get_settings();

		if ( $options['enable_click_fraud_protection'] && isset( $_COOKIE['wpadcenter_hide_ads'] ) ) {
			return;
		}

		wp_enqueue_style( 'wpadcenter-frontend' );

		$default_attributes = array(
			'adgroup_ids'  => array(),
			'align'        => 'alignnone',
			'num_ads'      => 1,
			'num_columns'  => 1,
			'max_width'    => false,
			'max_width_px' => '100',
			'devices'      => array( 'mobile', 'desktop', 'tablet' ),
			'placement_id' => '',
		);

		$attributes = wp_parse_args( $attributes, $default_attributes );
		if ( ! self::wpadcenter_verify_device( $attributes['devices'] ) ) {
			return;
		}

		$current_time = time();

		$args = array(
			'post_type'      => 'wpadcenter-ads',
			'posts_per_page' => $attributes['num_ads'],
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'wpadcenter-adgroups',
					'field'    => 'id',
					'terms'    => $attributes['adgroup_ids'],
				),
			),
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
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

			$adgroup_html = '<!-- Ad space powered by WP AdCenter v' . self::$released_version . ' - https://wpadcenter.com/ -->';

			$adgroup_html .= '<div class="wpadcenter-adgroup" >';

			$col_count = 0;
			$ad_count  = 0;
			while ( $ads->have_posts() ) {

				$ads->the_post();

				if ( 0 === $col_count || intval( $attributes['num_columns'] ) === $col_count ) {
					$adgroup_html .= '<div class="wpadcenter-adgroup__row wpadcenter-' . $attributes['align'] . '">';
				}

				$ad_id                = get_the_ID();
				$single_ad_attributes = array(
					'display_adgroup' => true,
					'max_width'       => $attributes['max_width'],
					'max_width_px'    => $attributes['max_width_px'],
					'placement_id'    => $attributes['placement_id'],
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
			wp_reset_postdata();

			$adgroup_html .= '</div>';

			return $adgroup_html;
		} else {
			return;

		}

	}

	/**
	 * Register scripts for gutenberg block.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_gutenberg_scripts() {
		wp_enqueue_style(
			$this->plugin_name . '-frontend',
			plugin_dir_url( __FILE__ ) . 'css/wpadcenter-public' . WPADCENTER_SCRIPT_SUFFIX . '.css',
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Shortcode for random ad.
	 *
	 * @param int $atts attributes.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_random_ad_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'adgroup_ids'     => '',
				'align'           => 'none',
				'max_width'       => 'off',
				'max_width_value' => '100',
				'devices'         => '',
			),
			$atts
		);
		if ( 'on' === $atts['max_width'] ) {
			$atts['max_width'] = true;
		} else {
			$atts['max_width'] = false;
		}
		$atts['adgroup_ids'] = explode( ',', $atts['adgroup_ids'] );
		$atts['align']       = 'align' . $atts['align'];

		$atts['devices'] = ! $atts['devices'] ? array( 'mobile', 'tablet', 'desktop' ) : explode( ',', $atts['devices'] );

		$random_ad_atts = array(
			'adgroup_ids'  => $atts['adgroup_ids'],
			'align'        => $atts['align'],
			'max_width'    => $atts['max_width'],
			'max_width_px' => $atts['max_width_value'],
			'devices'      => $atts['devices'],
		);

		return self::display_random_ad( $random_ad_atts );
	}

	/**
	 * Get html for displaying random ads.
	 *
	 * @param array $attributes contains attributes for display function.
	 *
	 * @since 1.0.0
	 *
	 * @return string $random_ads_html html for the ads to be displayed.
	 */
	public static function display_random_ad( $attributes = array() ) {

		$options = Wpadcenter::wpadcenter_get_settings();

		if ( $options['enable_click_fraud_protection'] && isset( $_COOKIE['wpadcenter_hide_ads'] ) ) {
			return;
		}

		wp_enqueue_style( 'wpadcenter-frontend' );

		$default_attributes = array(
			'adgroup_ids'  => array(),
			'align'        => 'alignnone',
			'max_width'    => false,
			'max_width_px' => '100',
			'devices'      => array( 'mobile', 'desktop', 'tablet' ),

		);

		$attributes = wp_parse_args( $attributes, $default_attributes );

		if ( ! self::wpadcenter_verify_device( $attributes['devices'] ) ) {
			return;
		}
		// if activated wpadcenter pro, return weighted random ad.
		if ( get_option( 'wpadcenter_pro_active' ) && get_option( 'wc_am_client_wpadcenter_pro_activated' ) === 'Activated' ) {
			$random_ad_html = apply_filters( 'wp_adcenter_random_weighted_ad', $attributes );
			return $random_ad_html;
		}

		$current_time = time();

		$args = array(
			'post_type'      => 'wpadcenter-ads',
			'posts_per_page' => 1,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'wpadcenter-adgroups',
					'field'    => 'id',
					'terms'    => $attributes['adgroup_ids'],
				),
			),
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
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
			'orderby'        => 'rand',
		);

		$ads = new WP_Query( $args );

		if ( $ads->have_posts() ) {

			while ( $ads->have_posts() ) {

				$ads->the_post();

				$ad_id                = get_the_ID();
				$single_ad_attributes = array(
					'align'        => $attributes['align'],
					'max_width'    => $attributes['max_width'],
					'max_width_px' => $attributes['max_width_px'],
				);

				$random_ad_html = self::display_single_ad( $ad_id, $single_ad_attributes );

			}
			wp_reset_postdata();
			return $random_ad_html;
		} else {
			return;

		}

	}

	/**
	 * Verifies if the ad should be displayed on the device.
	 *
	 * @param array $devices contains device names.
	 *
	 * @since 1.0.0
	 *
	 * @return bool return true or false depending upon the device.
	 */
	public static function wpadcenter_verify_device( $devices ) {

		if ( ! class_exists( 'Mobile_Detect' ) ) {
			/**
			 * The class responsible for detecting the device on which website is loaded.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
		}

		$detect = new Mobile_Detect();

		if ( ! $detect->isMobile() && ! $detect->isTablet() && in_array( 'desktop', $devices, true ) ||
					$detect->isTablet() && in_array( 'tablet', $devices, true ) ||
					$detect->isMobile() && ! $detect->isTablet() && in_array( 'mobile', $devices, true ) ) {
			if ( ! $detect->isMobile() && ! $detect->isTablet() ) {
				if ( in_array( 'desktop', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isTablet() ) {
				if ( in_array( 'tablet', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isMobile() && ! $detect->isTablet() ) {
				if ( in_array( 'mobile', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}

	}

}

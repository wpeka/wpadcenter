<?php

/**
 * The helper class for admin functionality of the plugin.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/public
 */

/**
 * The helper class for admin functionality of the plugin.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/public
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Admin_Helper {

	/**
	 * Returns default metafields.
	 *
	 * @since 1.0.0
	 *
	 * @return array $metafields default metafields.
	 */
	public static function get_default_metafields() {
		$metafields = array(
			'ad-type'             => array( 'wpadcenter_ad_type', 'string' ),
			'ad-size'             => array( 'wpadcenter_ad_size', 'string' ),
			'open-in-new-tab'     => array( 'wpadcenter_open_in_new_tab', 'bool' ),
			'nofollow-on-link'    => array( 'wpadcenter_nofollow_on_link', 'bool' ),
			'ad-code'             => array( 'wpadcenter_ad_code', 'raw' ),
			'external-image-link' => array( 'wpadcenter_external_image_link', 'url' ),
			'ad-google-adsense'   => array( 'wpadcenter_ad_google_adsense', 'raw' ),
			'start_date'          => array( 'wpadcenter_start_date', 'date' ),
			'end_date'            => array( 'wpadcenter_end_date', 'date' ),
		);

		return apply_filters( 'wpadcenter_get_default_metafields', $metafields );
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
				),
			),

		);

		return apply_filters( 'wpadcenter_get_ad_meta_relation', $ad_meta_relation );

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
			'1x1'     => '1x1: Responsive',
			'250x250' => '250x250: Square Pop-Up',
			'300x100' => '3:1 Rectangle',
			'468x60'  => '468x60: Full Banner',
			'300x250' => '300x250: Medium Rectangle',
		);
		return apply_filters( 'wpadcenter_get_default_ad_sizes', $sizes );
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
			'banner_image'        => 'Banner Image',
			'external_image_link' => 'External Image Link',
			'ad_code'             => 'Ad Code',
			'import_from_adsense' => 'Import from Adsense',

		);

		return apply_filters( 'wpadcenter_get_default_ad_types', $ad_types );
	}



}

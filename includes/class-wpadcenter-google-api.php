<?php
/**
 * AdSense Api integration.
 *
 * @link  https://club.wpeka.com
 * @since 1.0.1
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

namespace Wpeka\Adcenter;

/**
 * Deals with google adsense management api provided.
 * Documentation reference https://developers.google.com/adsense/management/v1.4/reference
 *
 * Class Wpadcenter_Google_Api
 *
 * @package Wpeka\Adcenter
 */
class Wpadcenter_Google_Api {


	const GID = '321946375921-f6ug2l78amebq5ttjr7jpgg6ttr9obaq.apps.googleusercontent.com';

	const GS = 'dQcDOIbdE-2K4uaOn3YSiWah';

	/**
	 * Generate Google authentication url
	 *
	 * @return string
	 */
	public static function get_auth_url() {
		$url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' .
			rawurlencode( 'https://www.googleapis.com/auth/adsense.readonly' ) .
		'&client_id=' . self::GID .
		'&redirect_uri=' . rawurlencode( 'urn:ietf:wg:oauth:2.0:oob' ) .
		'&access_type=offline&include_granted_scopes=true&prompt=select_account&response_type=code';

		return $url;
	}

	/**
	 * List all accounts available to this AdSense account.
	 *
	 * @param string $access_token access token from google.
	 *
	 * @return array
	 */
	public function get_account_list( $access_token ) {
		$url      = 'https://adsense.googleapis.com/v2/accounts';
		$headers  = array( 'Authorization' => 'Bearer ' . $access_token );
		$response = wp_remote_get( $url, array( 'headers' => $headers ) );

		if ( is_wp_error( $response ) ) {

			return array(
				'status'    => false,
				'error_msg' => $response->get_error_message(),
			);

		}

		$accounts = json_decode( $response['body'], true );
		if ( ! isset( $accounts['accounts'] ) || isset( $accounts['error'] ) ) {
			$msg = __( 'An error occurred while requesting account details.', 'wpadcenter' );
			if ( isset( $accounts['error']['message'] ) ) {
				$msg = $accounts['error']['message'];
			}
			return array(
				'status'    => false,
				'error_msg' => $msg,
				'raw'       => $accounts['error'],
			);
		}

		return $accounts;
	}


	/**
	 * Generate tokens
	 *
	 * @param string $code Authentication code.
	 *
	 * @return array|\WP_Error
	 */
	public function generate_tokens( $code ) {

		$cid = self::GID;
		$cs  = self::GS;

		$code_url     = 'https://www.googleapis.com/oauth2/v4/token';
		$redirect_uri = 'urn:ietf:wg:oauth:2.0:oob';
		$grant_type   = 'authorization_code';

		$args = array(
			'timeout' => 10,
			'body'    => array(
				'code'          => $code,
				'client_id'     => $cid,
				'client_secret' => $cs,
				'redirect_uri'  => $redirect_uri,
				'grant_type'    => $grant_type,
			),
		);

		$response = wp_remote_post( $code_url, $args );

		return $response;
	}


	/**
	 * Fetch adunits for a account and access token
	 *
	 * @param string $account account.
	 * @param string $access_token token.
	 *
	 * @return array|\WP_Error
	 */
	public function get_ad_units( $account, $access_token ) {

		$url = 'https://adsense.googleapis.com/v2/accounts/' . $account . '/adclients/ca-' . $account . '/adunits';

		$headers = array(
			'Authorization' => 'Bearer ' . $access_token,
		);

		return wp_remote_get( $url, array( 'headers' => $headers ) );

	}

	/**
	 * Return the ad code for a given client and unit
	 *
	 * @param string $account_id   account id.
	 * @param string $ad_unit      ad unit.
	 * @param string $access_token token.
	 *
	 * @return String the ad code or info on the error.
	 */
	public function get_ad_code( $account_id, $ad_unit, $access_token ) {

		$url = 'https://adsense.googleapis.com/v2/' . $ad_unit . '/adcode/';

		$headers  = array(
			'Authorization' => 'Bearer ' . $access_token,
		);
		$response = wp_remote_get( $url, array( 'headers' => $headers ) );

		return $response;

	}



	/**
	 * Renew the current access token.
	 *
	 * @param string $refresh_token Token.
	 *
	 * @return array|WP_Error
	 */
	public function renew_access_token( $refresh_token ) {
		$cid = self::GID;
		$cs  = self::GS;

		$url  = 'https://www.googleapis.com/oauth2/v4/token';
		$args = array(
			'body' => array(
				'refresh_token' => $refresh_token,
				'client_id'     => $cid,
				'client_secret' => $cs,
				'grant_type'    => 'refresh_token',
			),
		);

		return wp_remote_post( $url, $args );

	}

}

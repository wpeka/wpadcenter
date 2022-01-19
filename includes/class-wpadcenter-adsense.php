<?php
/**
 * Entry point for all adsense code
 *
 * @link  https://club.wpeka.com/
 * @since 1.1.4
 *
 * @package Wpadcenter
 */

namespace Wpeka\Adcenter;

/**
 * Main class for Wpadcenter_Adsense
 *
 * Class Wpadcenter_Adsense
 *
 * @package Wpeka\Adcenter
 */
class Wpadcenter_Adsense {

	const OPTNAME = 'wpeka_adsense';

	const PUBID = 'wpeka_adsense_pubid';

	const CALL_PER_24H = 20;

	/**
	 * Google api instance
	 *
	 * @var Wpadcenter_Google_Api
	 */
	protected $google_api;

	/**
	 * Singleton
	 *
	 * @var null| Wpadcenter_Adsense
	 */
	private static $instance = null;

	/**
	 * Default options to be saved
	 *
	 * @var array
	 */
	private static $default_options = array(
		'accounts'          => array(),
		'ad_codes'          => array(),
		'unsupported_units' => array(),
		'quota'             => array(
			'count' => self::CALL_PER_24H,
			'ts'    => 0,
		),
		'connect_error'     => array(),
	);

	/**
	 * Get singleton
	 *
	 * @return null|Wpadcenter_Adsense
	 */
	public static function get_instance() {
		if ( self::$instance ) {
			return self::$instance;
		}
		self::$instance = new Wpadcenter_Adsense();
		return self::$instance;
	}

	/**
	 * Wpadcenter_Adsense constructor.
	 */
	private function __construct() {

		add_action( 'wp_ajax_adsense_confirm_code', array( $this, 'confirm_code_and_generate_tokens' ) );
		add_action( 'wp_ajax_adsense_remove_authentication', array( $this, 'wpadcenter_remove_authentication' ) );

		$this->google_api = new \Wpeka\Adcenter\Wpadcenter_Google_Api();

	}

	/**
	 * Get adsense accounts saved in options table
	 *
	 * @return array
	 */
	public function get_saved_accounts() {
		$options = get_option( self::OPTNAME, array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		return $options + self::$default_options;
	}

	/**
	 * Saves token information that came through ajax call
	 *  and calls account list api
	 */
	public function confirm_code_and_generate_tokens() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		if ( ! isset( $_POST['code'] ) || ! isset( $_POST['nonce'] ) ) {
			wp_send_json(
				array(
					'status' => false,
					'body'   => __( 'Something went wrong.', 'wpadcenter' ),
				)
			);
			wp_die();
		}

		if ( false === check_admin_referer( 'wpeka-google-adsense', 'nonce' ) ) {
			wp_send_json(
				array(
					'status' => false,
					'body'   => __( 'Something went wrong.', 'wpadcenter' ),
				)
			);
			wp_die();
		}

		$code     = urldecode( sanitize_text_field( wp_unslash( $_POST['code'] ) ) );
		$response = $this->google_api->generate_tokens( $code );

		if ( is_wp_error( $response ) ) {
			wp_send_json(
				array(
					'status' => false,
					'body'   => $response->get_error_message(),
				)
			);
		}

		$token = json_decode( $response['body'], true );

		if ( null !== $token && isset( $token['refresh_token'] ) ) {
			$expires          = time() + absint( $token['expires_in'] );
			$token['expires'] = $expires;
			$this->get_account_details( $token );

		} else {

			wp_send_json(
				array(
					'status' => false,
					'body'   => $token,
				)
			);
		}

	}

	/**
	 * Get AdSense account details from a new access token.
	 *
	 * @param array $token authentication Token.
	 */
	public function get_account_details( $token ) {

		$data = $this->google_api->get_account_list( $token['access_token'] );

		if ( isset( $data['status'] ) && 'FAILED_PRECONDITION' === $data['status'] ) {

			wp_send_json(
				array(
					'status' => false,
					'body'   => $data['message'],
				)
			);

		}

		$options                  = self::get_option();
		$options['connect_error'] = array();
		update_option( self::OPTNAME, $options );

		if ( count( $data['accounts'] ) ) {

			$adsense_id = $data['accounts'][0]['name'];
			preg_match( '/pub-[0-9]+/', $adsense_id, $adsense_id );
			$adsense_id                = $adsense_id[0];
			$data['accounts'][0]['id'] = $adsense_id;
			self::save_token_from_data(
				$token,
				$data['accounts'][0]
			);

			update_option( self::PUBID, $adsense_id );

			wp_send_json(
				array(
					'status' => true,
					'body'   => $adsense_id,
				)
			);
		} else {
			wp_send_json(
				array(
					'status' => false,
					'body'   => __( 'No accounts found', 'wpadcenter' ),
				)
			);
		}
		wp_die();

	}

	/**
	 *  Get/Update ad unit list for a given client.
	 *
	 * @return array
	 */
	public function get_ad_units() {
		$options = self::get_option();

		$account = '';
		foreach ( $options['accounts'] as $key => $row ) {
			$account = $key;
		}
		$transient_key = '_wpeka_adunits_' . $account;
		$value         = get_transient( $transient_key );
		if ( false !== $value ) {
			return array(
				'error'   => false,
				'adunits' => $value,
			);
		}

		$access_token = self::get_access_token( $account );

		if ( isset( $access_token['msg'] ) ) {
			return array(
				'error'   => true,
				'message' => $access_token['msg'],
			);
		}
		$response = $this->google_api->get_ad_units( $account, $access_token );
		if ( is_wp_error( $response ) ) {

			return array(
				'error' => true,
				'msg'   => sprintf(
					/* translators: %s: account */
					esc_html__( 'Error while retrieving adUnits list for "%s".', 'wpadcenter' ),
					$account
				),
				'raw'   => $response->get_error_message(),
			);
		}

		$ad_units = json_decode( $response['body'], true );

		if ( null === $ad_units || ! isset( $ad_units['adUnits'] ) ) {

			$error_message = sprintf(
				/* translators: %s: account */
				esc_html__( 'Invalid response while retrieving adUnits list for "%s".', 'wpadcenter' ),
				$account
			);
			// check the response for errors and display them for better problem solving.
			if ( $ad_units && isset( $ad_units['error'] ) && isset( $ad_units['error']['errors'] ) && count(
				$ad_units['error']['errors']
			) ) {
				foreach ( $ad_units['error']['errors'] as $err ) {
					$hint = $err['reason'];
					if ( $hint ) {
						$error_message .= "<p class=\"description\">$hint</p>";
					}
					$error_message .= '<p class="description">' . __(
						'Reason:',
						'wpadcenter'
					) . ' "' . $err['reason'] . '"<br>';
					$error_message .= __( 'Message:', 'wpadcenter' ) . ' "' . $err['message'] . '"</p>';
				}
			}

			return array(
				'error' => true,
				'msg'   => $error_message,
				'raw'   => $response['body'],
			);
		}

		if ( ! isset( $ad_units['adUnits'] ) ) {
			return array(
				'error' => false,
				'msg'   => sprintf(
					// translators: %1$s is the AdSense publisher ID; %2$s a starting a tag to the AdSense ad unit list and %3$s the closing link.
					esc_html__(
						'The account "%1$s" does not seem to have any ad units. Please create some %2$shere%3$s.',
						'wpadcenter'
					),
					$account,
					'<a href="https://www.google.com/adsense/new/u/0/' . $account . '/main/myads-viewall-adunits?product=SELF_SERVICE_CONTENT_ADS" target="_blank">',
					'</a>'
				),
				'raw'   => $response['body'],
			);
		}

		$new_ad_units = array();
		foreach ( $ad_units['adUnits'] as $item ) {
			if ( 'INACTIVE' === $item['state'] || 'ARCHIVED' === $item['state'] ) {
				continue;
			}
			$new_ad_units[ $item['name'] ] = $item;
		}
		$options['accounts'][ $account ]['ad_units'] = $new_ad_units;
		update_option( self::OPTNAME, $options );
		set_transient( $transient_key, $new_ad_units, 86400 );
		return array(
			'error'   => false,
			'adunits' => $new_ad_units,
		);

	}

	/**
	 * Return the ad code for a given client and unit.
	 *
	 * @param string $ad_unit Ad unit.
	 *
	 * @return array the ad code or info on the error.
	 */
	public function get_ad_code( $ad_unit ) {
		$options = self::get_option();
		$account = '';
		foreach ( $options['accounts'] as $key => $row ) {
			$account = $key;
		}

		$access_token = $this->get_access_token( $account );

		if ( isset( $access_token['msg'] ) ) {
			return array(
				'error'   => true,
				'message' => $access_token['msg'],
			);
		}

		$response = $this->google_api->get_ad_code( $account, $ad_unit, $access_token );

		if ( is_wp_error( $response ) ) {
			return array(
				'error'   => true,
				'message' => sprintf( /* translators: %s: account */ esc_html__( 'Error while retrieving ad code for "%s".', 'wpadcenter' ), $ad_unit ),
				'raw'     => $response->get_error_message(),
			);
		}

		$ad_code = json_decode( $response['body'], true );

		if ( null === $ad_code || ! isset( $ad_code['adCode'] ) ) {

			if ( $ad_code['error'] &&
				$ad_code['error']['errors'] &&
				isset( $ad_code['error']['errors'][0] ) &&
				isset( $ad_code['error']['errors'][0]['reason'] ) &&
				'doesNotSupportAdUnitType' === $ad_code['error']['errors'][0]['reason']
			) {
				if ( array_key_exists( $ad_unit, $options['ad_codes'] ) ) {
					if ( array_key_exists( $ad_unit, $options['unsupported_units'] ) ) {
						unset( $options['unsupported_units'][ $ad_unit ] );
					}
				} else {
					$options['unsupported_units'][ $ad_unit ] = 1;
				}
				update_option( self::OPTNAME, $options );
				return array(
					'error'   => true,
					'message' => 'doesNotSupportAdUnitType',
				);
			} else {
				return array(
					'error'   => true,
					'message' => sprintf(
						/* translators: %s : adunit */
						esc_html__( 'Invalid response while retrieving ad code for "%s".', 'wpadcenter' ),
						$ad_unit
					),
					'raw'     => $response['body'],
				);
			}
		}

		$options['ad_codes'][ $ad_unit ] = $ad_code['adCode'];
		if ( isset( $options['unsupported_units'][ $ad_unit ] ) ) {
			unset( $options['unsupported_units'][ $ad_unit ] );
		}
		update_option( self::OPTNAME, $options );
		return array(
			'error'   => false,
			'message' => $ad_code['adCode'],
		);

	}


	/**
	 * Get access token
	 *
	 * @param string $account Account id.
	 *
	 * @return array|mixed
	 */
	public function get_access_token( $account ) {
		$options = get_option( self::OPTNAME, array() );
		if ( isset( $options['accounts'][ $account ] ) ) {

			if ( time() > $options['accounts'][ $account ]['expires'] ) {
				$new_tokens = $this->renew_access_token( $account );
				if ( $new_tokens['status'] ) {
					return $new_tokens['access_token'];
				} else {
					return $new_tokens;
				}
			} else {
				return $options['accounts'][ $account ]['access_token'];
			}
		} else {
			// Account does not exists.
			if ( ! empty( $options['accounts'] ) ) {
				// There is another account connected.
				return array(
					'status' => false,
					'msg'    => esc_html__(
						'It seems that some changes have been made in the wpadcenter settings. Please refresh this page.',
						'wpadcenter'
					),
					'reload' => true,
				);
			} else {
				// No account at all.
				return array(
					'status' => false,
					'msg'    => wp_kses(
						sprintf(
							/* translators: %s: account */
							__(
								'Adcenter does not have access to your account (<code>%s</code>) anymore.',
								'wpadcenter'
							),
							$account
						),
						array( 'code' => true )
					),
					'reload' => true,
				);
			}
		}
	}

	/**
	 *  Renew the current access token.
	 *
	 * @param string $account Account.
	 *
	 * @return array
	 */
	public function renew_access_token( $account ) {

		$options       = get_option( self::OPTNAME, array() );
		$refresh_token = $options['accounts'][ $account ]['refresh_token'];

		$response = $this->google_api->renew_access_token( $refresh_token );

		if ( is_wp_error( $response ) ) {

			return array(
				'status' => false,
				'msg'    => sprintf( /* translators: %s: account */ esc_html__( 'error while renewing access token for "%s"', 'wpadcenter' ), $account ),
				'raw'    => $response->get_error_message(),
			);
		} else {
			$tokens = json_decode( $response['body'], true );
			// checking for the $tokens is not enough. it can be empty.
			// monitored this, when the access token is revoked from the outside
			// this can happen, when the user connects from another domain.
			if ( null !== $tokens && isset( $tokens['expires_in'] ) ) {
				$expires = time() + absint( $tokens['expires_in'] );

				$options['accounts'][ $account ]['access_token'] = $tokens['access_token'];
				$options['accounts'][ $account ]['expires']      = $expires;

				update_option( self::OPTNAME, $options );
				return array(
					'status'       => true,
					'access_token' => $tokens['access_token'],
				);
			} else {

				return array(
					'status' => false,
					'msg'    => sprintf(
						/* translators: %s: account */                        esc_html__( 'invalid response received while renewing access token for "%s"', 'wpadcenter' ),
						$account
					),
					'raw'    => $response['body'],
				);
			}
		}
	}

	/**
	 * Get the class's option
	 *
	 * @return array
	 */
	public static function get_option() {
		$options = get_option( self::OPTNAME, array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		return $options + self::$default_options;
	}

	/**
	 * Save token obtained from confirmation code.
	 *
	 * @param array $tokens Tokens.
	 * @param array $details Details of the account.
	 */
	public static function save_token_from_data( $tokens, $details ) {

		$options    = self::get_option();
		$adsense_id = $details['id'];

		if ( ! isset( $options['accounts'][ $adsense_id ] ) ) {
			$options['accounts'][ $adsense_id ] = array();
		}
		$options['accounts'][ $adsense_id ] = array(
			'access_token'  => $tokens['access_token'],
			'refresh_token' => $tokens['refresh_token'],
			'expires'       => $tokens['expires'],
			'token_type'    => $tokens['token_type'],
		);

		$options['accounts'][ $adsense_id ]['details'] = $details;
		update_option( self::OPTNAME, $options );
	}

	/**
	 * Removes current authentication
	 */
	public function wpadcenter_remove_authentication() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wpadcenter' ) );
		}
		// check for nonce.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'wpeka-google-adsense', 'nonce' );
		}

		$wpeka_adsense_pubid = get_option( 'wpeka_adsense_pubid' );

		delete_option( 'wpeka_adsense_pubid' );
		delete_option( 'wpeka_adsense' );
		delete_transient( '_wpeka_adunits_' . $wpeka_adsense_pubid );

		wp_send_json_success();
		wp_die();
	}
}

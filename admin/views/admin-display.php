<?php
/**
 * Admin Settings HTML with Core ui vue library
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/views
 */

$the_options = get_option( WPADCENTER_SETTINGS_FIELD );
$data_obj    = \Wpeka\Adcenter\Wpadcenter_Adsense::get_instance();
$data        = $data_obj->get_saved_accounts();
$nonce       = wp_create_nonce( 'wpeka-google-adsense' );
$auth_url    = \Wpeka\Adcenter\Wpadcenter_Google_Api::get_auth_url();

?>

<div id="app" style="width: 750px;">
	<c-tabs>
		<c-tab title="General" active>
		<?php do_action( 'wpadcenter_before_general_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Auto Refresh Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="ad-toggle">		
						<c-switch ref="auto_refresh" v-model="auto_refresh" id="inline-form-auto_refresh" variant="3d" size="sm" color="dark" <?php checked( $the_options['auto_refresh'] ); ?> v-on:update:checked="auto_refresh = !auto_refresh"></c-switch>
						<label for="inline-form-auto_refresh"><?php esc_html_e( 'Auto Refresh (Global)', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Auto refresh all ads on pages.', 'wpadcenter' ); ?>'" color="primary" name="cil-settings"></c-icon>
						<input type="hidden" name="auto_refresh_field" v-model="auto_refresh"><br>
					</div>

					<div class="auto_refresh_enabled" v-show="auto_refresh">
						<div class="transition_effect">
							<label for="inline-form-transition_effect_field">Transition Effect</label><br>
							<select name="transition_effect_field" id="transition_effect_field" ref="transition_effect" style="width: 100%;" class="form-control">
								<?php $this->print_combobox_options( $this->get_transition_effect_options(), $the_options['transition_effect'] ); ?>
							</select>
						</div>
						<c-input type="number" label="Transition Delay" value="<?php echo esc_html( stripslashes( $the_options['transition_delay'] ) ); ?>" name="transition_delay_field"></c-input>
						<c-input type="number" label="Transition Speed" value="<?php echo esc_html( stripslashes( $the_options['transition_speed'] ) ); ?>" name="transition_speed_field"></c-input>
					</div>
				</c-card-body>
			</c-card>
			<c-card>
				<c-card-header><?php esc_html_e( 'Hide Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>			
					<div class="ad-toggle">
						<c-switch ref="hide_ads_logged" v-model="hide_ads_logged" id="inline-form-hide_ads_logged" variant="3d" size="sm" color="dark" <?php checked( $the_options['hide_ads_logged'] ); ?> v-on:update:checked="hide_ads_logged = !hide_ads_logged"></c-switch>
						<label for="inline-form-hide_ads_logged"><?php esc_html_e( 'Hide ads for logged users (Global)', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Hides all the ads for logged in users if enabled.', 'wpadcenter' ); ?>'" color="primary" name="cil-settings"></c-icon>
						<input type="hidden" name="hide_ads_logged_field" v-model="hide_ads_logged">
					</div>
				</c-card-body>
			</c-card>

			<c-card>
				<c-card-header><?php esc_html_e( 'Ad Block Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="ad-toggle">
						<c-switch ref="adblock_detector" v-model="adblock_detector" id="inline-form-adblock_detector" variant="3d" size="sm" color="dark" <?php checked( $the_options['adblock_detector'] ); ?> v-on:update:checked="adblock_detector = !adblock_detector"></c-switch>
						<label for="inline-form-adblock_detector"><?php esc_html_e( 'Ad Block Detector', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Enable adblock on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cil-settings"></c-icon>
						<input type="hidden" name="adblock_detector_field" v-model="adblock_detector">
					</div>
					<label for="inline-form-adblock_detector_message" v-show="adblock_detector" class="form-label"><?php esc_html_e( 'Ad Block Detector Message', 'wpadcenter' ); ?></label>
					<textarea id="inline-form-adblock_detector_message" class="form-control" name="adblock_detected_message_field" style="width: 100%;" rows="6" v-show="adblock_detector" v-c-tooltip="'<?php esc_html_e( 'Message displayed on the adblocker detected pop up.', 'wpadcenter' ); ?>'"><?php echo esc_html( stripslashes( $the_options['adblock_detected_message'] ) ); ?></textarea>
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_general_settings' ); ?>
		</c-tab>
		<c-tab title="Scripts">
		<?php do_action( 'wpadcenter_before_scripts_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Scripts Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="ad-toggle">
						<c-switch ref="enable_scripts" v-model="enable_scripts" id="inline-form-enable_scripts" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_scripts'] ); ?> v-on:update:checked="enable_scripts = !enable_scripts"></c-switch>
						<label for="inline-form-enable_scripts"><?php esc_html_e( 'Enable Scripts', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Enable scripts on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cil-settings"></c-icon>
						<input type="hidden" name="enable_scripts_field" v-model="enable_scripts">
					</div>
				</c-card-body>
			</c-card>
			<c-card v-show="enable_scripts">
				<c-card-header><?php esc_html_e( 'Enter Scripts', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="enable_scripts_enabled">
						<label for="header_scripts_field" class="form-label" style="margin-top: 0px;">Header Scripts</label>
						<textarea id="header_scripts_field" name="header_scripts_field" class="form-control" style="width: 100%;" rows="6" v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the head section on all pages and/or posts.', 'wpadcenter' ); ?>'"><?php echo esc_html( stripslashes( $the_options['header_scripts'] ) ); ?></textarea>
						<label for="body_scripts_field" class="form-label">Body Scripts</label>
						<textarea id="body_scripts_field" name="body_scripts_field" class="form-control" style="width: 100%;" rows="6" v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the body section on all pages and/or posts.', 'wpadcenter' ); ?>'"><?php echo esc_html( stripslashes( $the_options['body_scripts'] ) ); ?></textarea>
						<label for="footer_scripts_field" class="form-label">Footer Scripts</label>
						<textarea id="footer_scripts_field" name="footer_scripts_field" class="form-control" style="width: 100%;" rows="6" v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the footer section on all pages and/or posts.', 'wpadcenter' ); ?>'"><?php echo esc_html( stripslashes( $the_options['footer_scripts'] ) ); ?></textarea>
					</div>
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_scripts_settings' ); ?>
		</c-tab>
		<c-tab title="ads.txt">
			<c-card>
				<?php do_action( 'wpadcenter_before_ads_txt_settings' ); ?>
				<c-card-header><?php esc_html_e( 'Ads.txt Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
				<div class="ad-toggle">
					<input type="hidden" name="ads_txt_tab" v-model="value" />
					<input type="hidden" name="ads_txt_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_problems' ) ); ?>">
					<input type="hidden" name="ads_txt_replace_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_replace' ) ); ?>">
					<input type="hidden" name="ads_txt_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
					<c-switch ref="enable_ads_txt" v-model="enable_ads_txt" id="inline-form-enable_ads_txt" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_ads_txt'] ); ?> v-on:update:checked="enable_ads_txt = !enable_ads_txt; this.value = enable_ads_txt ? '1' : '0';"></c-switch>
					<label for="inline-form-enable_ads_txt"><?php esc_html_e( 'Enable Ads.txt', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Enable ads.txt functionality', 'wpadcenter' ); ?>'" color="primary" name="cil-settings"></c-icon>
					<input type="hidden" name="enable_ads_txt_field" v-model="enable_ads_txt">
				</div>
				<div class="enable_ads_txt_enabled" v-show="enable_ads_txt">
					<br>
					<textarea name="ads_txt_content_field" class="form-control" style="width: 100%;" rows="6" v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the footer section on all pages and/or posts.', 'wpadcenter' ); ?>'"><?php echo esc_html( stripslashes( $the_options['ads_txt_content'] ) ); ?></textarea>
					<c-spinner class="ads_txt_spinner" style="display:block; margin: 10px 0px;" color="dark" grow></c-spinner><span class="ads_txt_problems"></span></td>
					<input type="button" class="button" name="check_ads_txt_problems" value="Check for Problems" />
				</div>
				</c-card-body>
				<?php do_action( 'wpadcenter_after_ads_txt_settings' ); ?>
			</c-card>
		</c-tab>
		<c-tab title="Import From Adsense">
		<?php do_action( 'wpadcenter_before_adsense_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Connect to Adsense', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<?php if ( ! empty( $data['accounts'] ) && is_array( $data['accounts'] ) ) : ?>
						<select name="gadsense_account_id">
							<?php foreach ( $data['accounts'] as $key => $account ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $key ); ?></option>
							<?php } ?>
						</select>

					<?php else : ?>					
						<c-input type="text" id="mapi-code" label="<?php esc_html_e( 'Token', 'wpadcenter' ); ?>" value="" placeholder="<?php esc_html_e( 'Copy and Paste the token after you have clicked connect to adsense', 'wpadcenter' ); ?>"></c-input>
						<p style="margin-top: 10px;"></p>
						<div class="token-submit">
							<button class="button init-gauthentication"><?php esc_html_e( 'Connect to AdSense', 'wpadcenter' ); ?></button>
								<button id="mapi-confirm-code" class="button">
									<?php esc_html_e( 'Submit Token', 'wpadcenter' ); ?>
								</button>
							<span class="spinner"></span>
						</div>
						<p style="margin-top: 10px;"><?php esc_html_e( 'This will open another window. Please allow access to your AdSense account. Copy the text you get at the end and come back here.', 'wpadcenter' ); ?></p>
					<?php endif; ?>
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_adsense_settings' ); ?>
		</c-tab>
	</c-tabs>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>

<script type="text/javascript">
	if ('undefined' == typeof window.AdsenseGAPI) {
		AdsenseGAPI = {};
	}
	AdsenseGAPI.nonce = '<?php echo esc_html( $nonce ); ?>';
	AdsenseGAPI.oAuth2 = '<?php echo $auth_url; // phpcs:ignore WordPress.Security.EscapeOutput ?>';
</script>

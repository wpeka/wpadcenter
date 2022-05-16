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

$the_options = Wpadcenter::wpadcenter_get_settings();
$data_obj    = \Wpeka\Adcenter\Wpadcenter_Adsense::get_instance();
$data        = $data_obj->get_saved_accounts();
$nonce       = wp_create_nonce( 'wpeka-google-adsense' );
$auth_url    = \Wpeka\Adcenter\Wpadcenter_Google_Api::get_auth_url();

?>
<style>
	[v-cloak] {
		display: none;
	}
</style>
<div id="app" v-cloak>
<div class="adc-nav">
	<div class="adc-nav-inner">
		<div class="adc-logo"></div>
	</div></div>
	<c-tabs variant="pills" ref="active_tab" id="wpadcenter_tabs">
		<?php do_action( 'wp_adcenter_before_general_tab' ); ?>
		<c-tab title="<?php esc_attr_e( 'General', 'wpadcenter' ); ?>" active href="#general">
		<?php do_action( 'wp_adcenter_before_general_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Tracking Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="wpadcenter-margin-mod">
						<input type="hidden" ref="roles_security" name="roles_security" value="<?php echo esc_attr( wp_create_nonce( 'roles_security' ) ); ?>">
						<input type="hidden" ref="roles_ajaxurl" name="roles_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
						<label for="wpadcenter-select-roles"><?php esc_html_e( 'Roles to exclude from tracking', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Select certain roles to exclude from tracking the ads', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<input type="hidden" ref="roles_selected" v-model="roles_selected" name="roles_selected_field" value="<?php echo esc_html( stripslashes( $the_options['roles_selected'] ) ); ?>">
					</div>
					<v-select id="wpadcenter-select-roles" :options="roles" taggable multiple v-model="roles_selected">
					</v-select>
				</c-card-body>
			</c-card>
			<c-card>
				<c-card-header><?php esc_html_e( 'Statistics Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>

					<label for="inline-form-trim_stats" class="wpadcenter-margin-mod"><?php esc_html_e( 'Trim statistics older than (in months)', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="{content:'<?php esc_html_e( 'Automatically clean the statistics database records older than a set point. Setting this to 0 will disable it.', 'wpadcenter' ); ?>',placement:'top'}" color="primary" name="cib-google-keep"></c-icon>
					<c-input type="number" min="0" name="trim_stats_field" value="<?php echo esc_html( stripslashes( $the_options['trim_stats'] ) ); ?>" />
				</c-card-body>
			</c-card>
			<c-card>
				<?php do_action( 'wp_adcenter_before_ads_txt_settings' ); ?>
				<c-card-header><?php esc_html_e( 'Ads.txt Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
				<div class="ad-toggle">
					<input type="hidden" name="ads_txt_tab" value="0" ref="ads_txt_tab">
					<input type="hidden" name="ads_txt_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_problems' ) ); ?>">
					<input type="hidden" name="ads_txt_replace_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_replace' ) ); ?>">
					<input type="hidden" name="ads_txt_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
					<label for="inline-form-enable_ads_txt"><?php esc_html_e( 'Enable ads.txt', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Ads.txt or “Authorized Digital Sellers” is a technical specification developed by the IAB to combat ad fraud. Enable this option to create/update ads.txt file.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<input type="hidden" name="enable_ads_txt_field" v-model="enable_ads_txt">
				</div>
				<c-switch ref="enable_ads_txt" v-model="enable_ads_txt" id="inline-form-enable_ads_txt" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_ads_txt'] ); ?> v-on:update:checked="onChangeEnableAdsTxt"></c-switch>
				<div class="enable_ads_txt_enabled" v-show="enable_ads_txt">
					<label for="ads_txt_content" class="ads-txt-label"><?php esc_html_e( 'Content', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Additional records to add to the file, one record per line.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<textarea id="ads_txt_content" name="ads_txt_content_field" class="form-control" rows="6" placeholder="google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0"><?php echo esc_html( stripslashes( $the_options['ads_txt_content'] ) ); ?></textarea>
					<c-spinner class="ads_txt_spinner" color="dark" grow></c-spinner><span class="ads_txt_problems"></span></td>
					<input type="button" class="button" name="check_ads_txt_problems" value="<?php esc_attr_e( 'Check for Problems', 'wpadcenter' ); ?>" id="check_ads_txt_problems" />
				</div>
				</c-card-body>
			</c-card>
			<c-card>
				<?php do_action( 'wp_adcenter_before_link_options' ); ?>
				<c-card-header><?php esc_html_e( 'Link Options', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
				<div class="ad-toggle">
					<label for="link_open_in_new_tab"><?php esc_html_e( 'Open link in a new tab', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'If enabled, link opens in a new tab on click.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<input type="hidden" name="link_open_in_new_tab_field" v-model="link_open_in_new_tab">
					<input ref="link_open_in_new_tab_mount" type="hidden" value="<?php echo esc_attr( $the_options['link_open_in_new_tab'] ); ?>">

				</div>
				<c-switch ref="link_open_in_new_tab" v-model="link_open_in_new_tab" id="link_open_in_new_tab" variant="3d" size="sm" color="dark" <?php checked( $the_options['link_open_in_new_tab'] ); ?> v-on:update:checked="onChangeOpenInNewTab"></c-switch>

				<div class="ad-toggle wpadcenter-settings-secondary-heading">
					<label for="link_nofollow"><?php esc_html_e( 'No follow on link', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'If enabled, it signals that the page linking out is claiming no endorsement of the page it links to.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<input type="hidden" name="link_nofollow_field" v-model="link_nofollow">
					<input ref="link_nofollow_mount" type="hidden" value="<?php echo esc_attr( $the_options['link_nofollow'] ); ?>">

				</div>
				<c-switch ref="link_nofollow" v-model="link_nofollow" id="link_nofollow" variant="3d" size="sm" color="dark" <?php checked( $the_options['link_nofollow'] ); ?> v-on:update:checked="onChangeNoFollow"></c-switch>

				<div class="wpadcenter-margin-mod wpadcenter-settings-secondary-heading">
							<label for="wpadcenter-additional-rel-tags"><?php esc_html_e( 'Additional rel attribute tags', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip='"<?php esc_html_e( 'Adds rel attribute tags to links', 'wpadcenter' ); ?>"' color="primary" name="cib-google-keep"></c-icon>
							<input type="hidden" ref="link_additional_rel_tags" v-model="link_additional_rel_tags" name="link_additional_rel_tags_field">
							<input type="hidden" ref="link_additional_rel_tags_mount" value="<?php echo esc_html( stripslashes( $the_options['link_additional_rel_tags'] ) ); ?>">

						</div>
						<v-select id="wpadcenter-additional-rel-tags" :options="additional_rel_tags_options" multiple v-model="link_additional_rel_tags"  >
						</v-select>
				<div class="wpadcenter-margin-mod wpadcenter-settings-secondary-heading">
						<label for="wpadcenter-additional-css-class" ><?php esc_html_e( 'Additional CSS classes', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'These classes will be added to the link.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						</div>
						<textarea id="wpadcenter-additional-css-class" name="link_additional_css_class_field" class="form-control" rows="1"><?php echo esc_html( stripslashes( $the_options['link_additional_css_class'] ) ); ?></textarea>
						<?php do_action( 'wp_adcenter_extend_link_options' ); ?>
				</c-card-body>
			</c-card>
			<c-card>
				<c-card-header><?php esc_html_e( 'Privacy Options', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="ad-toggle">
						<label for="inline-form-enable_privacy"><?php esc_html_e( 'Enable Privacy Module', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Show ads only to users who give the permission to cookies and ads', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<input type="hidden" name="enable_privacy_field" v-model="enable_privacy">
					</div>
					<c-switch ref="enable_privacy" v-model="enable_privacy" id="inline-form-enable_privacy" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_privacy'] ); ?> v-on:update:checked="enable_privacy = !enable_privacy"></c-switch>
					<div class="enable_privacy_enabled" v-show="enable_privacy">
						<p style="font-weight: bold; margin-top: 1rem;"><?php esc_html_e( 'Consent Method:', 'wpadcenter' ); ?></p>
						<input type="hidden" ref="consent_method" value="<?php echo esc_html( $the_options['consent_method'] ); ?>">
						<div class="radio-group">
							<input type="radio" v-model="consent_method" name="consent_method_field" id="show-all-ads-without" value='show-all-ads-without' <?php checked( 'show-all-ads-without', $the_options['consent_method'] ); ?>>
							<label for="show-all-ads-without"><?php esc_html_e( 'Show all ads without consent', 'wpadcenter' ); ?></label>
						</div>
						<div class="radio-group">
							<input type="radio" v-model="consent_method" name="consent_method_field" value="cookie" id="cookie-consent" <?php checked( 'cookie', $the_options['consent_method'] ); ?>>
							<label for="cookie-consent"><?php echo esc_html( __( 'Cookie', 'wpadcenter' ) ) . ' - <a href="https://docs.wpeka.com/wp-adcenter/privacy-options" target="_blank">Manual</a>'; ?></label>
						</div>
						<div class="cookie_method" v-show="consent_method === 'cookie'" style="margin-top: 0.5rem;">
							<div class="cookie_options">
								<label><?php esc_html_e( 'Cookie name', 'wpadcenter' ); ?></label>
								<c-input name="cookie_name_field" value="<?php echo esc_html( $the_options['cookie_name'] ); ?>"></c-input>
								<label><?php esc_html_e( 'cookie value', 'wpadcenter' ); ?></label>
								<c-input name="cookie_value_field" value="<?php echo esc_html( $the_options['cookie_value'] ); ?>"></c-input>
							</div>
							<hr />
							<div class="ad-toggle">
								<label for="inline-form-cookie_non_personalized"><?php esc_html_e( 'Show non-personalized AdSense ads until consent is given.', 'wpadcenter' ); ?></label>
								<input type="hidden" name="cookie_non_personalized_field" v-model="cookie_non_personalized">
							</div>
							<c-switch ref="cookie_non_personalized" v-model="cookie_non_personalized" id="inline-form-enable_privacy" variant="3d" size="sm" color="dark" <?php checked( $the_options['cookie_non_personalized'] ); ?> v-on:update:checked="cookie_non_personalized = !cookie_non_personalized"></c-switch>
						</div>
					</div>
				</c-card-body>
			</c-card>
			<?php do_action( 'wp_adcenter_after_general_settings' ); ?>
		</c-tab>
		<?php do_action( 'wp_adcenter_before_scripts_tab' ); ?>
		<?php do_action( 'wp_adcenter_before_integrations_tab' ); ?>
		<c-tab title="<?php esc_attr_e( 'Integrations', 'wpadcenter' ); ?>" href="#adsense">
		<?php do_action( 'wp_adcenter_before_integrations_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Connect to AdSense', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<?php if ( ! empty( $data['accounts'] ) && is_array( $data['accounts'] ) ) : ?>
						<select name="gadsense_account_id">
							<?php foreach ( $data['accounts'] as $key => $account ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $key ); ?></option>
							<?php } ?>
						</select>
						<div id="gadsense_remove_authentication" style="margin-top: 1rem;">
							<button type="button" class="button remove-gauthentication"><?php esc_html_e( 'Remove Authentication', 'wpadcenter' ); ?></button>
							<span class="spinner"></span>
						</div>

					<?php else : ?>
						<label for="mapi-code" class="wpadcenter-label-margin-bottom"><?php esc_html_e( 'Token', 'wpadcenter' ); ?></label>			
						<c-input type="text" id="mapi-code" value="" placeholder="<?php esc_html_e( 'Copy and paste the token after you have clicked connect to AdSense', 'wpadcenter' ); ?>"></c-input>
						<p class="wpadcenter-p-margin-mod"></p>
						<div class="token-submit">
							<button type="button" class="button init-gauthentication"><?php esc_html_e( 'Connect to AdSense', 'wpadcenter' ); ?></button>
								<button type="button" id="mapi-confirm-code" class="button">
									<?php esc_html_e( 'Submit Token', 'wpadcenter' ); ?>
								</button>
							<span class="spinner"></span>
						</div>
						<p class="wpadcenter-p-margin-mod"><?php esc_html_e( 'This will open another window. Please allow access to your AdSense account. Copy the text you get at the end and come back here.', 'wpadcenter' ); ?></p>
					<?php endif; ?>
				</c-card-body>
			</c-card>
			<?php do_action( 'wp_adcenter_after_integrations_settings' ); ?>
		</c-tab>
		<?php do_action( 'wp_adcenter_after_integrations_tab' ); ?>
	</c-tabs>
	<div class="adc-save">
		<div class="adc-save-button">
			<c-button color="info" class="wpadcenter_save" onClick="return wpadcenter_settings_btn_click(this.name)" name="update_admin_settings_form" type="submit">
				<span class="wpadcenter_save-text"><?php esc_html_e( 'Save Changes', 'wpadcenter' ); ?></span>
			</c-button>
		</div>
	</div>
</div>

<script type="text/javascript">
	if ('undefined' == typeof window.AdsenseGAPI) {
		AdsenseGAPI = {};
	}
	AdsenseGAPI.nonce = '<?php echo esc_html( $nonce ); ?>';
	AdsenseGAPI.oAuth2 = '<?php echo $auth_url; // phpcs:ignore WordPress.Security.EscapeOutput ?>';
</script>

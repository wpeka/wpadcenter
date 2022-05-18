
/*global jQuery*/

Vue.component( 'v-select', VueSelect.default );

const j = jQuery.noConflict();
cilPencil = ["512 512","<path fill='var(--ci-primary-color, currentColor)' d='M29.663,482.25l.087.087a24.847,24.847,0,0,0,17.612,7.342,25.178,25.178,0,0,0,8.1-1.345l142.006-48.172,272.5-272.5A88.832,88.832,0,0,0,344.334,42.039l-272.5,272.5L23.666,456.541A24.844,24.844,0,0,0,29.663,482.25Zm337.3-417.584a56.832,56.832,0,0,1,80.371,80.373L411.5,180.873,331.127,100.5ZM99.744,331.884,308.5,123.127,388.873,203.5,180.116,412.256,58.482,453.518Z' class='ci-primary'/>"]
cibGoogleKeep = ["32 32","<path d='M26.661 10.661c0-5.859-4.801-10.661-10.661-10.661s-10.661 4.803-10.661 10.661c0 3.401 1.599 6.521 4.26 8.536v10.641h2.161v2.161h8.48v-2.161h2.161v-10.641c2.681-2 4.26-5.156 4.26-8.536zM11.74 27.697v-2.099h8.52v2.099zM11.74 23.437v-2.099h8.52v2.099zM20.74 17.76l-0.48 0.319v1.119h-8.52v-1.119l-0.459-0.319c-2.401-1.599-3.803-4.239-3.803-7.099 0-4.697 3.803-8.521 8.521-8.521s8.521 3.803 8.521 8.521c0 2.86-1.401 5.521-3.803 7.099z'/>"]
cilSettings = ["512 512","<path fill='var(--ci-primary-color, currentColor)' d='M245.151,168a88,88,0,1,0,88,88A88.1,88.1,0,0,0,245.151,168Zm0,144a56,56,0,1,1,56-56A56.063,56.063,0,0,1,245.151,312Z' class='ci-primary'/><path fill='var(--ci-primary-color, currentColor)' d='M464.7,322.319l-31.77-26.153a193.081,193.081,0,0,0,0-80.332l31.77-26.153a19.941,19.941,0,0,0,4.606-25.439l-32.612-56.483a19.936,19.936,0,0,0-24.337-8.73l-38.561,14.447a192.038,192.038,0,0,0-69.54-40.192L297.49,32.713A19.936,19.936,0,0,0,277.762,16H212.54a19.937,19.937,0,0,0-19.728,16.712L186.05,73.284a192.03,192.03,0,0,0-69.54,40.192L77.945,99.027a19.937,19.937,0,0,0-24.334,8.731L21,164.245a19.94,19.94,0,0,0,4.61,25.438l31.767,26.151a193.081,193.081,0,0,0,0,80.332l-31.77,26.153A19.942,19.942,0,0,0,21,347.758l32.612,56.483a19.937,19.937,0,0,0,24.337,8.73l38.562-14.447a192.03,192.03,0,0,0,69.54,40.192l6.762,40.571A19.937,19.937,0,0,0,212.54,496h65.222a19.936,19.936,0,0,0,19.728-16.712l6.763-40.572a192.038,192.038,0,0,0,69.54-40.192l38.564,14.449a19.938,19.938,0,0,0,24.334-8.731L469.3,347.755A19.939,19.939,0,0,0,464.7,322.319Zm-50.636,57.12-48.109-18.024-7.285,7.334a159.955,159.955,0,0,1-72.625,41.973l-10,2.636L267.6,464h-44.89l-8.442-50.642-10-2.636a159.955,159.955,0,0,1-72.625-41.973l-7.285-7.334L76.241,379.439,53.8,340.562l39.629-32.624-2.7-9.973a160.9,160.9,0,0,1,0-83.93l2.7-9.972L53.8,171.439l22.446-38.878,48.109,18.024,7.285-7.334a159.955,159.955,0,0,1,72.625-41.973l10-2.636L222.706,48H267.6l8.442,50.642,10,2.636a159.955,159.955,0,0,1,72.625,41.973l7.285,7.334,48.109-18.024,22.447,38.877-39.629,32.625,2.7,9.972a160.9,160.9,0,0,1,0,83.93l-2.7,9.973,39.629,32.623Z' class='ci-primary'/>"]
cilInfo = ["512 512","<rect width='34.924' height='34.924' x='256' y='95.998' fill='var(--ci-primary-color, currentColor)' class='ci-primary'/><path fill='var(--ci-primary-color, currentColor)' d='M16,496H496V16H16ZM48,48H464V464H48Z' class='ci-primary'/><path fill='var(--ci-primary-color, currentColor)' d='M285.313,359.032a18.123,18.123,0,0,1-15.6,8.966,18.061,18.061,0,0,1-17.327-23.157l35.67-121.277A49.577,49.577,0,0,0,194.7,190.572l-11.718,28.234,29.557,12.266,11.718-28.235a17.577,17.577,0,0,1,33.1,11.7l-35.67,121.277A50.061,50.061,0,0,0,269.709,400a50.227,50.227,0,0,0,43.25-24.853l15.1-25.913-27.646-16.115Z' class='ci-primary'/>"]

var vm = new Vue( {
	el: '#app',
	data() {
		return {
			auto_refresh: null,
			adblock_detector: null,
			geo_targeting: null,
			hide_ads_logged: null,
			enable_ads_txt: null,
			value: 1,
			ajax_url: null,
			roles_security: null,
			roles: [],
			roles_selected: [],
			enable_advertisers: null,
			enable_notifications: null,
			roles_selected_visibility: [],
			content_ads: null,
			adGroups: [],
			adgroups_security: null,
			ab_testing_security: null,
			count: 0,
			test_count: 0,
			link_open_in_new_tab: null,
			link_nofollow: null,
			additional_rel_tags_options: [ 'sponsored', 'ugc' ],
			link_additional_rel_tags: [],
			enable_privacy: false,
			radioOptions: [],
			consent_method: null,
			cookie_non_personalized: false,
			enable_affiliate: false,
			enable_click_fraud_protection: false,

			enable_global_email: false,
			global_email_frequency_options: [ 'Daily', 'Weekly', 'Monthly' ],
			global_email_frequency: 'Daily',
			global_email_report_type: 'Last 7 days',
			global_email_report_type_options: [ 'Last 7 days', 'Last 30 days' ],
			frequency_message: 'The daily report is sent at 9 AM PST',
			frequency_message_options: [ 'The daily report is sent at 9 AM PST', 'The weekly report is sent on every Monday at 9 AM PST', 'The monthly report is sent on the 1st of every month at 9 AM PST' ],
		};
	},
	methods: {
		setValues: function() {
			this.enable_ads_txt = this.$refs.enable_ads_txt.checked;
			this.enable_privacy = this.$refs.enable_privacy.checked;
			this.enable_global_email = this.$refs.hasOwnProperty( 'enable_global_email' ) ? this.$refs.enable_global_email.checked : false;
			this.consent_method = this.$refs.consent_method.value;
			this.cookie_non_personalized = this.$refs.cookie_non_personalized.checked;
			this.adblock_detector = this.$refs.hasOwnProperty( 'adblock_detector' ) ? this.$refs.adblock_detector.checked : false;
			this.geo_targeting = this.$refs.hasOwnProperty( 'geo_targeting' ) ? this.$refs.geo_targeting.checked : false;
			this.enable_affiliate = this.$refs.hasOwnProperty( 'enable_affiliate' ) ? this.$refs.enable_affiliate.checked : false;
			this.$refs.ads_txt_tab.value = this.enable_ads_txt ? '1' : '0';
			if ( this.$refs.hasOwnProperty( 'geo_targeting_tab' ) ) {
				this.$refs.geo_targeting_tab.value = this.geo_targeting ? '1' : '0';
			}
			this.enable_advertisers = this.$refs.hasOwnProperty( 'enable_advertisers' ) ? this.$refs.enable_advertisers.checked : false;
			this.enable_notifications = this.$refs.hasOwnProperty( 'enable_notifications' ) ? this.$refs.enable_notifications.checked : false;
			this.content_ads = this.$refs?.content_ads ? this.$refs.content_ads.checked : false;
			this.adgroups_security = this.$refs?.adgroups_security ? this.$refs.adgroups_security.value : '';
			this.ab_testing_security = this.$refs?.ab_testing_security ? this.$refs.ab_testing_security.value : '';
			this.count = this.$refs?.count ? this.$refs.count.value : 0;
			this.test_count = this.$refs?.test_count ? this.$refs.test_count.value : 0;
			this.link_open_in_new_tab = this.$refs.link_open_in_new_tab_mount.value ? Boolean( this.$refs.link_open_in_new_tab_mount.value ) : false;
			this.link_nofollow = this.$refs.link_nofollow_mount.value ? Boolean( this.$refs.link_nofollow_mount.value ) : false;
			this.link_additional_rel_tags = this.$refs.link_additional_rel_tags_mount.value ? this.$refs.link_additional_rel_tags_mount.value.split( ',' ) : [];
			this.global_email_frequency = this.$refs.hasOwnProperty( 'global_email_frequency_mount' ) && this.$refs.global_email_frequency_mount.value ? this.$refs.global_email_frequency_mount.value : '';
			this.global_email_report_type = this.$refs.hasOwnProperty( 'global_email_report_type_mount' ) && this.$refs.global_email_report_type_mount.value ? this.$refs.global_email_report_type_mount.value : '';
			let navLinks = j( '.nav-link' ).map( function() {
				return this.getAttribute( 'href' );
			} );
			for ( let i = 0; i < navLinks.length; i++ ) {
				let re = new RegExp( navLinks[i] );
				if ( window.location.href.match( re ) ) {
					this.$refs.active_tab.activeTabIndex = i;
					break;
				}
			}
			this.ajax_url = this.$refs.roles_ajaxurl.value;
			this.roles_security = this.$refs.roles_security.value;
			j.ajax( {
				type: 'POST',
				url: this.ajax_url,
				data: {
					action: 'get_roles',
					security: this.roles_security,
				},
			} ).done( data => {
				data = JSON.parse( data );
				if ( Array.isArray( data ) ) {
					let roles_selected_visibility = data.pop();
					let roles_selected = data.pop();
					this.roles = [ ...data ];
					if ( roles_selected !== '' ) {
						this.roles_selected = roles_selected.split( ',' );
					}
					if ( roles_selected_visibility !== '' ) {
						this.roles_selected_visibility = roles_selected_visibility.split( ',' );
					}
				}
			} );
			this.enable_click_fraud_protection = this.$refs.hasOwnProperty( 'enable_click_fraud_protection' ) ? this.$refs.enable_click_fraud_protection.checked : false;
		},
		onFrequencyChange() {
			let index = this.global_email_frequency_options.indexOf( this.global_email_frequency );
			if ( index >= 0 ) {
				this.$refs.frequency_message.innerText = this.frequency_message_options[index];
				this.frequency_message = this.$refs.frequency_message.innerText;
			} else {
				this.$refs.frequency_message.innerText = '';
				this.frequency_message = '';
			}
		},
		onChangeEnableAdsTxt() {
			this.enable_ads_txt = ! this.enable_ads_txt;
			this.$refs.ads_txt_tab.value = this.enable_ads_txt ? '1' : '0';
			j( '#check_ads_txt_problems' ).click();
		},
		onChangeGeoTargeting() {
			this.geo_targeting = ! this.geo_targeting;
			this.$refs.geo_targeting_tab.value = this.geo_targeting ? '1' : '0';
			j( '#check_maxmind_license_key' ).click();
		},
		onChangeOpenInNewTab() {
			this.link_open_in_new_tab = ! this.link_open_in_new_tab;
			this.$refs.link_open_in_new_tab.value = this.link_open_in_new_tab ? '1' : '0';
		},
		onChangeNoFollow() {
			this.link_nofollow = ! this.link_nofollow;
			this.$refs.link_nofollow.value = this.link_nofollow ? '1' : '0';
		},
		onAddRuleContentAds( event ) {
			let contentAds = Vue.extend( componentContentAds );
			let component = new contentAds( {
				propsData: {
					placement_id: '',
					position: 'before-content',
					alignment: 'none',
					ad_or_adgroup: 'ads',
					adgroup_selected: [],
					ad_selected: [],
					post_selected: '',
					position_selected: '',
					element_selected: '',
					count: this.count,
					adgroups_security: this.adgroups_security,
					number: 1,
					in_feed_number: 1,
					position_reverse: false,
					show: true,
				},
			} ).$mount();
			this.count++;
			this.$refs.content_ads_enabled.appendChild( component.$el );
		},

		onCreateNewTestClick( event ) {
			let abTests = Vue.extend( componentABTests );
			let component = new abTests( {
				propsData: {
					active: true,
					test_id: '',
					placement_label: '',
					placement_names: '',
					date: new Date().toISOString(),
					placements: [],
					ab_testing_security: this.ab_testing_security,
					test_count: this.test_count,
					placements_selected: [],
					test_duration: 1,
					test_name: '',
					error_show: false,
					selected_placement_name: [],

				},
			} ).$mount();
			this.test_count++;
			this.$refs.ab_tests_enabled.appendChild( component.$el );
		},
		onChangeEnableClickFraudProtection( event ) {
			this.enable_click_fraud_protection = ! this.enable_click_fraud_protection;
			this.$refs.enable_click_fraud_protection.value = this.enable_click_fraud_protection ? '1' : '0';
		},
	},
	mounted() {
		this.setValues();
	},
	icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep },
	components: {
		'component-content-ads': componentContentAds,
		'component-ab-tests': componentABTests,
		'multiple-email-input': multipleEmailInput,
	},
} );

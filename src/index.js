import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import CoreuiVueCharts from '@coreui/vue-chartjs';
import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
import vSelect from 'vue-select';
import componentContentAds from './contentads';
import componentABTests from './abtests';
Vue.component('v-select', vSelect);


import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';

Vue.use(CoreuiVue);
Vue.use(CoreuiVueCharts)

// jquery $ as j
const j = jQuery.noConflict();

var vm = new Vue({
    el: '#app',
    data() {
        return {
            auto_refresh: null,
            adblock_detector: null,
            geo_targeting: null,
            enable_scripts: null,
            hide_ads_logged: null,
            enable_ads_txt: null,
            value: 1,
            ajax_url: null,
            roles_security: null,
            roles: [],
            roles_selected: [],
            scriptInfo: '<script type="text/javascript">console.log( "hello world" );</script>',
            enable_advertisers: null,
            enable_notifications: null,
            roles_selected_visibility: [],
            content_ads: null,
            adGroups: [],
            adgroups_security: null,
            ab_testing_security: null,
            count: 0,
            test_count: 0,
            // placement_id: '',
            link_open_in_new_tab: null,
            link_nofollow: null,
            additional_rel_tags_options: ['sponsored', 'ugc'],
            link_additional_rel_tags: [],
            enable_privacy: false,
            radioOptions: [],
            consent_method: null,
            cookie_non_personalized: false,
            enable_affiliate: false,

        }
    },
    methods: {
        setValues: function () {
            this.enable_scripts = this.$refs.enable_scripts.checked;
            this.enable_ads_txt = this.$refs.enable_ads_txt.checked;
            this.enable_privacy = this.$refs.enable_privacy.checked;
            this.consent_method = this.$refs.consent_method.value;
            this.cookie_non_personalized = this.$refs.cookie_non_personalized.checked;
            this.adblock_detector = this.$refs.hasOwnProperty('adblock_detector') ? this.$refs.adblock_detector.checked : false;
            this.geo_targeting = this.$refs.hasOwnProperty('geo_targeting') ? this.$refs.geo_targeting.checked : false;
            this.enable_affiliate = this.$refs.hasOwnProperty('enable_affiliate') ? this.$refs.enable_affiliate.checked : false;
            this.$refs.ads_txt_tab.value = this.enable_ads_txt ? "1" : "0";
            if (this.$refs.hasOwnProperty('geo_targeting_tab')) {
                this.$refs.geo_targeting_tab.value = this.geo_targeting ? "1" : "0";
            }
            this.enable_advertisers = this.$refs.hasOwnProperty('enable_advertisers') ? this.$refs.enable_advertisers.checked : false;
            this.enable_notifications = this.$refs.hasOwnProperty('enable_notifications') ? this.$refs.enable_notifications.checked : false;
            this.content_ads = this.$refs?.content_ads ? this.$refs.content_ads.checked : false;
            this.adgroups_security = this.$refs?.adgroups_security ? this.$refs.adgroups_security.value : '';
            this.ab_testing_security = this.$refs?.ab_testing_security ? this.$refs.ab_testing_security.value : '';
            this.count = this.$refs?.count ? this.$refs.count.value : 0;
            this.test_count = this.$refs?.test_count ? this.$refs.test_count.value : 0;
            // this.placement_id = this.$refs?.placement_id ? this.$refs.placement_id.value : '';
            this.link_open_in_new_tab = this.$refs.link_open_in_new_tab_mount.value ? Boolean(this.$refs.link_open_in_new_tab_mount.value) : false;
            this.link_nofollow = this.$refs.link_nofollow_mount.value ? Boolean(this.$refs.link_nofollow_mount.value) : false;
            this.link_additional_rel_tags = this.$refs.link_additional_rel_tags_mount.value ? this.$refs.link_additional_rel_tags_mount.value.split(',') : [];
            let navLinks = j('.nav-link').map(function () {
                return this.getAttribute('href');
            });
            for (let i = 0; i < navLinks.length; i++) {
                let re = new RegExp(navLinks[i]);
                if (window.location.href.match(re)) {
                    this.$refs.active_tab.activeTabIndex = i;
                    break;
                }
            }
            this.ajax_url = this.$refs.roles_ajaxurl.value;
            this.roles_security = this.$refs.roles_security.value;
            j.ajax({
                type: "POST",
                url: this.ajax_url,
                data: {
                    action: "get_roles",
                    security: this.roles_security,
                },
            }).done(data => {
                data = JSON.parse(data);
                if (Array.isArray(data)) {
                    let roles_selected_visibility = data.pop();
                    let roles_selected = data.pop();
                    this.roles = [...data];
                    if (roles_selected !== '') {
                        this.roles_selected = roles_selected.split(',');
                    }
                    if (roles_selected_visibility !== '') {
                        this.roles_selected_visibility = roles_selected_visibility.split(',');
                    }
                }
            });
        },
        onChangeEnableAdsTxt() {
            this.enable_ads_txt = !this.enable_ads_txt;
            this.$refs.ads_txt_tab.value = this.enable_ads_txt ? "1" : "0";
            j('#check_ads_txt_problems').click();
        },
        onChangeGeoTargeting() {
            this.geo_targeting = !this.geo_targeting;
            this.$refs.geo_targeting_tab.value = this.geo_targeting ? "1" : "0";
            j('#check_maxmind_license_key').click();
        },
        onChangeOpenInNewTab() {
            this.link_open_in_new_tab = !this.link_open_in_new_tab;
            this.$refs.link_open_in_new_tab.value = this.link_open_in_new_tab ? "1" : "0";
        },
        onChangeNoFollow() {
            this.link_nofollow = !this.link_nofollow;
            this.$refs.link_nofollow.value = this.link_nofollow ? "1" : "0";
        },
        onAddRuleContentAds(event) {
            let contentAds = Vue.extend(componentContentAds);
            let component = new contentAds({
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
                }
            }).$mount();
            this.count++;
            this.$refs.content_ads_enabled.appendChild(component.$el);
        },

        onCreateNewTestClick(event) {
            let abTests = Vue.extend(componentABTests);
            let component = new abTests({
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

                }
            }).$mount();
            this.test_count++;
            this.$refs.ab_tests_enabled.appendChild(component.$el);
        }
    },
    mounted() {
        this.setValues();
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep },
    components: {
        'component-content-ads': componentContentAds,
        'component-ab-tests': componentABTests,
    }
});
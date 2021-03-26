import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import CoreuiVueCharts from '@coreui/vue-chartjs';
import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';

Vue.use(CoreuiVue);
Vue.use(CoreuiVueCharts)

// jquery $ as j
const j = jQuery.noConflict();

var vm = new Vue({
   el: '#app',
    data () {
        return {
            auto_refresh: null,
            adblock_detector: null,
            enable_scripts: null,
            hide_ads_logged: null,
            enable_ads_txt: null,
            value: 1,
            ajax_url: null,
            roles_security: null,
            roles: [],
            roles_selected: [],
            scriptInfo: '<script type="text/javascript">console.log( "hello world" );</script>'
        }
    },
    methods: {
        setValues: function() {
            this.enable_scripts = this.$refs.enable_scripts.checked;
            this.enable_ads_txt = this.$refs.enable_ads_txt.checked;
            this.$refs.ads_txt_tab.value = this.enable_ads_txt ? "1" : "0";
            if( window.location.href.match(/#adsense/g) ) {
                this.$refs.active_tab.activeTabIndex=3;
            }else if( window.location.href.match(/#adstxt/g) ) {
                this.$refs.active_tab.activeTabIndex=2;
            }
            else if(window.location.href.match(/#scripts/g) ) {
                this.$refs.active_tab.activeTabIndex=1;
            }
            else {
                this.$refs.active_tab.activeTabIndex=0;
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
                if( Array.isArray( data ) ) {
                    let roles_selected = data.pop();
                    this.roles = [ ...data];
                    if( roles_selected !== '' ) {
                        this.roles_selected = roles_selected.split(',');
                    }
                }
            });
        },
        onChangeEnableAdsTxt() {
            this.enable_ads_txt = !this.enable_ads_txt;
            this.$refs.ads_txt_tab.value = this.enable_ads_txt ? "1" : "0";
            j('#check_ads_txt_problems').click();
        }
    },
    mounted() {
        this.setValues();
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
});
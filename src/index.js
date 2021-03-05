import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import CoreuiVueCharts from '@coreui/vue-chartjs';
import { cilPencil, cilSettings } from '@coreui/icons';

import '@coreui/coreui/dist/css/coreui.min.css';

Vue.use(CoreuiVue);
Vue.use(CoreuiVueCharts)

var vm = new Vue({
   el: '#app',
    data () {
        return {
            auto_refresh: null,
            adblock_detector: null,
            enable_scripts: null,
            hide_ads_logged: null,
            enable_ads_txt: null,
        }
    },
    methods: {
        setValues: function() {
            this.auto_refresh = this.$refs.auto_refresh.checked;
            this.adblock_detector = this.$refs.adblock_detector.checked;
            this.enable_scripts = this.$refs.enable_scripts.checked;
            this.hide_ads_logged = this.$refs.hide_ads_logged.checked;
            this.enable_ads_txt = this.$refs.enable_ads_txt.checked;
        },
    },
    mounted() {
        this.setValues();
    },
    icons: { cilPencil, cilSettings }
});
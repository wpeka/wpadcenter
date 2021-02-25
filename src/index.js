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
            currentAlertCounter: 10,
        }
    },
    icons: { cilPencil, cilSettings }
});
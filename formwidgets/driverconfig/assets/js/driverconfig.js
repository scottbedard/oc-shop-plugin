require('assets/js/boot');

import Vue from 'vue';
import DriverConfigComponent from '../../components/driverconfig';

$.fn.mountDriverConfigComponent = function(params) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = params.token;

    new Vue({
        el: $(this)[0],
        functional: true,
        components: { 'v-driverconfig': DriverConfigComponent },
        render: h => <v-driverconfig
            drivers={ params.drivers }>
        </v-driverconfig>,
    });
};

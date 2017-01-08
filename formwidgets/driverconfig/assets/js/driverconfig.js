require('assets/js/boot');

import Vue from 'vue';
import DriverConfigComponent from '../../components/driverconfig';
import DriverFormComponent from '../../components/form/form';

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

$.fn.mountDriverFormComponent = function(params) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = params.token;

    new Vue({
        el: $(this)[0],
        functional: true,
        components: { 'v-driver-form': DriverFormComponent },
        render: h => <v-driver-form
            lang={ params.lang }>
        </v-driver-form>
    });
}

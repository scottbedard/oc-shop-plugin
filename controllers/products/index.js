require('assets/js/boot');

import Vue from 'vue';

import Sortable from 'vue-sortable';
Vue.use(Sortable);

//
// Mount the options / inventories component
//
import OptionsInventoriesComponent from './components/options_inventories/options_inventories';
$.fn.mountOptionsInventoriesComponent = function({ inventories, lang, options, token }) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = token;
    new Vue({
        el: $(this)[0],
        components: { 'v-options-inventories': OptionsInventoriesComponent },
        render: h => <v-options-inventories
            inventories-prop={ inventories }
            lang={ lang }
            options-prop={ options }>
        </v-options-inventories>,
    });
};

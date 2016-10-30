require('assets/js/boot');

import Vue from 'vue';
import OptionsInventoriesComponent from './components/options_inventories/options_inventories';

//
// Mount the options / inventories component
//
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

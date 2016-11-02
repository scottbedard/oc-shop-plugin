require('assets/js/boot');

import Vue from 'vue';

import Sortable from 'vue-sortable';
Vue.use(Sortable);

//
// Mount the options / inventories component
//
import OptionsInventoriesComponent from './components/options_inventories/options_inventories';
$.fn.mountOptionsInventoriesComponent = function(params) {

    Vue.http.headers.common['X-CSRF-TOKEN'] = params.token;

    new Vue({
        el: $(this)[0],
        functional: true,
        components: { 'v-options-inventories': OptionsInventoriesComponent },
        render: h => <v-options-inventories
            inventories-prop={ params.inventories }
            inventory-validation={ params.inventoryValidation }
            lang={ params.lang }
            options-prop={ params.options }
            option-validation={ params.optionValidation }>
        </v-options-inventories>,
    });
};

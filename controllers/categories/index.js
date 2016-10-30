require('assets/js/boot');

import Vue from 'vue';
import ReorderComponent from './components/reorder';

//
// Mount the category reordering component
//
$.fn.mountReorderComponent = function({ categories, endpoint, lang, token }) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = token;
    new Vue({
        el: $(this)[0],
        components: { 'v-reorder': ReorderComponent },
        render: h => <v-reorder
            categories={ categories }
            endpoint={ endpoint }
            lang={ lang }>
        </v-reorder>,
    });
};

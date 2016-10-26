require('assets/js/boot');

import Vue from 'vue';
import ReorderComponent from './components/reorder';

$.fn.mountReorderComponent = function({ categories, endpoint, lang, token }) {
    // include the csrf token in the headers
    Vue.http.headers.common['X-CSRF-TOKEN'] = token;

    // mount our view component to the modal
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

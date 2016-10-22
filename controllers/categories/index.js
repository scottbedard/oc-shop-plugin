import Vue from 'vue';
import ReorderComponent from './components/reorder';

$.fn.mountReorderComponent = function({ categories, lang }) {

    new Vue({
        el: $(this)[0],
        components: { 'v-reorder': ReorderComponent },
        render: h => <v-reorder
            categories={ categories }
            lang={ lang }>
        </v-reorder>,
    });
};

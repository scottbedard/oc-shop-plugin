import Vue from 'vue';
import Sortable from 'vue-sortable';
import ReorderComponent from './components/reorder';

Vue.use(Sortable);

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

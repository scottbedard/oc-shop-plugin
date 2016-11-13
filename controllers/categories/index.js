require('assets/js/boot');

import Vue from 'vue';
import Sortable from 'vue-sortable';
Vue.use(Sortable);

import CategoryOrderComponent from './components/category_order/reorder';
import FiltersComponent from './components/filters/filters';
import ProductOrderComponent from './components/product_order/product_order';

//
// Category reordering
//
$.fn.mountReorderComponent = function({ categories, endpoint, lang, token }) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = token;
    new Vue({
        el: $(this)[0],
        components: { 'v-category-order': CategoryOrderComponent },
        render: h => <v-category-order
            categories={ categories }
            endpoint={ endpoint }
            lang={ lang }>
        </v-category-order>,
    });
};

//
// Filters
//
$.fn.mountFiltersComponent = function({ filterValidation, filtersProp, lang }) {
    new Vue({
        el: $(this)[0],
        components: { 'v-filters': FiltersComponent },
        render: h => <v-filters
            filter-validation={ filterValidation }
            filters-prop={ filtersProp }
            lang={ lang }>
        </v-filters>,
    });
};

//
// Product reordering
//
$.fn.mountProductOrderComponent = function({ lang, products }) {
    new Vue({
        el: $(this)[0],
        components: { 'v-product-order': ProductOrderComponent },
        render: h => <v-product-order
            lang={ lang }
            products-prop={ products }>
        </v-product-order>,
    });
};

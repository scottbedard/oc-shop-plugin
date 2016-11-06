require('assets/js/boot');

import Vue from 'vue';
import CategoryOrderComponent from './components/category_order/reorder';
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
// Product reordering
//
$.fn.mountProductOrderComponent = function({ lang }) {
    new Vue({
        el: $(this)[0],
        components: { 'v-product-order': ProductOrderComponent },
        render: h => <v-product-order
            lang={ lang }>
        </v-product-order>,
    });
};

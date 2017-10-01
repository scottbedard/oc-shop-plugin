import Vue from 'vue';
import Vuex from 'vuex';
import InventoryComponent from './components/inventory';
import store from './store';


// register global components and utilities
import 'assets/js/boot';

$(function() {
    // parse prop data from the target element
    const el = document.querySelector('[data-component=inventory]');

    const props = {
        lang: JSON.parse(el.dataset.lang),
    };

    // mount our formwidget to the dom
    new Vue({
        el,
        store,
        render: h => h(InventoryComponent, { props }),
    });
});

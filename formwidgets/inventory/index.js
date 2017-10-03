import Vue from 'vue';
import Vuex from 'vuex';
import inventoryComponent from './components';
import store from 'assets/js/store';

// register global components and utilities
import 'assets/js/boot';

$(function() {
    // commit initial data from the target element
    const el = document.querySelector('[data-component=inventory]');
    store.commit('inventories/setEndpoints', JSON.parse(el.dataset.endpoints));
    store.commit('inventories/setLang', JSON.parse(el.dataset.lang));

    // mount our formwidget to the dom
    new Vue({
        el,
        store,
        render: h => h(inventoryComponent),
    });
});

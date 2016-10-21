import Vue from 'vue';
import TreesortComponent from './treesort.vue';

$(function() {
    new Vue({
        el: '[data-bedard-shop="categories-treesort"]',
        render: (h) => h(TreesortComponent),
    });
});

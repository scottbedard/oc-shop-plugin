import Vue from 'vue';
import TreesortComponent from './treesort';

$(() => {
    new Vue({
        el: '[data-bedard-shop="categories-treesort"]',
        functional: true,
        render: h => h(TreesortComponent),
    });
});

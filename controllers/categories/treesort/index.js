import Vue from 'vue';
import TreesortComponent from './treesort';

$(() => {
    // Instantiate our Vue component with the same initial text and classes
    // as our target element to prevent flickering or duplicate markups.
    let $target = $('[data-bedard-shop="categories-treesort"]');

    new Vue({
        components: { 'v-treesort': TreesortComponent },
        el: $target[0],
        render: h => <v-treesort class={ $target.attr('class') }>
            { $target.text().trim() }
        </v-treesort>
    });
});

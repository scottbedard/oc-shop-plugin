import Vue from 'vue';
import Sortable from 'sortablejs';

Vue.directive('sortable', {
    inserted(el, binding) {
        return new Sortable(el, binding.value || {});
    },
});

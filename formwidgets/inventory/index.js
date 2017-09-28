import Vue from 'vue';
import InventoryComponent from './components/inventory';

import 'assets/js/boot';

$(function() {
    const el = document.querySelector('[data-component=inventory]');
    const lang = JSON.parse(el.dataset.lang);

    new Vue({
        el,
        render: h => h(InventoryComponent, {
            props: { lang },
        }),
    });
});

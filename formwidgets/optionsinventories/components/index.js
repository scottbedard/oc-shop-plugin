require('assets/js/boot');

import Vue from 'vue';
import OptionsInventoriesComponent from './optionsinventories';

$(function() {
    const el = document.querySelector('[data-component=options-inventories]');
    const lang = JSON.parse(el.dataset.lang);
    const product = JSON.parse(el.dataset.product);

    new Vue({
        el,
        render: h => h(OptionsInventoriesComponent, {
            props: { lang, product },
        }),
    });
});

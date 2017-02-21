import Vue from 'vue';
import OptionsInventoriesComponent from './optionsinventories';

$(() => {

    const el = document.querySelector('[data-component=options-inventories]');
    const lang = JSON.parse(el.dataset.lang);

    new Vue({
        el,
        render: h => h(OptionsInventoriesComponent, {
            props: { lang },
        }),
    });
});

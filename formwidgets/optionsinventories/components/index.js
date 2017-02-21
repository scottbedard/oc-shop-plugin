import Vue from 'vue';
import OptionsInventoriesComponent from './optionsinventories';

$(() => {
    console.log ('erm');
    new Vue({
        el: '[data-component=options-inventories]',
        render: h => h(OptionsInventoriesComponent),
    });
});

import axios from 'axios';
import Vue from 'vue';
import OptionsInventoriesComponent from './optionsinventories';

// register global utilities
require('assets/js/boot');

$(function() {
    // find the element we're going to mount our component to
    const el = document.querySelector('[data-component=options-inventories]');

    // parse our props from data attributes
    const endpoints = JSON.parse(el.dataset.endpoints);
    const lang = JSON.parse(el.dataset.lang);
    const name = el.dataset.name;
    const product = JSON.parse(el.dataset.product);

    // configure axios with our csrf token
    axios.defaults.headers.common['X-CSRF-TOKEN'] = el.dataset.token;

    // instantiate our component and mount it to the dom
    new Vue({
        el,
        render: h => h(OptionsInventoriesComponent, {
            props: { endpoints, lang, name, product },
        }),
    });
});

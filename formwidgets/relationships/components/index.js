import Vue from 'vue';
import RelationshipsComponent from './relationships';

// register global utilities
require('assets/js/boot');

$(function() {
    // find the element we're going to mount our component to
    const el = document.querySelector('[data-component=relationships]');

    // parse our props from data attributes
    const lang = JSON.parse(el.dataset.lang);
    const name = el.dataset.name;
    const value = JSON.parse(el.dataset.value);

    // instantiate our component and mount it to the dom
    new Vue({
        el,
        render: h => h(RelationshipsComponent, {
            props: {  lang, name, value },
        }),
    });
});

import Vue from 'vue';
import CartHistoryComponent from './cart_history';

// register global utilities
require('assets/js/boot');

$(function() {
    // find the element we're going to mount our component to
    const el = document.querySelector('[data-component=cart-history]');

    // parse our props from data attributes
    // const lang = JSON.parse(el.dataset.lang);

    // configure axios with our csrf token
    // axios.defaults.headers.common['X-CSRF-TOKEN'] = el.dataset.token;

    // instantiate our component and mount it to the dom
    new Vue({
        el,
        render: h => h(CartHistoryComponent, {
            props: {},
        }),
    });
});

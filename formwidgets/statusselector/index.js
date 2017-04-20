import Vue from 'vue';
import StatusSelectorComponent from './components/status_selector';

// register global utilities
require('assets/js/boot');

window.mountBedardShopStatusSelector = function() {
    // find the element we're going to mount our component to
    $('[data-component=status-selector]').each(function() {
        let el = $(this)[0];

        // parse our props from data attributes
        const name = el.dataset.name;
        const statuses = JSON.parse(el.dataset.statuses);
        const value = el.dataset.value;

        // instantiate our component and mount it to the dom
        new Vue({
            el,
            render: h => h(StatusSelectorComponent, {
                props: {
                    name,
                    statuses,
                    value,
                },
            }),
        });
    });
};

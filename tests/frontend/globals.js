import factory from 'spyfu-vue-factory';

// create a global container for our vue instances
beforeEach(() => {
    window.vm = undefined;
});

afterEach(() => {
    destroy(vm);
});

// click an element
window.click = function(el) {
    el.click();
};

// destroy a vue instance, typically used from afterEach
window.destroy = function(vm) {
    if (vm && vm.$destroy) {
        vm.$destroy();
    }
};

// expose our component factory globally
window.factory = factory;

// input into text fields
window.input = function(value, el) {
    el.value = value;

    return simulate('input', el);
};

// for simple components, expose a global mount function
window.mount = factory();

// execute function on next tick
window.nextTick = function(fn) {
    return setTimeout(fn, 0);
};

// simulate an html event
window.simulate = function(name, el) {
    return el.dispatchEvent(new Event(name));
};

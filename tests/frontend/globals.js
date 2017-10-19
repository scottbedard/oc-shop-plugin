import factory from 'spyfu-vue-factory';

import {
    flashMsgStub,
    hasClassStub,
    onStub,
    select2Stub,
} from './jquery_stubs';

beforeEach(() => {
    // clean up our container
    window.vm = undefined;

    // reset the unique id counter
    window.uniqueIdCount = 0;

    // and reset any jquery stubs
    flashMsgStub.reset();
    hasClassStub.reset();
    onStub.reset();
    select2Stub.reset();
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
window.simulate = function(name, el, eventSetupFn) {
    const e = new Event(name);

    if (eventSetupFn) {
        eventSetupFn(e);
    }

    return el.dispatchEvent(e);
};

// jquery fake
window.$ = function() {
    return {
        hasClass: hasClassStub,
        on: onStub,
        select2: select2Stub,
    };
};

window.$.oc = {
    flashMsg: flashMsgStub,
};

require('../scss/core');

import Vue from 'vue';

//
// Vue plugins
//
import VueResource from 'vue-resource';
Vue.use(VueResource);

//
// Global components
//
import components from './components/global';
Object.keys(components).forEach(tag => Vue.component(tag, components[tag]));

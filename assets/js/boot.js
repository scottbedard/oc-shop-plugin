import Vue from 'vue';
Vue.config.productionTip = false;

// register global components
import components from './components/global';
Object.keys(components).forEach(tag => Vue.component(tag, components[tag]));

// register global filters
import filters from './filters/global';
Object.keys(filters).forEach(tag => Vue.filter(tag, filters[tag]));

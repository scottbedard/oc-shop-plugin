import Vue from 'vue';
Vue.config.productionTip = false;

// register global directives
import './directives/sortable';

// register global plugins
import './plugins/flash_message';

// register global filters
import filters from './filters/global';
Object.keys(filters).forEach(tag => Vue.filter(tag, filters[tag]));

// register global components
import components from './components/global';
Object.keys(components).forEach(tag => Vue.component(tag, components[tag]));

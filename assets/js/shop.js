// pull in our global plugin styles
require('../scss/global');

// create a global event bus so October can communicate with our components
import Vue from 'vue';
window.BedardShop = new Vue();

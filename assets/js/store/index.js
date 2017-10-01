import { isProduction } from '../constants';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

//
// modules
//
import inventories from './modules/inventories';

//
// store
//
export default new Vuex.Store({
    modules: {
        inventories,
    },
    strict: !isProduction,
});

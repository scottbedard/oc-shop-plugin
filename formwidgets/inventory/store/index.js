import { isProduction } from 'assets/js/constants';
import actions from './actions';
import getters from './getters';
import mutations from './mutations';
import state from './state';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    getters,
    mutations,
    state,
    strict: !isProduction,
});

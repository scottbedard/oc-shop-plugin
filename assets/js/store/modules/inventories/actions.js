import axios from 'axios';
import { snakeCaseKeysDeep } from 'assets/js/utilities/helpers';

//
// actions
//
export default {

    // add a new value to an option
    addValueToOption({ commit, state }) {
        const newValue = state.optionForm.newValue.trim();

        if (newValue.length) {
            commit('addOptionFormValue', newValue);
            commit('setOptionFormNewValue', '');
        }
    },

    // create an inventory
    createInventory({ commit, state }) {
        commit('setInventoryFormIsSaving', true);

        const inventory = {};

        axios.post(state.endpoints.createInventory, { inventory }).then(() => {
            // success
            commit('setInventoryFormIsSaving', false);
        }, (error) => {
            // failure
            commit('setInventoryFormIsSaving', false);
            $.oc.flashMsg({ text: error.response.data, class: 'error' });
        });
    },

    // hide the inventory form
    hideInventoryForm({ commit }) {
        commit('setInventoryFormIsSaving', false);
        commit('setInventoryFormIsVisible', false);
    },

    // hide the option form
    hideOptionForm({ commit }) {
        commit('setOptionFormIsSaving', false);
        commit('setOptionFormIsVisible', false);
    },

    // reorder the option values
    reorderOptionValue({ commit }, indices) {
        commit('reorderOptionValue', indices);
    },

    // validate and save an option
    saveOption({ commit, state }) {
        commit('setOptionFormIsSaving', true);

        const request = axios.post(state.endpoints.validateOption, snakeCaseKeysDeep(state.optionForm.data));

        request.then(() => {
            commit('setOptionFormIsSaving', false);
        }, () => {
            commit('setOptionFormIsSaving', false);
        });

        return request;
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormContext', 'create');
        commit('setInventoryFormIsVisible', true);
    },

    // show a fresh option form
    showCreateOptionForm({ commit }) {
        commit('setOptionFormIsSaving', false);
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormNewValue', '');
        commit('setOptionFormValues', []);
    },

    toggleOptionValueDelete({ commit }, value) {
        commit('toggleOptionValueDelete', value);
    },
};

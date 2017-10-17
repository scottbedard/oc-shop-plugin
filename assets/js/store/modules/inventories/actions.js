import axios from 'axios';
import { createOption } from './factories';

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
    hideInventoryForm({ commit, dispatch }) {
        commit('setInventoryFormIsSaving', false);
        commit('setInventoryFormIsVisible', false);
    },

    // hide the option form
    hideOptionForm({ commit }) {
        commit('setOptionFormIsVisible', false);
    },

    // reorder the options
    reorderOption({ commit }, indices) {
        commit('reorderOption', indices);
    },

    // reorder the option values
    reorderOptionValue({ commit }, indices) {
        commit('reorderOptionValue', indices);
    },

    // validate and save an inventory
    saveInventory({ commit, dispatch }) {
        // @todo: validate inventory
        commit('saveInventory');
        dispatch('hideInventoryForm');
    },

    // validate and save an option
    saveOption({ commit, dispatch }) {
        // @todo: validate option
        commit('saveOption');
        dispatch('hideOptionForm');
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormContext', 'create');
        commit('setInventoryFormIsVisible', true);
    },

    // show a fresh option form
    showCreateOptionForm({ commit }) {
        commit('setOptionFormContext', 'create');
        commit('setOptionFormData', createOption());
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormNewValue', '');
    },

    // show the form of an existing option
    showEditOptionForm({ commit }, option) {
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormContext', 'update');
        commit('setOptionFormData', option);
    },

    // toggle the delete flag of an option
    toggleOptionDelete({ commit }, option) {
        commit('toggleOptionDelete', option);
    },

    // toggle the delete flag of an option value
    toggleOptionValueDelete({ commit }, value) {
        commit('toggleOptionValueDelete', value);
    },

    // update an option value
    updateOptionValue({ commit }, payload) {
        commit('updateOptionValue', payload);
    },
};

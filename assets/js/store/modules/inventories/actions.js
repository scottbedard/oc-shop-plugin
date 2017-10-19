import axios from 'axios';
import { createInventory, createOption } from './factories';

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

    // select an inventory value
    selectInventoryValue({ commit }, data) {
        commit('selectInventoryValue', data);
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormIsVisible', true);
        commit('setInventoryFormContext', 'create');
        commit('setInventoryFormData', createInventory());
    },

    // show a fresh option form
    showCreateOptionForm({ commit }) {
        commit('setOptionFormNewValue', '');
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormContext', 'create');
        commit('setOptionFormData', createOption());
    },

    // show the form for an existing inventory
    showEditInventoryForm({ commit }, data) {
        commit('setInventoryFormData', data);
        commit('setInventoryFormIsVisible', true);
        commit('setInventoryFormContext', 'update');
    },

    // show the form for an existing option
    showEditOptionForm({ commit }, data) {
        commit('setOptionFormData', data);
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormContext', 'update');
    },

    // toggle the delete flag for an inventory
    toggleInventoryDelete({ commit }, inventory) {
        commit('toggleInventoryDelete', inventory);
    },

    // toggle the delete flag for an option
    toggleOptionDelete({ commit }, option) {
        commit('toggleOptionDelete', option);
    },

    // toggle the delete flag for an option value
    toggleOptionValueDelete({ commit }, value) {
        commit('toggleOptionValueDelete', value);
    },

    // update an option value
    updateOptionValue({ commit }, payload) {
        commit('updateOptionValue', payload);
    },
};

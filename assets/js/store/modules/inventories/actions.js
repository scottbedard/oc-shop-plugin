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
    hideInventoryForm({ commit, dispatch }) {
        commit('setInventoryFormIsSaving', false);
        commit('setInventoryFormIsVisible', false);
    },

    // hide the option form
    hideOptionForm({ commit }) {
        commit('setOptionFormIsVisible', false);
    },

    // reorder the option values
    reorderOptionValue({ commit }, indices) {
        commit('reorderOptionValue', indices);
    },

    // validate and save an option
    saveOption({ commit, dispatch, state }) {
        // @todo: validate option

        // update or create the option
        commit(state.optionForm.data.id ? 'updateOption' : 'addOption');

        // close the option modal
        dispatch('hideOptionForm');
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormContext', 'create');
        commit('setInventoryFormIsVisible', true);
    },

    // show a fresh option form
    showCreateOptionForm({ commit }) {
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormNewValue', '');
        commit('setOptionFormValues', []);
    },

    // show the form of an existing option
    showEditOptionForm({ commit }, option) {
        commit('setOptionFormIsVisible', true);
        commit('setOptionFormContext', 'update');
        commit('setOptionFormData', option);
    },

    toggleOptionValueDelete({ commit }, value) {
        commit('toggleOptionValueDelete', value);
    },
};

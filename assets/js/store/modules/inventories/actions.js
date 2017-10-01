import axios from 'axios';

//
// actions
//
export default {

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
        commit('setInventoryFormIsVisible', false);
    },

    // hide the option form
    hideOptionForm({ commit }) {
        commit('setOptionFormIsVisible', false);
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormContext', 'create');
        commit('setInventoryFormIsVisible', true);
    },

    // show a fresh option form
    showCreateOptionForm({ commit }) {
        commit('setOptionFormIsVisible', true);
    },
};

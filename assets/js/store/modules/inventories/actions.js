//
// actions
//
export default {

    // hide the inventory form
    hideInventoryForm({ commit }) {
        commit('setInventoryFormIsVisible', false);
    },

    // show a fresh inventory form
    showCreateInventoryForm({ commit }) {
        commit('setInventoryFormIsVisible', true);
    },
};

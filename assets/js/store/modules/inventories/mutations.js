import { simpleSetters } from 'spyfu-vuex-helpers';
import { createInventory, createOption, createOptionValue } from './factories';
import { clone, uniqueId } from 'assets/js/utilities/helpers';
import Vue from 'vue';

//
// mutations
//
export default {
    ...simpleSetters({
        setEndpoints: 'endpoints',
        setFieldName: 'fieldName',
        setInventoryFormContext: 'inventoryForm.context',
        setInventoryFormIsLoading: 'inventoryForm.isLoading',
        setInventoryFormIsVisible: 'inventoryForm.isVisible',
        setInventoryFormQuantity: 'inventoryForm.data.quantity',
        setInventoryFormSku: 'inventoryForm.data.sku',
        setLang: 'lang',
        setOptionFormContext: 'optionForm.context',
        setOptionFormIsReordering: 'optionForm.isReordering',
        setOptionFormIsVisible: 'optionForm.isVisible',
        setOptionFormName: 'optionForm.data.name',
        setOptionFormNewValue: 'optionForm.newValue',
        setOptionFormPlaceholder: 'optionForm.data.placeholder',
        setOptionFormValues: 'optionForm.data.values',
        setOptionsIsReordering: 'optionsIsReordering',
    }),

    // add a new value to the option form
    addOptionFormValue(state, name) {
        const sortOrder = state.optionForm.data.values.length;
        state.optionForm.data.values.push(createOptionValue({ name, sortOrder }));
    },

    // reorder an option in the list
    reorderOption(state, { newIndex, oldIndex }) {
        const movedValue = state.options.splice(oldIndex, 1)[0];
        state.options.splice(newIndex, 0, movedValue);
    },

    // reorder a value in the option form
    reorderOptionValue(state, { newIndex, oldIndex }) {
        const movedValue = state.optionForm.data.values.splice(oldIndex, 1)[0];
        state.optionForm.data.values.splice(newIndex, 0, movedValue);
    },

    // update an existing inventory, or create a new one
    saveInventory(state) {
        const index = state.inventories.findIndex(obj => obj._key === state.inventoryForm.data._key);

        if (index > -1) {
            state.inventories.splice(index, 1, clone(state.inventoryForm.data));
        } else {
            state.inventories.push(createInventory(state.inventoryForm.data));
        }
    },

    // update an existing option, or create a new one
    saveOption(state) {
        const index = state.options.findIndex(obj => obj._key === state.optionForm.data._key);

        if (index > -1) {
            state.options.splice(index, 1, clone(state.optionForm.data));
        } else {
            state.options.push(createOption(state.optionForm.data));
        }
    },

    // select an inventory value
    selectInventoryValue(state, { values, selectedKey }) {
        const removedKeys = values.map(v => v._key);
        const selectedKeys = state.inventoryForm.data.valueKeys
            .filter(key => !removedKeys.includes(key))
            .concat(selectedKey)
            .map(Number)
            .sort((a, b) => a > b)
            .filter(key => key);

        state.inventoryForm.data.valueKeys = [...new Set(selectedKeys)];
    },

    // set the parent product model
    setModel(state, model) {
        // attach our _delete and _key properties
        model.options.forEach(option => {
            Vue.set(option, '_delete', false);
            Vue.set(option, '_key', uniqueId());

            option.values.forEach(value => {
                Vue.set(value, '_delete', false);
                Vue.set(value, '_key', uniqueId());
            });
        });

        // and set our model data
        state.model = model;
        state.options = clone(model.options);
    },

    // set the inventory form data
    setInventoryFormData(state, data) {
        state.inventoryForm.data = clone(data);
    },

    // set the option form data
    setOptionFormData(state, data) {
        state.optionForm.data = clone(data);
    },

    // toggle the delete flag of an inventory
    toggleInventoryDelete(state, inventory) {
        inventory._delete = !inventory._delete;
    },

    // toggle the delete flag of an option
    toggleOptionDelete(state, option) {
        option._delete = !option._delete;
    },

    // delete an option value, or toggle it's delete flag
    toggleOptionValueDelete(state, value) {
        value._delete = !value._delete;
    },

    // update an option value
    updateOptionValue(state, { key, value }) {
        const optionValue = state.optionForm.data.values.find(obj => obj._key === key);

        if (optionValue) {
            optionValue.name = value;
        }
    },
};

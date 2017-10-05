import { simpleSetters } from 'spyfu-vuex-helpers';
import { createOption, createOptionValue } from './factories';
import { clone } from 'assets/js/utilities/helpers';

//
// mutations
//
export default {
    ...simpleSetters({
        setEndpoints: 'endpoints',
        setInventoryFormContext: 'inventoryForm.context',
        setInventoryFormIsSaving: 'inventoryForm.isSaving',
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

    // set the option form data
    setOptionFormData(state, data) {
        state.optionForm.data = JSON.parse(JSON.stringify(data));
    },

    // toggle the delete flag of an option
    toggleOptionDelete(state, option) {
        option._delete = !option._delete;
    },

    // delete an option value, or toggle it's delete flag
    toggleOptionValueDelete(state, value) {
        if (value.id) {
            value._delete = ! value._delete;
        } else {
            const { values } = state.optionForm.data;

            values.splice(values.indexOf(value), 1);
        }
    },

    // update an existing option, or create a new one
    saveOption(state) {
        const optionIndex = state.options.findIndex(obj => obj._key === state.optionForm.data._key);

        if (optionIndex > -1) {
            state.options.splice(optionIndex, 1, clone(state.optionForm.data));
        } else {
            state.options.push(createOption(state.optionForm.data));
        }
    },

    // update an option value
    updateOptionValue(state, { key, value }) {
        const optionValue = state.optionForm.data.values.find(obj => obj._key === key);

        if (optionValue) {
            optionValue.name = value;
        }
    },
};

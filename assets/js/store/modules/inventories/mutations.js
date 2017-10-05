import { simpleSetters } from 'spyfu-vuex-helpers';
import { createOption, createOtionValue } from './factories';

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
    }),

    // add a new option
    addOption(state) {
        state.options.push(createOption(state.optionForm.data));
    },

    // add a new value to the option form
    addOptionFormValue(state, name) {
        const sortOrder = state.optionForm.data.values.length;
        state.optionForm.data.values.push(createOtionValue({ name, sortOrder }));
    },

    // reorder a value in the option form
    reorderOptionValue(state, { newIndex, oldIndex }) {
        const movedValue = state.optionForm.data.values.splice(oldIndex, 1)[0];
        state.optionForm.data.values.splice(newIndex, 0, movedValue);
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

    // update an existing option
    updateOption() {
        console.log ('update it');
    },
};

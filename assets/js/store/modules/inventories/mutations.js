import { simpleSetters } from 'spyfu-vuex-helpers';
import { uniqueId } from 'assets/js/utilities/helpers';

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
        setOptionFormIsSaving: 'optionForm.isSaving',
        setOptionFormIsVisible: 'optionForm.isVisible',
        setOptionFormName: 'optionForm.data.name',
        setOptionFormNewValue: 'optionForm.data.newValue',
        setOptionFormPlaceholder: 'optionForm.data.placeholder',
        setOptionFormValues: 'optionForm.data.values',
    }),

    // add a new value to the option form
    addOptionFormValue(state, newValue) {
        state.optionForm.data.values.push({
            _delete: false,
            _key: uniqueId(),
            id: null,
            name: newValue,
            sortOrder: state.optionForm.data.values.length,
        });
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
};

import { simpleSetters } from 'spyfu-vuex-helpers';

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
        setOptionFormIsSaving: 'optionForm.isSaving',
        setOptionFormIsVisible: 'optionForm.isVisible',
        setOptionFormName: 'optionForm.data.name',
        setOptionFormNewValue: 'optionForm.data.newValue',
        setOptionFormPlaceholder: 'optionForm.data.placeholder',
        setOptionFormValues: 'optionForm.data.values',
    }),

    // add a new value to the option form
    addOptionFormValue(state, name) {
        const sortOrder = state.optionForm.data.values.length;

        state.optionForm.data.values.push({ id: null, name, sortOrder });
    },
};

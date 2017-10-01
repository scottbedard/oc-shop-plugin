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
        setOptionFormContext: 'optionForm.context',
        setOptionFormIsSaving: 'optionForm.isSaving',
        setOptionFormIsVisible: 'optionForm.isVisible',
        setOptionFormName: 'optionForm.data.name',
        setOptionFormPlaceholder: 'optionForm.data.placeholder',
        setLang: 'lang',
    }),
};

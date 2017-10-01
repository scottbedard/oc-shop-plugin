import { simpleSetters } from 'spyfu-vuex-helpers';

//
// mutations
//
export default {
    ...simpleSetters({
        setInventoryFormContext: 'inventoryForm.context',
        setInventoryFormIsSaving: 'inventoryForm.isSaving',
        setInventoryFormIsVisible: 'inventoryForm.isVisible',
        setLang: 'lang',
    }),
};

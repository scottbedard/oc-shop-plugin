import { simpleSetters } from 'spyfu-vuex-helpers';

//
// mutations
//
export default {
    ...simpleSetters({
        setInventoryFormContext: 'inventoryForm.context',
        setInventoryFormIsVisible: 'inventoryForm.isVisible',
        setLang: 'lang',
    }),
};

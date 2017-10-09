import { createInventory, createOption } from './factories';

//
// state
//
export default function() {
    return {
        endpoints: {},
        fieldName: '',
        inventoryForm: {
            context: 'create',
            data: createInventory(),
            isSaving: false,
            isVisible: false,
        },
        inventories: [],
        lang: {},
        model: {},
        optionForm: {
            context: 'create',
            data: createOption(),
            isReordering: false,
            isVisible: false,
            newValue: '',
        },
        options: [],
        optionsIsReordering: false,
    };
}

//
// state
//
export default function() {
    return {
        endpoints: {},
        inventoryForm: {
            context: 'create',
            data: {
                sku: '',
                quantity: 0,
            },
            isSaving: false,
            isVisible: false,
        },
        inventories: [],
        lang: {},
        optionForm: {
            context: 'create',
            data: {
                name: '',
                newValue: '',
                placeholder: '',
                values: [],
            },
            isSaving: false,
            isVisible: false,
        },
        options: [],
    };
}

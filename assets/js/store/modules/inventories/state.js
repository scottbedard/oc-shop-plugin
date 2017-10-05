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
                _delete: false,
                _key: 0,
                id: null,
                name: 'sdff',
                placeholder: 'aaa',
                values: [],
            },
            isReordering: false,
            isVisible: false,
            newValue: '',
        },
        options: [],
    };
}

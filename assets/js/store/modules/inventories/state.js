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
        lang: {},
        optionForm: {
            context: 'create',
            data: {

            },
            isSaving: false,
            isVisible: false,
        },
    };
}

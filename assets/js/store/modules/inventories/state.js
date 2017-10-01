//
// state
//
export default function() {
    return {
        inventoryForm: {
            context: 'update',
            data: {
                sku: '',
                quantity: 0,
            },
            isSaving: false,
            isVisible: false,
        },
        lang: {},
    };
}

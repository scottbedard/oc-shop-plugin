let newId = 0;

export default {
    inventory() {
        return {
            id: null,
            is_deleted: false,
            quantity: 0,
            sku: '',
            values: {},
        };
    },
    option() {
        return {
            id: null,
            is_deleted: false,
            name: '',
            newId: `_${ newId++ }`,
            placeholder: '',
            values: [],
        };
    },
};

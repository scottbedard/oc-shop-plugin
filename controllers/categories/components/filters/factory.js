let newId = 0;

export default () => {
    return {
        comparator: null,
        id: null,
        is_deleted: false,
        left: '',
        newId: `_${ newId++ }`,
        right: '',
        sort_order: 0,
        value: 0,
    };
};

let newId = 0;

export default () => ({
    id: null,
    is_deleted: false,
    name: '',
    newId: `_${ newId++ }`,
    placeholder: '',
    values: [],
});

let newId = 0;

export default () => ({
    id: null,
    is_deleted: true,
    name: '',
    newId: `_${ newId++ }`,
    placeholder: '',
    values: [],
});

let newId = 0;

export default () => ({
    id: null,
    name: '',
    newId: `_${ newId++ }`,
    placeholder: '',
    values: [],
});

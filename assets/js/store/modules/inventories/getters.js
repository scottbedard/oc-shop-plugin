import { snakeCaseKeys } from 'assets/js/utilities/object';

//
// getters
//
export default {

    // create an array of all deleted value keys
    allDeletedValueKeys(state) {
        return state.options.reduce((keys, option) => {
            return option._delete
                ? keys.concat(option.values.map(v => v._key))
                : keys.concat(option.values.filter(v => v._delete).map(v => v._key));
        }, []);
    },

    // create an array of every value key and name
    allValueNames(state) {
        return state.options
            .map(option => option.values)
            .reduce((arr, value) => arr.concat(value), [])
            .reduce((obj, value) => {
                obj[value._key] = value.name;
                return obj;
            }, {});
    },

    // final value to send back to the server
    fieldData(state) {
        return snakeCaseKeys({
            inventories: state.inventories,
            options: state.options,
        });
    },
};

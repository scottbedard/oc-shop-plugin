import { snakeCaseKeys } from 'assets/js/utilities/object';

//
// getters
//
export default {

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

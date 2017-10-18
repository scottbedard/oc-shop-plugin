import { snakeCaseKeys } from 'assets/js/utilities/object';

//
// getters
//
export default {

    // final value to send back to the server
    fieldData(state) {
        return snakeCaseKeys({
            inventories: state.inventories,
            options: state.options,
        });
    },
};

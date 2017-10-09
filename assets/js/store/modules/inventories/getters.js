import { snakeCaseKeys } from 'assets/js/utilities/object';

//
// getters
//
export default {

    fieldData(state) {
        return snakeCaseKeys({
            inventories: state.inventories,
            options: state.options,
        });
    },
};

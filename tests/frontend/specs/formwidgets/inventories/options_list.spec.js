import inventoriesModule from 'assets/js/store/modules/inventories';
import optionsListComponent from 'formwidgets/inventory/components/options/list/list';
import { uniqueId } from 'assets/js/utilities/helpers';
import { createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';

//
// factory
//
const mount = factory({
    components: {
        'v-options-list': optionsListComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('options list', () => {

});

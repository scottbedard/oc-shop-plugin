import { createInventory } from 'assets/js/store/modules/inventories/factories';
import { uniqueId } from 'assets/js/utilities/helpers';
import inventoriesListComponent from 'formwidgets/inventory/components/inventories/list/list';
import inventoriesModule from 'assets/js/store/modules/inventories';

//
// factory
//
const mount = factory({
    components: {

    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventories list', () => {
    it('does stuff');
});

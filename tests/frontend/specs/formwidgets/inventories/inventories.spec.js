import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import { uniqueId } from 'assets/js/utilities/helpers';

//
// factory
//
const mount = factory({
    components: {
        'v-inventories': inventoriesComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventories', () => {
    it('clicking create displays a fresh form', (done) => {
        vm = mount({
            template: '<v-inventories />',
        });

        click(vm.$el.querySelector('[data-action="create"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.true;
            expect(vm.$store.state.inventories.inventoryForm.context).to.equal('create');
            done();
        });
    });
});

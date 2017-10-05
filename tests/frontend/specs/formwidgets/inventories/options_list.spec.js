import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import inventoryFormComponent from 'formwidgets/inventory/components/inventories/form/form';
import optionFormComponent from 'formwidgets/inventory/components/options/form/form';
import optionsComponent from 'formwidgets/inventory/components/options/options';
import { uniqueId } from 'assets/js/utilities/helpers';

//
// factory
//
const mount = factory({
    components: {
        'v-options': optionsComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('options list', () => {
    it('clicking create displays a fresh form', (done) => {
        vm = mount({
            template: '<v-options />',
        });

        click(vm.$el.querySelector('[data-action="create"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.optionForm.isVisible).to.be.true;
            expect(vm.$store.state.inventories.optionForm.context).to.equal('create');
            done();
        });
    });
});

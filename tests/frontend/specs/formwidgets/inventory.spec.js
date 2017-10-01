import inventoryForm from 'formwidgets/inventory/components/inventories/form/form';
import inventoriesModule from 'assets/js/store/modules/inventories';

//
// factory
//
const mount = factory({
    components: {
        'v-inventory-form': inventoryForm,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventory formwidget', () => {

    //
    // inventories
    //
    describe('inventories', () => {
        it('form displays the correct header for context', (done) => {
            vm = mount({
                template: '<v-inventory-form />',
            });

            vm.$store.commit('inventories/setInventoryFormContext', 'create');
            vm.$nextTick(() => {
                expect(vm.$el.textContent).to.include('backend.relation.create_name');

                vm.$store.commit('inventories/setInventoryFormContext', 'update');
                vm.$nextTick(() => {
                    expect(vm.$el.textContent).to.include('backend.relation.update_name');

                    done();
                });
            });
        });
    });
});

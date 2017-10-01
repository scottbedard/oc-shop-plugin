import inventoryFormComponent from 'formwidgets/inventory/components/inventories/form/form';
import inventoriesModule from 'assets/js/store/modules/inventories';
import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';

//
// factory
//
const mount = factory({
    components: {
        'v-inventory-form': inventoryFormComponent,
        'v-inventories': inventoriesComponent,
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

        it('clicking create displays a fresh form', (done) => {
            vm = mount({
                template: '<v-inventories />',
            });

            click(vm.$el.querySelector('[data-create="inventory"]'));

            vm.$nextTick(() => {
                expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.true;
                expect(vm.$store.state.inventories.inventoryForm.context).to.equal('create');
                done();
            });
        });
    });
});

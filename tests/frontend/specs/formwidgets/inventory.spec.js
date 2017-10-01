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

        describe('form', () => {
            it('displays a loading state when saving', (done) => {
                vm = mount({
                    template: '<v-inventory-form />',
                });

                expect(vm.$el.querySelector('.spinner')).to.be.null;
                vm.$store.commit('inventories/setInventoryFormIsSaving', true);

                setTimeout(() => {
                    expect(vm.$el.querySelector('.spinner')).not.to.be.null;
                    done();
                }, 500);
            });

            it('closes when cancel is clicked', () => {
                vm = mount({
                    template: '<v-inventory-form />',
                }, {
                    inventories: {
                        inventoryForm: {
                            isVisible: true,
                        },
                    },
                });

                click(vm.$el.querySelector('[data-action=cancel]'));

                expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.false;
            });
        });
    });
});

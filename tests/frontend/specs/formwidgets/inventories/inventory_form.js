import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import inventoryFormComponent from 'formwidgets/inventory/components/inventories/form/form';

//
// factory
//
const mount = factory({
    components: {
        'v-inventory-form': inventoryFormComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventory form', () => {
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

    it('tracks form data', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        });

        input('foo', vm.$el.querySelector('[data-input=sku]'));
        input(5, vm.$el.querySelector('[data-input=quantity]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.data.sku).to.equal('foo');
            expect(vm.$store.state.inventories.inventoryForm.data.quantity).to.equal(5);
            done();
        });
    });

    it('create a new inventory', () => {
        vm = mount({
            template: '<v-inventory-form />',
        });

        input('ABC123', vm.$el.querySelector('[data-input=sku]'));
        input('10', vm.$el.querySelector('[data-input=quantity]'));
        click(vm.$el.querySelector('[data-action=confirm]'));

        expect(vm.$store.state.inventories.inventories).to.deep.equal([
            createInventory({
                _delete: false,
                _key: 1,
                id: null,
                quantity: 10,
                sku: 'ABC123',
                valueKeys: [],
            }),
        ]);
    });

    it('updates an existing inventory', () => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({
                        _key: 123,
                        id: 1,
                        sku: 'ABC123',
                        quantity: 10,
                    }),
                },
                inventories: [
                    createInventory({
                        _key: 123,
                        id: 1,
                        sku: 'ABC123',
                        quantity: 10,
                    }),
                ],
            },
        });

        input('DEF456', vm.$el.querySelector('[data-input=sku]'));
        input('123', vm.$el.querySelector('[data-input=quantity]'));
        click(vm.$el.querySelector('[data-action=confirm]'));

        expect(vm.$store.state.inventories.inventories).to.deep.equal([
            createInventory({
                _delete: false,
                _key: 123,
                id: 1,
                quantity: 123,
                sku: 'DEF456',
                valueKeys: [],
            }),
        ]);
    });

    it('doesn\'t show select boxes for deleted options', () => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    isVisible: true,
                },
                options: [
                    createOption({ _delete: true, _key: 100, name: 'size' }),
                    createOption({ _delete: false, _key: 200, name: 'color' }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-option="100"]')).to.be.null;
        expect(vm.$el.querySelector('[data-option="200"]')).not.to.be.null;
    });
});

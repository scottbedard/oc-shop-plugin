import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import inventoryFormComponent from 'formwidgets/inventory/components/inventories/form/form';
import { flashMsgStub } from 'tests/frontend/jquery_stubs';

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

describe('inventory validation', () => {
    it('alerts an error when local skus are duplicated', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({ _key: 100, sku: 'ABC123' }),
                },
                inventories: [
                    createInventory({ _key: 200, sku: 'ABC123' }),
                ],
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.form.sku_unique_error',
            });

            done();
        }, 10);
    });
});

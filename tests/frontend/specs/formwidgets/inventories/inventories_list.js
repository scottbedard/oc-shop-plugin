import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import { uniqueId } from 'assets/js/utilities/helpers';
import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';
import inventoriesModule from 'assets/js/store/modules/inventories';

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
describe('inventories list', () => {
    it('opens an inventory on click', (done) => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _key: 123, sku: 'foo', quantity: 5 }),
                ],
                options: [
                    createOption({
                        name: 'foo',
                        placeholder: 'bar',
                        values: [
                            createOptionValue({ name: 'baz' }),
                        ],
                    }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-inventory="123"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.equal(true);
            expect(vm.$store.state.inventories.inventoryForm.context).to.equal('update');
            expect(vm.$el.querySelector('[data-input=sku]').value).to.equal('foo');
            expect(vm.$el.querySelector('[data-input=quantity]').value).to.equal('5');

            done();
        });
    });

    it('clicking create inventory displays a fresh form', (done) => {
        vm = mount({
            template: '<v-inventories />',
        });

        click(vm.$el.querySelector('[data-action="create"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.true;
            expect(vm.$store.state.inventories.inventoryForm.context).to.equal('create');
            expect(vm.$el.querySelector('[data-input=sku]').value).to.equal('');
            expect(vm.$el.querySelector('[data-input=quantity]').value).to.equal('0');
            done();
        });
    });
});

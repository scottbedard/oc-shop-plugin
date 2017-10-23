import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import inventoriesModule from 'assets/js/store/modules/inventories';

//
// factory
//
const mount = factory({
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('store', () => {
    it('sets the initial state', () => {
        vm = mount();

        vm.$store.commit('inventories/setModel', {
            inventories: [
                {
                    id: 1,
                    product_id: 1,
                    sku: 'abc123',
                    quantity: 1,
                    values: [
                        {
                            id: 1,
                            name: 'small',
                            sort_order: 1,
                        },
                    ],
                },
            ],
            options: [
                {
                    id: 1,
                    name: 'size',
                    placeholder: 'select size',
                    sort_order: 0,
                    values: [
                        {
                            id: 1,
                            name: 'small',
                            sort_order: 1,
                        },
                    ],
                },
            ],
        });

        // check the inventory
        expect(vm.$store.state.inventories.inventories[0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.inventories[0]['_key']).to.be.a.number;
        expect(vm.$store.state.inventories.inventories[0]['sku']).to.equal('abc123');
        expect(vm.$store.state.inventories.inventories[0]['quantity']).to.equal(1);
        expect(vm.$store.state.inventories.inventories[0].values).to.be.undefined;
        expect(vm.$store.state.inventories.inventories[0].valueKeys).to.deep
            .equal([vm.$store.state.inventories.options[0].values[0]._key]);

        // check the option
        expect(vm.$store.state.inventories.options[0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.options[0]['_key']).to.be.a.number;
        expect(vm.$store.state.inventories.options[0]['name']).to.equal('size');
        expect(vm.$store.state.inventories.options[0]['placeholder']).to.equal('select size');
        expect(vm.$store.state.inventories.options[0]['sort_order']).to.equal(0);

        // check the option value
        expect(vm.$store.state.inventories.options[0]['values'][0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.options[0]['values'][0]['_key']).to.be.a.number;
        expect(vm.$store.state.inventories.options[0]['values'][0]['name']).to.equal('small');
        expect(vm.$store.state.inventories.options[0]['values'][0]['sort_order']).to.equal(1);
    });
});

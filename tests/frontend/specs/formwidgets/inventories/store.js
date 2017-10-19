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

        // check the option
        expect(vm.$store.state.inventories.options[0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.options[0]['_key']).to.equal(3);
        expect(vm.$store.state.inventories.options[0]['name']).to.equal('size');
        expect(vm.$store.state.inventories.options[0]['placeholder']).to.equal('select size');
        expect(vm.$store.state.inventories.options[0]['sort_order']).to.equal(0);

        // check the option value
        expect(vm.$store.state.inventories.options[0]['values'][0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.options[0]['values'][0]['_key']).to.equal(4);
        expect(vm.$store.state.inventories.options[0]['values'][0]['name']).to.equal('small');
        expect(vm.$store.state.inventories.options[0]['values'][0]['sort_order']).to.equal(1);
    });
});

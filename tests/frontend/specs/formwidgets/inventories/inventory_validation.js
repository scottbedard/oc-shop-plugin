import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import { flashMsgStub } from 'tests/frontend/jquery_stubs';
import axios from 'axios';
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

describe('inventory validation', () => {
    beforeEach(() => {
        // assume a successful resonse from the server
        axios.post.withArgs('http://validate/inventory').resolves({ data: 'Ok' });
    });

    it('allows acceptable input', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({
                        _key: 100,
                        sku: 'ABC123',
                        quantity: 1,
                    }),
                },
                inventories: [
                    createInventory({
                        _key: 100,
                        sku: 'ABC123',
                    }),
                ],
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            // console.log (flashMsgStub.lastCall.args[0]);
            expect(flashMsgStub).not.to.have.been.called;
            done();
        }, 10);
    });

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
                text: 'bedard.shop.inventories.form.sku_unique_local_error',
            });

            done();
        }, 10);
    });

    it('alerts an error when quantity is less than zero', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({ quantity: -1 }),
                },
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.form.quantity_negative_error',
            });

            done();
        }, 10);
    });

    it('alerts an error for duplicate default inventories', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({ _key: 1, valueKeys: [] }),
                },
                inventories: [
                    createInventory({ _key: 2, valueKeys: [] }),
                ],
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.form.default_exists_error',
            });

            done();
        }, 10);
    });

    it('alerts an error if value keys aren\'t unique', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({ _key: 1, valueKeys: [1, 2] }),
                },
                inventories: [
                    createInventory({ _key: 2, valueKeys: [1, 2] }),
                ],
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.form.value_collision_error',
            });

            done();
        }, 10);
    });

    it('alerts an error if the server throws an error', (done) => {
        vm = mount({
            template: '<v-inventory-form />',
        }, {
            inventories: {
                inventoryForm: {
                    data: createInventory({ sku: 'foo' }),
                },
            },
        });

        vm.$store.commit('inventories/setEndpoints', { validateInventory: 'http://validate/inventory' });

        axios.post.withArgs('http://validate/inventory').rejects({
            response: {
                data: 'some.error.message',
            },
        });

        submit(vm.$el.querySelector('form'));

        setTimeout(() => {
            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'some.error.message',
            });

            done();
        }, 10);
    });
});

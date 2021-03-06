import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import { flashMsgStub } from 'tests/frontend/jquery_stubs';
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

    it('flags inventories for deletion', (done) => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _delete: false, _key: 100 }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-inventory="100"] [data-action="toggle-delete"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventories[0]._delete).to.be.true;
            expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.true;
            click(vm.$el.querySelector('[data-inventory="100"] [data-action="toggle-delete"]'));

            vm.$nextTick(() => {
                expect(vm.$store.state.inventories.inventories[0]._delete).to.be.false;
                expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.false;
                done();
            });
        });
    });

    it('appears as deleted if an option was deleted', (done) => {
        const option = createOption({
            _delete: false,
            _key: 200,
            values: [
                createOptionValue({ _key: 300 }),
            ],
        });

        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _delete: false, _key: 100, valueKeys: [300] }),
                ],
                options: [option],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.false;

        vm.$store.commit('inventories/toggleOptionDelete', option);

        vm.$nextTick(() => {
            expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.true;
            done();
        });
    });

    it('appears as deleted if a value was deleted', (done) => {
        const value = createOptionValue({ _delete: false, _key: 200 });

        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _delete: false, _key: 100, valueKeys: [200] }),
                ],
                options: [
                    createOption({ values: [value] }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.false;

        vm.$store.commit('inventories/toggleOptionValueDelete', value);

        vm.$nextTick(() => {
            expect(vm.$el.querySelector('[data-inventory="100"]').classList.contains('is-deleted')).to.be.true;
            done();
        });
    });

    it('doesn\'t allow inventories to be opened if they are deleted', (done) => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _delete: true, _key: 100 }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-inventory="100"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.false;

            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.list.delete_warning',
            });

            done();
        });
    });

    it('doesn\'t allow inventories to be opened if an option is deleted', (done) => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({
                        _key: 100,
                        valueKeys: [300],
                    }),
                ],
                options: [
                    createOption({
                        _delete: true,
                        values: [
                            createOptionValue({ _key: 300 }),
                        ],
                    }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-inventory="100"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.false;

            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.list.delete_option_warning',
            });

            done();
        });
    });

    it('doesn\'t allow inventories to be opened if a value is deleted', (done) => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({  _key: 100, valueKeys: [300] }),
                ],
                options: [
                    createOption({
                        values: [
                            createOptionValue({ _delete: true, _key: 300 }),
                        ],
                    }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-inventory="100"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.false;

            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.inventories.list.delete_option_warning',
            });

            done();
        });
    });

    it('displays an inventory\'s sku if there is one', () => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _key: 100, sku: 'foo' }),
                    createInventory({ _key: 200, sku: null }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"] [data-sku]').textContent.trim()).to.equal('foo,');
        expect(vm.$el.querySelector('[data-inventory="200"] [data-sku]')).to.be.null;
    });

    it('displays an out of stock message', () => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _key: 100, quantity: 0 }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"] [data-quantity]').textContent.trim()).to
            .equal('bedard.shop.inventories.list.out_of_stock');
    });

    it('displays a singular in stock message', () => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _key: 100, quantity: 1 }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"] [data-quantity]').textContent.trim()).to
            .equal('bedard.shop.inventories.list.single_in_stock');
    });

    it('displays a multiple in stock message', () => {
        vm = mount({
            template: '<v-inventories />',
        }, {
            inventories: {
                inventories: [
                    createInventory({ _key: 100, quantity: 2 }),
                ],
            },
        });

        expect(vm.$el.querySelector('[data-inventory="100"] [data-quantity]').textContent.trim()).to
            .equal('bedard.shop.inventories.list.multiple_in_stock');
    });
});

import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import { uniqueId } from 'assets/js/utilities/helpers';
import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import optionsComponent from 'formwidgets/inventory/components/options/options';

//
// factory
//
const mount = factory({
    components: {
        'v-inventories': inventoriesComponent,
        'v-options': optionsComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

describe('options inventories form widget', () => {
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
        expect(vm.$store.state.inventories.options[0]['_key']).to.equal(2);
        expect(vm.$store.state.inventories.options[0]['name']).to.equal('size');
        expect(vm.$store.state.inventories.options[0]['placeholder']).to.equal('select size');
        expect(vm.$store.state.inventories.options[0]['sort_order']).to.equal(0);

        // check the option value
        expect(vm.$store.state.inventories.options[0]['values'][0]['_delete']).to.be.false;
        expect(vm.$store.state.inventories.options[0]['values'][0]['_key']).to.equal(3);
        expect(vm.$store.state.inventories.options[0]['values'][0]['name']).to.equal('small');
        expect(vm.$store.state.inventories.options[0]['values'][0]['sort_order']).to.equal(1);
    });

    it('clicking create option displays a fresh form', (done) => {
        vm = mount({
            template: '<v-options />',
        });

        click(vm.$el.querySelector('[data-action="create"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.optionForm.context).to.equal('create');
            expect(vm.$el.querySelector('[data-input=name]').value).to.equal('');
            expect(vm.$el.querySelector('[data-input=placeholder]').value).to.equal('');
            expect(vm.$el.querySelectorAll('[data-input=option-value]').length).to.equal(0);

            done();
        });
    });

    it('opens an option on click', (done) => {
        vm = mount({
            template: '<v-options />',
        }, {
            inventories: {
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

        click(vm.$el.querySelector('.list-item'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.optionForm.context).to.equal('update');
            expect(vm.$el.querySelector('[data-input=name]').value).to.equal('foo');
            expect(vm.$el.querySelector('[data-input=placeholder]').value).to.equal('bar');
            expect(vm.$el.querySelector('[data-input=option-value]').value).to.equal('baz');
            expect(vm.$el.querySelectorAll('[data-input=option-value]').length).to.equal(1);

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
});

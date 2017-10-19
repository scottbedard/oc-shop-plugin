import { createInventory, createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import optionsComponent from 'formwidgets/inventory/components/options/options';
import {flashMsgStub} from 'tests/frontend/jquery_stubs';

//
// factory
//
const mount = factory({
    components: {
        'v-options': optionsComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('options list', () => {
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
                        _key: 123,
                        name: 'foo',
                        placeholder: 'bar',
                        values: [
                            createOptionValue({ name: 'baz' }),
                        ],
                    }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-option="123"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.optionForm.context).to.equal('update');
            expect(vm.$el.querySelector('[data-input=name]').value).to.equal('foo');
            expect(vm.$el.querySelector('[data-input=placeholder]').value).to.equal('bar');
            expect(vm.$el.querySelector('[data-input=option-value]').value).to.equal('baz');
            expect(vm.$el.querySelectorAll('[data-input=option-value]').length).to.equal(1);

            done();
        });
    });

    it('doesn\'t allow deleted options to be opened', (done) => {
        vm = mount({
            template: '<v-options />',
        }, {
            inventories: {
                options: [
                    createOption({ _delete: true, _key: 123 }),
                ],
            },
        });

        click(vm.$el.querySelector('[data-option="123"]'));

        vm.$nextTick(() => {
            expect(vm.$store.state.inventories.optionForm.isVisible).to.be.false;

            expect(flashMsgStub).to.have.been.calledWith({
                class: 'error',
                text: 'bedard.shop.options.list.delete_warning',
            });

            done();
        });
    });
});

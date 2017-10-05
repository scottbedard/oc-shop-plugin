import inventoriesModule from 'assets/js/store/modules/inventories';
import optionsComponent from 'formwidgets/inventory/components/options/options';
import { uniqueId } from 'assets/js/utilities/helpers';
import { createOption, createOptionValue } from 'assets/js/store/modules/inventories/factories';

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
describe('options', () => {
    it('clicking create displays a fresh form', (done) => {
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
});

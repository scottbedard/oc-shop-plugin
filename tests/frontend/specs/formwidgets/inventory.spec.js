import inventoriesComponent from 'formwidgets/inventory/components/inventories/inventories';
import inventoriesModule from 'assets/js/store/modules/inventories';
import inventoryFormComponent from 'formwidgets/inventory/components/inventories/form/form';
import optionFormComponent from 'formwidgets/inventory/components/options/form/form';
import optionsComponent from 'formwidgets/inventory/components/options/options';
import { uniqueId } from 'assets/js/utilities/helpers';

//
// factory
//
const mount = factory({
    components: {
        'v-inventories': inventoriesComponent,
        'v-inventory-form': inventoryFormComponent,
        'v-option-form': optionFormComponent,
        'v-options': optionsComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventory form widget', () => {

    //
    // options
    //
    describe('options', () => {
        it('clicking create displays a fresh form', (done) => {
            vm = mount({
                template: '<v-options />',
            });

            click(vm.$el.querySelector('[data-action="create"]'));

            vm.$nextTick(() => {
                expect(vm.$store.state.inventories.optionForm.isVisible).to.be.true;
                expect(vm.$store.state.inventories.optionForm.context).to.equal('create');
                done();
            });
        });

        describe('form', () => {
            it('form displays the correct header for context', (done) => {
                vm = mount({
                    template: '<v-option-form />',
                });

                vm.$store.commit('inventories/setOptionFormContext', 'create');
                vm.$nextTick(() => {
                    expect(vm.$el.textContent).to.include('backend.relation.create_name');

                    vm.$store.commit('inventories/setOptionFormContext', 'update');
                    vm.$nextTick(() => {
                        expect(vm.$el.textContent).to.include('backend.relation.update_name');

                        done();
                    });
                });
            });

            it('closes when cancel is clicked', () => {
                vm = mount({
                    template: '<v-option-form />',
                }, {
                    inventories: {
                        optionForm: { isVisible: true },
                    },
                });

                click(vm.$el.querySelector('[data-action=cancel]'));

                expect(vm.$store.state.inventories.optionForm.isVisible).to.be.false;
            });

            it('tracks form data', (done) => {
                vm = mount({
                    template: '<v-option-form />',
                });

                input('foo', vm.$el.querySelector('[data-input=new-value]'));
                input('bar', vm.$el.querySelector('[data-input=name]'));
                input('baz', vm.$el.querySelector('[data-input=placeholder]'));

                vm.$nextTick(() => {
                    expect(vm.$store.state.inventories.optionForm.newValue).to.equal('foo');
                    expect(vm.$store.state.inventories.optionForm.data.name).to.equal('bar');
                    expect(vm.$store.state.inventories.optionForm.data.placeholder).to.equal('baz');
                    done();
                });
            });

            it('also adds values on enter', () => {
                vm = mount({
                    template: '<v-option-form />',
                });

                const dispatch = sinon.stub(vm.$store, 'dispatch');

                const enter = new Event('keydown');
                enter.keyCode = 13;
                vm.$el.querySelector('[data-input=new-value]').dispatchEvent(enter);

                expect(dispatch).to.have.been.calledWith('inventories/addValueToOption');
            });

            it('adds values to the form', () => {
                vm = mount({
                    template: '<v-option-form />',
                });

                // enter a value
                input('whatever', vm.$el.querySelector('[data-input=new-value]'));

                // pressing tab should submit the value
                const dispatch = sinon.spy(vm.$store, 'dispatch');
                const tab = new Event('keydown');
                tab.keyCode = 9;
                vm.$el.querySelector('[data-input=new-value]').dispatchEvent(tab);

                expect(dispatch).to.have.been.calledWith('inventories/addValueToOption');

                // a new value should have been added to the form data
                expect(vm.$store.state.inventories.optionForm.data.values).to.deep.equal([
                        { _delete: false, _key: 0, id: null, name: 'whatever', sortOrder: 0 },
                ]);

                // and the input should be cleared
                expect(vm.$store.state.inventories.optionForm.newValue).to.equal('');
            });

            it('reorders values in the form', () => {
                vm = mount({
                    template: '<v-option-form ref="optionForm"/>',
                }, {
                    inventories: {
                        optionForm: {
                            data: {
                                values: [
                                    { _delete: false, _key: 0, id: 0, name: 'foo' },
                                    { _delete: false, _key: 1, id: 1, name: 'bar' },
                                    { _delete: false, _key: 2, id: 2, name: 'baz' },
                                ],
                            },
                        },
                    },
                });

                vm.$store.dispatch('inventories/reorderOptionValue', { newIndex: 1, oldIndex: 2 });

                expect(vm.$store.state.inventories.optionForm.data.values.map(v => v._key)).to.deep.equal([0, 2, 1]);
            });

            it('toggles a value\'s delete flag on click if an id is present', (done) => {
                vm = mount({
                    template: '<v-option-form ref="optionForm"/>',
                }, {
                    inventories: {
                        optionForm: {
                            data: {
                                values: [{ _delete: false, _key: 0, id: 1, name: 'foo' }],
                            },
                        },
                    },
                });

                click(vm.$el.querySelector('[data-action=delete]'));

                vm.$nextTick(() => {
                    expect(vm.$el.querySelector('.oc-icon-trash-o')).to.be.null;
                    expect(vm.$el.querySelector('.oc-icon-undo')).not.to.be.null;
                    expect(vm.$store.state.inventories.optionForm.data.values[0]._delete).to.be.true;

                    click(vm.$el.querySelector('[data-action=delete]'));

                    vm.$nextTick(() => {
                        expect(vm.$el.querySelector('.oc-icon-trash-o')).not.to.be.null;
                        expect(vm.$el.querySelector('.oc-icon-undo')).to.be.null;
                        expect(vm.$store.state.inventories.optionForm.data.values[0]._delete).to.be.false;

                        done();
                    });
                });

            });

            it('removes a value when delete is clicked and no id is present', () => {
                vm = mount({
                    template: '<v-option-form ref="optionForm"/>',
                }, {
                    inventories: {
                        optionForm: {
                            data: {
                                values: [{ _delete: false, _key: 0, id: null, name: 'foo' }],
                            },
                        },
                    },
                });

                click(vm.$el.querySelector('[data-action=delete]'));
                expect(vm.$store.state.inventories.optionForm.data.values.length).to.equal(0);
            });

            it('saves option on confirm', () => {
                vm = mount({
                    template: '<v-option-form />',
                });

                const dispatch = sinon.stub(vm.$store, 'dispatch');
                click(vm.$el.querySelector('[data-action="confirm"]'));
                expect(dispatch).to.have.been.calledWith('inventories/saveOption');
            });

            it('creates new options', () => {
                vm = mount({
                    template: '<v-option-form />',
                });

                const uid = uniqueId();
                input('foo', vm.$el.querySelector('[data-input=name]'));
                input('bar', vm.$el.querySelector('[data-input=placeholder]'));
                input('baz', vm.$el.querySelector('[data-input=new-value]'));
                simulate('keydown', vm.$el.querySelector('[data-input=new-value]'), e => e.keyCode = 13);
                click(vm.$el.querySelector('[data-action=confirm]'));

                expect(vm.$store.state.inventories.options).to.deep.equal([{
                    _delete: false,
                    _key: uid + 2,
                    id: null,
                    name: 'foo',
                    placeholder: 'bar',
                    sortOrder: 0,
                    values: [{
                        _delete: false,
                        _key: uid + 1,
                        id: null,
                        name: 'baz',
                        sortOrder: 0,
                    }],
                }]);
            });
        });
    });

    //
    // inventories
    //
    describe('inventories', () => {
        it('clicking create displays a fresh form', (done) => {
            vm = mount({
                template: '<v-inventories />',
            });

            click(vm.$el.querySelector('[data-action="create"]'));

            vm.$nextTick(() => {
                expect(vm.$store.state.inventories.inventoryForm.isVisible).to.be.true;
                expect(vm.$store.state.inventories.inventoryForm.context).to.equal('create');
                done();
            });
        });

        describe('form', () => {
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

            it('displays a loading state when saving', (done) => {
                vm = mount({
                    template: '<v-inventory-form />',
                });

                expect(vm.$el.querySelector('.spinner')).to.be.null;
                vm.$store.commit('inventories/setInventoryFormIsSaving', true);

                setTimeout(() => {
                    expect(vm.$el.querySelector('.spinner')).not.to.be.null;
                    done();
                }, 500);
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

            it('creates in the create context', () => {
                vm = mount({
                    template: '<v-inventory-form />',
                }, {
                    inventories: {
                        inventoryForm: { context: 'create' },
                    },
                });

                const dispatch = sinon.stub(vm.$store, 'dispatch');
                click(vm.$el.querySelector('[data-action="confirm"]'));
                expect(dispatch).to.have.been.calledWith('inventories/createInventory');
            });

            it('updates in the update context', () => {
                vm = mount({
                    template: '<v-inventory-form />',
                }, {
                    inventories: {
                        inventoryForm: { context: 'update' },
                    },
                });

                const dispatch = sinon.stub(vm.$store, 'dispatch');
                click(vm.$el.querySelector('[data-action="confirm"]'));
                expect(dispatch).to.have.been.calledWith('inventories/updateInventory');
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
        });
    });
});

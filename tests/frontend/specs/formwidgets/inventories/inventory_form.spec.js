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
        'v-inventory-form': inventoryFormComponent,
    },
    modules: {
        inventories: inventoriesModule,
    },
});

//
// tests
//
describe('inventory form', () => {
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

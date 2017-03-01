<template>
    <div class="bedard-shop options-inventories">
        <!-- Options -->
        <div class="form-group span-left">
            <label>{{ 'bedard.shop.options.plural' | trans(lang) }}</label>
            <v-option-list
                :lang="lang"
                :options="options"
                @click="onOptionClicked"
                @delete="onOptionDeleted"
                @reorder="onOptionsReordered"
            />
            <v-option-form
                ref="optionForm"
                :endpoints="endpoints"
                :lang="lang"
                @create="onOptionCreated"
                @update="onOptionUpdated"
            />
            <v-create @click="onCreateOptionClicked">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </v-create>
        </div>

        <!-- Inventories -->
        <div class="form-group span-right">
            <label>{{ 'bedard.shop.inventories.plural' | trans(lang) }}</label>
            <v-inventory-list
                :inventories="inventories"
                :lang="lang"
                :options="options"
                @click="onInventoryClicked"
                @delete="onInventoryDeleted"
            />
            <v-inventory-form
                ref="inventoryForm"
                :endpoints="endpoints"
                :inventories="inventories"
                :lang="lang"
                :options="options"
                @create="onInventoryCreated"
                @update="onInventoryUpdated"
            />
            <v-create @click="onCreateInventoryClicked">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-create>
        </div>

        <!-- This field passes our data back to the form widget -->
        <input type="hidden" :name="name" :value="formData" />
    </div>
</template>

<script>
    import { clone, renameKey } from 'assets/js/utilities/helpers';

    export default {
        created() {
            this.inventories = clone(this.product.inventories);
            this.options = clone(this.product.options);

            window.BedardShop.$on('save', this.onFormSaved);
        },
        data() {
            return {
                inventories: [],
                options: [],
            };
        },
        components: {
            'v-create': require('./create/create'),
            'v-inventory-form': require('./inventory_form/inventory_form'),
            'v-inventory-list': require('./inventory_list/inventory_list'),
            'v-option-form': require('./option_form/option_form'),
            'v-option-list': require('./option_list/option_list'),
        },
        computed: {
            formData() {
                return JSON.stringify({
                    inventories: this.inventories.map(inventory => {
                        inventory = clone(inventory);
                        inventory.value_ids = inventory.values.map(value => value.id);
                        delete inventory.values;

                        return inventory;
                    }),
                    options: this.options.map(option => {
                        return renameKey(clone(option), 'values', 'value_data');
                    }),
                });
            },
        },
        methods: {
            onCreateInventoryClicked() {
                this.$refs.inventoryForm.show();
            },
            onCreateOptionClicked() {
                this.$refs.optionForm.show();
            },
            onFormSaved() {
                // remove deleted options that no longer exist
                this.options = this.options.filter(option => ! option._deleted);

                // remove values that no longer exist
                this.options.forEach(option => {
                    option.values = option.values.filter(value => ! value._deleted);
                });
            },
            onInventoryClicked(inventory) {
                this.$refs.inventoryForm.show(inventory);
            },
            onInventoryCreated(inventory) {
                this.inventories.push(inventory);
            },
            onInventoryDeleted(inventory) {
                this.$set(inventory, '_deleted', ! inventory._deleted);
            },
            onInventoryUpdated(newInventory) {
                let index = this.inventories.findIndex(inventory => inventory.id === newInventory.id);
                this.inventories.splice(index, 1, newInventory);
            },
            onOptionClicked(option) {
                this.$refs.optionForm.show(option);
            },
            onOptionCreated(option) {
                this.options.push(option);
            },
            onOptionDeleted(option) {
                this.$set(option, '_deleted', ! option._deleted);
            },
            onOptionsReordered({ newIndex, oldIndex }) {
                const option = this.options.splice(oldIndex, 1)[0];
                this.options.splice(newIndex, 0, option);
            },
            onOptionUpdated(newOption) {
                let index = this.options.findIndex(option => option.id === newOption.id);
                this.options.splice(index, 1, newOption);

                this.syncInventoryValues();
            },
            syncInventoryValues() {
                // create a flattened array of our current values
                let currentValues = [];
                this.options.forEach(o => o.values.forEach(v => currentValues.push(v)));

                // itterate over each value in our inventories
                this.inventories.forEach(inventory => {
                    inventory.values.forEach((value, index) => {

                        // make sure the inventory's value matches our current value
                        let currentValue = currentValues.find(v => v.id == value.id);
                        value.name = currentValue.name;
                    });
                });
            },
        },
        props: [
            'endpoints',
            'lang',
            'name',
            'product',
        ],
    };
</script>

<template>
    <div class="bedard-shop options-inventories">
        <!-- Options -->
        <div class="form-group span-left">
            <label>{{ 'bedard.shop.options.plural' | trans(lang) }}</label>
            <v-option-list
                :lang="lang"
                :options="options"
                @click="onOptionClicked"
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
            <v-inventory-form ref="inventoryForm" :lang="lang" />
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
            this.options = clone(this.product.options);
        },
        data() {
            return {
                options: [],
            };
        },
        components: {
            'v-create': require('./create/create'),
            'v-inventory-form': require('./inventory_form/inventory_form'),
            'v-option-form': require('./option_form/option_form'),
            'v-option-list': require('./option_list/option_list'),
        },
        computed: {
            formData() {
                // clone the option object and rename the values
                // key so our option can be validated and saved
                return this.options.map(option => renameKey(clone(option), 'values', 'value_data'));
            },
        },
        methods: {
            onCreateInventoryClicked() {
                this.$refs.inventoryForm.show();
            },
            onCreateOptionClicked() {
                this.$refs.optionForm.show();
            },
            onOptionClicked(option) {
                this.$refs.optionForm.show(option);
            },
            onOptionCreated(option) {
                this.options.push(option);
            },
            onOptionsReordered({ newIndex, oldIndex }) {
                const option = this.options.splice(oldIndex, 1)[0];
                this.options.splice(newIndex, 0, option);
            },
            onOptionUpdated(newOption) {
                let oldOption = this.options.find(option => option.id === newOption.id);
                let index = this.options.indexOf(oldOption);
                this.options.splice(index);
                this.options.splice(index, 0, newOption);
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

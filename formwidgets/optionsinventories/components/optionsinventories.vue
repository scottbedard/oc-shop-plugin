<template>
    <div class="bedard-shop options-inventories">
        <!-- Options -->
        <div class="form-group span-left">
            <label>{{ 'bedard.shop.options.plural' | trans(lang) }}</label>
            <v-option-form ref="optionForm" :lang="lang" />
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
    </div>
</template>

<script>
    export default {
        data() {
            return {
                optionFormIsVisible: false,
            };
        },
        components: {
            'v-create': require('./create/create'),
            'v-inventory-form': require('./inventory_form/inventory_form'),
            'v-option-form': require('./option_form/option_form'),
        },
        methods: {
            onCreateInventoryClicked() {
                this.$refs.inventoryForm.show();
            },
            onCreateOptionClicked() {
                this.$refs.optionForm.show();
            },
        },
        props: [
            'lang',
        ],
    };
</script>

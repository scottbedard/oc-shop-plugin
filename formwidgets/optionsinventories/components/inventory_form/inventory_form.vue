<style lang="scss" scoped>@import 'core';

</style>

<template>
    <v-modal ref="modal">
        <!-- Header -->
        <v-modal-header>
            <template v-if="context === 'create'">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </template>
            <template v-if="context === 'update'">
                {{ 'backend.relation.update_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </template>
        </v-modal-header>

        <!-- Body -->
        <v-modal-body>
            <v-option-selector
                :options="options"
            />
            <v-form-input
                ref="sku">
                {{ 'bedard.shop.inventories.form.sku' | trans(lang) }}
            </v-form-input>
            <v-form-input
                required
                ref="price">
                {{ 'bedard.shop.inventories.form.price' | trans(lang) }}
            </v-form-input>
        </v-modal-body>

        <!-- Footer -->
        <v-modal-footer>
            Footer
        </v-modal-footer>
    </v-modal>
</template>

<script>
    export default {
        data() {
            return {
                context: 'create',
            };
        },
        components: {
            'v-option-selector': require('./option_selector/option_selector'),
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
        },
        props: [
            'lang',
            'options',
        ],
    };
</script>

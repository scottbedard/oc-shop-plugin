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
            Body
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
        methods: {
            show() {
                this.$refs.modal.show();
            },
        },
        props: [
            'lang',
        ],
    };
</script>
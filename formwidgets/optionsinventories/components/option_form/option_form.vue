<style lang="scss" scoped>@import 'core';

</style>

<template>
    <v-modal ref="modal">
        <!-- Header -->
        <v-modal-header>
            <template v-if="context === 'create'">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </template>
            <template v-if="context === 'update'">
                {{ 'backend.relation.update_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </template>
        </v-modal-header>

        <!-- Body -->
        <v-modal-body>
            <v-input v-model="option.name" required>
                <label slot="label">{{ 'bedard.shop.options.form.name' | trans(lang) }}</label>
            </v-input>
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
                option: {
                    name: '',
                },
                context: 'create',
            };
        },
        methods: {
            show(option = {}) {
                this.context = typeof option.id === 'undefined'
                    ? 'create'
                    : 'update';

                this.$refs.modal.show();
            },
        },
        props: [
            'lang',
        ],
    };
</script>

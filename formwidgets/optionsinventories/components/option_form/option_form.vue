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
            <v-form-input v-model="option.name" required>
                {{ 'bedard.shop.options.form.name' | trans(lang) }}
            </v-form-input>
            <v-form-input v-model="option.placeholder">
                {{ 'bedard.shop.options.form.placeholder' | trans(lang ) }}
            </v-form-input>
        </v-modal-body>

        <!-- Footer -->
        <v-modal-footer>
            <v-spinner v-if="isLoading">
                {{ 'backend.form.saving_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </v-spinner>
            <div v-else>
                <v-button @click="onSaveClicked">
                    <template v-if="context === 'create'">{{ 'backend.form.create' | trans(lang) }}</template>
                    <template v-else>{{ 'backend.form.save' | trans(lang) }}</template>
                </v-button>
            </div>
        </v-modal-footer>
    </v-modal>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                isLoading: false,
                option: {
                    id: null,
                    name: '',
                    placeholder: '',
                },
            };
        },
        computed: {
            context() {
                return this.option.id ? 'update' : 'create';
            },
        },
        methods: {
            create() {
                this.isLoading = true;

                axios.post(this.endpoints.createOption, { option: this.option })
                    .then(this.onCreateComplete)
                    .catch(this.onCreateFailed)
                    .then(() => this.isLoading = false);
            },
            hide() {
                this.$refs.modal.hide();
            },
            onCreateComplete(response) {
                this.hide();
                this.$emit('create', response.data);
            },
            onCreateFailed(error) {
                $.oc.flashMsg({ text: error.response.data, class: 'error' });
            },
            onSaveClicked() {
                if (this.context === 'create') {
                    this.create();
                } else {
                    this.update();
                }
            },
            show(option = {}) {
                this.option.id = option.id || null;
                this.option.name = option.name || '';
                this.option.placeholder = option.placeholder || '';

                this.$refs.modal.show();
            },
        },
        props: [
            'endpoints',
            'lang',
        ],
    };
</script>

<style lang="scss" scoped>@import 'core';
    .v-footer-buttons {
        display: flex;

        button {
            margin-left: 10px;
        }
    }
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
            <v-form-input v-model="option.name" @keypress.enter.prevent="onSave" required>
                {{ 'bedard.shop.options.form.name' | trans(lang) }}
            </v-form-input>
            <v-form-input v-model="option.placeholder" @keypress.enter.prevent="onSave">
                {{ 'bedard.shop.options.form.placeholder' | trans(lang ) }}
            </v-form-input>
            <v-values
                :lang="lang"
                :values="option.values"
                @add="onValueAdded"
                @input="onValueInput"
                @remove="onValueRemoved">
            </v-values>
        </v-modal-body>

        <!-- Footer -->
        <v-modal-footer>
            <v-spinner v-if="isLoading">
                {{ 'backend.form.saving_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </v-spinner>
            <div class="v-footer-buttons" v-else>
                <v-button @click="onSave" primary>
                    <template v-if="context === 'create'">{{ 'backend.form.create' | trans(lang) }}</template>
                    <template v-else>{{ 'backend.form.save' | trans(lang) }}</template>
                </v-button>
                <v-button @click="onCancel">
                    Cancel
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
                    values: [],
                },
            };
        },
        components: {
            'v-values': require('./values/values'),
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
            onCancel() {
                this.hide();
            },
            onCreateComplete(response) {
                this.hide();
                this.$emit('create', response.data);
            },
            onCreateFailed(error) {
                $.oc.flashMsg({ text: error.response.data, class: 'error' });
            },
            onSave() {
                if (this.context === 'create') {
                    this.create();
                } else {
                    this.update();
                }
            },
            onValueAdded(value) {
                this.option.values.push({
                    id: null,
                    name: value,
                    option_id: this.option.id,
                    sort_order: this.option.values.length,
                });
            },
            onValueInput(e, value) {
                value.name = e.target.value;
            },
            onValueRemoved(value) {
                this.option.values.splice(this.option.values.indexOf(value), 1);
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

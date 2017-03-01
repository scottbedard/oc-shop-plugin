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
            <v-form-input
                required
                ref="name"
                v-model="option.name"
                @keypress.enter.prevent="onSave">
                {{ 'bedard.shop.options.form.name' | trans(lang) }}
            </v-form-input>
            <v-form-input
                ref="placeholder"
                v-model="option.placeholder"
                @keydown.tab="onPlaceholderTab"
                @keypress.enter.prevent="onSave">
                {{ 'bedard.shop.options.form.placeholder' | trans(lang) }}
            </v-form-input>
            <v-values
                ref="values"
                :lang="lang"
                :values="option.values"
                @add="onValueAdded"
                @delete="onValueDeleted"
                @enter="onSave"
                @focus-placeholder="onFocusPlaceholder"
                @input="onValueInput"
                @remove="onValueRemoved"
                @reorder="onValuesReordered">
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
                    {{ 'backend.form.cancel' | trans(lang) }}
                </v-button>
            </div>
        </v-modal-footer>
    </v-modal>
</template>

<script>
    import axios from 'axios';
    import { clone, renameKey } from 'assets/js/utilities/helpers';

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

                axios.post(this.endpoints.createOption, { option: this.getPostData() })
                    .then(response => {
                        this.hide();
                        this.$emit('create', response.data);
                    })
                    .catch(this.onRequestFailed)
                    .then(this.onRequestComplete);
            },
            getPostData() {
                return renameKey(clone(this.option), 'values', 'value_data');
            },
            hide() {
                this.$refs.modal.hide();
            },
            onCancel() {
                this.hide();
            },
            onFocusPlaceholder() {
                this.$refs.placeholder.focus();
            },
            onPlaceholderTab(e) {
                if (! e.shiftKey) {
                    e.preventDefault();
                    this.$refs.values.focus();
                }
            },
            onRequestComplete() {
                this.isLoading = false;
            },
            onRequestFailed(error) {
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
                let { values } = this.option;

                this.option.values.push({
                    _deleted: false,
                    _key: Math.max(values.length, ...values.map(v => v._key)) + 1,
                    id: null,
                    name: value,
                    option_id: this.option.id,
                    sort_order: this.option.values.length,
                });
            },
            onValueDeleted(value) {
                value._deleted = ! value._deleted;
            },
            onValueInput(e, value) {
                value.name = e.target.value;
            },
            onValueRemoved(value) {
                this.option.values.splice(this.option.values.indexOf(value), 1);
            },
            onValuesReordered({ newIndex, oldIndex }) {
                const value = this.option.values.splice(oldIndex, 1)[0];

                this.option.values.splice(newIndex, 0, value);
                this.option.values.forEach((value, i) => value.sort_order = i);
            },
            show(option) {
                option = clone(option || {});
                this.option.id = option.id || null;
                this.option.name = option.name || '';
                this.option.placeholder = option.placeholder || '';
                this.option.values = option.values || [];

                this.option.values.forEach((value, i) => {
                    // attach a _deleted flag to each value
                    this.$set(value, '_deleted', value._deleted || false);

                    // in order to make values sortable, they each need a
                    // unique key so Vue is able to track their indexes
                    this.$set(value, '_key', i);
                });

                this.$refs.values.clearInput();
                this.$refs.modal.show();
                setTimeout(() => this.$refs.name.focus(), 250);
            },
            update() {
                this.isLoading = true;

                axios.post(this.endpoints.validateOption, { option: this.getPostData() })
                    .then(response => {
                        this.hide();
                        this.$emit('update', clone(this.option));
                    })
                    .catch(this.onRequestFailed)
                    .then(this.onRequestComplete);
            },
        },
        props: [
            'endpoints',
            'lang',
        ],
    };
</script>

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
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </template>
            <template v-if="context === 'update'">
                {{ 'backend.relation.update_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </template>
        </v-modal-header>

        <!-- Body -->
        <v-modal-body>
            <v-option-selector
                ref="optionSelector"
                :inventory="inventory"
                :options="options"
                @change="onValueChanged"
            />
            <v-form-input ref="sku" v-model="inventory.sku">
                {{ 'bedard.shop.inventories.form.sku' | trans(lang) }}
            </v-form-input>
            <v-form-input ref="price" v-model="inventory.quantity" required>
                {{ 'bedard.shop.inventories.form.quantity' | trans(lang) }}
            </v-form-input>
        </v-modal-body>

        <!-- Footer -->
        <v-modal-footer>
            <v-spinner v-if="isLoading">
                {{ 'backend.form.saving_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
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
    import trans from 'assets/js/filters/trans/trans';
    import { clone, renameKey } from 'assets/js/utilities/helpers';

    export default {
        data() {
            return {
                inventory: {
                    id: null,
                    quantity: 0,
                    sku: '',
                    value_ids: [],
                    values: [],
                },
                isLoading: false,
            };
        },
        components: {
            'v-option-selector': require('./option_selector/option_selector'),
        },
        computed: {
            context() {
                return this.inventory.id ? 'update' : 'create';
            },
        },
        methods: {
            create() {
                if (this.valuesAreNotUnique()) {
                    return;
                }

                this.isLoading = true;

                axios.post(this.endpoints.createInventory, { inventory: this.getFormData() })
                    .then(response => {
                        this.hide();
                        this.$emit('create', response.data);
                    })
                    .catch(this.onRequestFailed)
                    .then(this.onRequestComplete);
            },
            getFormData() {
                let data = clone(this.inventory);
                delete data.values;

                data.quantity = Number(data.quantity);

                return data;
            },
            getValues(inventory) {
                let values = [];
                inventory.value_ids.map(id => {
                    this.options.find(option => {
                        option.values.find(value => {
                            if (value.id === id) {
                                values.push(value);
                                return true;
                            }
                        });
                    });
                });

                return values;
            },
            getValueSignature(inventory) {
                return typeof inventory.value_ids !== 'undefined'
                    ? inventory.value_ids.sort().join('.')
                    : inventory.values.map(value => value.id).sort().join('.');
            },
            hide() {
                this.$refs.modal.hide();
            },
            onCancel() {
                this.hide();
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
            onValueChanged(id) {
                // if we've already got our value id, do nothing
                if (this.inventory.value_ids.indexOf(id) !== -1) {
                    return;
                }

                // remove the sibling value
                let option = this.options.find(option => option.values.find(value => value.id === id));

                option.values.map(value => value.id).forEach(siblingId => {
                    let siblingIndex = this.inventory.value_ids.indexOf(siblingId);

                    if (siblingIndex !== -1) {
                        this.inventory.value_ids.splice(siblingIndex, 1);
                    }
                });

                // add our new id to the array
                this.inventory.value_ids.push(id);
            },
            show(inventory = {}) {
                inventory = clone(inventory);
                this.inventory.id = inventory.id || null;
                this.inventory.quantity = inventory.quantity || 0;
                this.inventory.sku = inventory.sku || '';
                this.inventory.values = inventory.values || [];
                this.inventory.value_ids = this.inventory.values.length
                    ? inventory.values.map(value => value.id)
                    : [];

                this.$refs.modal.show();
                this.$refs.optionSelector.refresh();
            },
            update() {
                if (this.valuesAreNotUnique()) {
                    return;
                }

                this.isLoading = true;

                axios.post(this.endpoints.validateInventory, { inventory: this.getFormData() })
                    .then(response => {
                        let inventory = clone(this.inventory);
                        inventory.quantity = Number(inventory.quantity);
                        inventory.values = this.getValues(inventory);

                        this.hide();
                        this.$emit('update', inventory);
                    })
                    .catch(this.onRequestFailed)
                    .then(this.onRequestComplete);
            },
            valuesAreNotUnique() {
                let valueSignature = this.getValueSignature(this.inventory);

                let otherInventories = this.inventories.filter(inventory => {
                    return inventory.id !== this.inventory.id
                        && ! inventory._deleted;
                });

                let isCollision = otherInventories.find(inventory => {
                    return this.getValueSignature(inventory) === valueSignature;
                });

                if (isCollision) {
                    let text = this.inventory.value_ids.length
                        ? trans('bedard.shop.inventories.form.collision_values', this.lang)
                        : trans('bedard.shop.inventories.form.collision_default', this.lang);

                    $.oc.flashMsg({ text, class: 'error' });
                }

                return isCollision;
            },
        },
        props: [
            'endpoints',
            'inventories',
            'lang',
            'options',
        ],
    };
</script>

<style lang="scss" scoped>@import 'assets/scss/utils';
    .modal-footer {
        align-items: center;
        display: flex;
        justify-content: flex-end;

        > .actions {
            min-height: 40px;
        }
    }
</style>

<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
            <div class="grid padded">
                <div class="row" v-for="option in options">
                    <div class="cell mobile-12">
                        <v-dropdown-field
                            :channel="channel"
                            :label="option.name"
                            :placeholder="getPlaceholder(option.name)"
                            :values="option.values"
                            @change="onOptionChanged">
                        </v-dropdown-field>
                    </div>
                </div>
                <div class="row">
                    <div class="cell mobile-12 tablet-6">
                        <v-input-field
                            type="number"
                            v-model="inventory.quantity"
                            :label="lang.inventories.form.quantity"
                            :required="true">
                        </v-input-field>
                    </div>
                    <div class="cell mobile-12 tablet-6">
                        <v-input-field
                            v-model="inventory.sku"
                            :label="lang.inventories.form.sku">
                        </v-input-field>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <v-loader v-if="isLoading">
                {{ lang.inventories.form.loading_message }}
            </v-loader>
            <div v-else class="actions">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{ lang.form.cancel }}
                </button>
                <button type="button" class="btn btn-primary" @click.prevent="onCreateClicked">
                    {{ createOrSave }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import EventChannel from '../_channel';

    export default {
        created() {
            EventChannel.$on('inventory:opened', () => setTimeout(this.onOpened, 300));
        },
        data() {
            return {
                isLoading: false,
                inventory: {
                    id: null,
                    newId: null,
                    product_id: null,
                    quantity: 0,
                    sku: '',
                    valueIds: [],
                },
            };
        },
        computed: {
            channel() {
                return EventChannel;
            },
            context() {
                return typeof this.sourceModel.id === 'undefined'
                    ? 'create'
                    : 'update';
            },
            createOrSave() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.form.save;
            },
            title() {
                return this.context === 'create'
                    ? this.lang.inventories.form.create_title
                    : this.lang.inventories.form.update_title
            },
        },
        methods: {
            getPlaceholder(name) {
                let langString = this.lang.inventories.form.option_placeholder;

                return langString.replace(':name', name.trim().toLowerCase());
            },
            onCreateClicked() {
                this.validate()
                    .then(response => {
                        this.$emit('save', this.inventory);
                        $(this.$el).closest('.control-popup').modal('hide');
                    })
                    .catch(error => $.oc.flashMsg({ text: error.body, class: 'error' }))
                    .then(() => this.isLoading = false);
            },
            onOpened() {
                this.channel.$emit('dropdown:reset');
                this.setSelectedOptionValues();
            },
            onOptionChanged(value, oldValue) {
                if (oldValue && typeof oldValue.id !== 'undefined') {
                    this.inventory.valueIds = this.inventory.valueIds.filter(id => id != oldValue.id);
                }

                if (value && typeof value.id !== 'undefined') {
                    this.inventory.valueIds.push(value.id);
                }
            },
            onSourceModelChanged() {
                this.inventory = JSON.parse(JSON.stringify(this.sourceModel));
            },
            setSelectedOptionValues() {
                for (let valueId of this.inventory.valueIds) {
                    for (let option of this.options) {
                        let foundValue = option.values.find(value => value.id == valueId);
                        if (foundValue) {
                            EventChannel.$emit('dropdown:set', foundValue);
                        }
                    }
                }
            },
            validate() {
                this.isLoading = true;
                return this.$http.post(this.validationEndpoint, { inventory: this.inventory })
            },
        },
        props: [
            'inventories',
            'lang',
            'inventories',
            'options',
            'sourceModel',
            'validationEndpoint',
        ],
        watch: {
            'sourceModel': 'onSourceModelChanged',
        },
    };
</script>

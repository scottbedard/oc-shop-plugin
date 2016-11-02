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
    export default {
        data() {
            return {
                isLoading: false,
                inventory: {
                    id: null,
                    product_id: null,
                    quantity: 0,
                    sku: '',
                },
            };
        },
        computed: {
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
                        console.log (response)
                    })
                    .catch(error => $.oc.flashMsg({ text: error.body, class: 'error' }))
                    .then(() => this.isLoading = false);
            },
            onOptionChanged(value) {
                // update value
            },
            validate() {
                this.isLoading = true;
                console.log (this.inventory);
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
    };
</script>

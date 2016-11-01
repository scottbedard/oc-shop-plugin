<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
            <div class="grid padded">
                <div class="row">
                    <div class="cell mobile-12">
                        <v-dropdown-field label="Dropdown options"></v-dropdown-field>
                    </div>
                </div>
                <div class="row">
                    <div class="cell mobile-12 tablet-6">
                        <v-input-field
                            v-model="inventory.price"
                            :label="lang.inventories.form.price"
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
            <button type="button" class="btn btn-default" data-dismiss="modal">
                {{ lang.form.cancel }}
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                {{ createOrSave }}
            </button>
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
                    price: 0,
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
        props: [
            'inventories',
            'lang',
            'inventories',
            'sourceModel',
        ],
    };
</script>

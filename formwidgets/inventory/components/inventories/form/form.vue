<template>
    <v-modal :visible="isVisible" @close="close">
        <form @submit.prevent="save">
            <!-- header -->
            <v-modal-header @close="close">
                {{ title | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-modal-header>

            <!-- body -->
            <v-modal-body>

                <!-- sku -->
                <v-form-input
                    v-model="sku"
                    data-input="sku"
                    ref="sku">
                    {{ 'bedard.shop.inventories.form.sku' | trans(lang) }}
                </v-form-input>

                <!-- quantity -->
                <v-form-input
                    v-model.number="quantity"
                    data-input="quantity"
                    min="0"
                    placeholder="0"
                    required>
                    {{ 'bedard.shop.inventories.form.quantity' | trans(lang) }}
                </v-form-input>

                <!-- option selector -->
                <v-option-selector />

                <pre>{{ $store.state.inventories.inventoryForm.data }}</pre>
            </v-modal-body>

            <!-- footer -->
            <v-modal-footer>
                <v-button data-action="confirm" primary type="submit">
                    {{ createOrSave | trans(lang) }}
                </v-button>
                <v-button data-action="cancel" type="button" @click="close">
                    {{ 'backend.form.cancel' | trans(lang) }}
                </v-button>
            </v-modal-footer>
        </form>
    </v-modal>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import { mapTwoWayState } from 'spyfu-vuex-helpers';

    export default {
        components: {
            'v-option-selector': require('./option_selector/option_selector').default,
        },
        computed: {
            ...mapState('inventories', {
                context: state => state.inventoryForm.context,
                isSaving: state => state.inventoryForm.isSaving,
                isVisible: state => state.inventoryForm.isVisible,
                lang: state => state.lang,
            }),
            ...mapTwoWayState('inventories', {
                'inventoryForm.data.sku': 'setInventoryFormSku',
                'inventoryForm.data.quantity': 'setInventoryFormQuantity',
            }),
            createOrSave() {
                return this.context === 'create'
                    ? 'backend.form.create'
                    : 'backend.form.save';
            },
            title() {
                return this.context === 'create'
                    ? 'backend.relation.create_name'
                    : 'backend.relation.update_name';
            },
        },
        methods: {
            ...mapActions('inventories', {
                close: 'hideInventoryForm',
                save: 'saveInventory',
            }),
            focusSku() {
                this.$refs.sku.focus();
            },
        },
        watch: {
            isVisible(isVisible) {
                if (isVisible) {
                    setTimeout(this.focusSku, 100);
                }
            },
        },
    };
</script>

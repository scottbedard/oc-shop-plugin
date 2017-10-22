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
            </v-modal-body>

            <!-- footer -->
            <v-modal-footer>
                <transition name="fade" mode="out-in">
                    <v-spinner v-if="isLoading" key="loading">
                        {{ 'backend.form.saving' | trans(lang) }}
                    </v-spinner>
                    <div v-else key="actions">
                        <v-button data-action="confirm" primary type="submit">
                            {{ createOrSave | trans(lang) }}
                        </v-button>
                        <v-button data-action="cancel" type="button" @click="close">
                            {{ 'backend.form.cancel' | trans(lang) }}
                        </v-button>
                    </div>
                </transition>
            </v-modal-footer>
        </form>
    </v-modal>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import { mapTwoWayState } from 'spyfu-vuex-helpers';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        components: {
            'v-option-selector': require('./option_selector/option_selector').default,
        },
        computed: {
            ...mapState('inventories', {
                context: state => state.inventoryForm.context,
                isLoading: state => state.inventoryForm.isLoading,
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
            }),
            focusSku() {
                this.$refs.sku.focus();
            },
            save() {
                // save the inventory, and flash an error if anything goes wrong
                this.$store.dispatch('inventories/saveInventory').catch((err) => {
                    this.$flashError(trans(err, this.lang));
                });
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

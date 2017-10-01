<template>
    <v-modal :visible="isVisible" @close="close">
        <form @submit="save">
            <!-- header -->
            <v-modal-header @close="close">
                {{ title | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-modal-header>

            <!-- body -->
            <v-modal-body>
                Hello from the body
            </v-modal-body>

            <!-- footer -->
            <v-modal-footer>
                <transition name="fade" mode="out-in">
                    <!-- loading state -->
                    <v-spinner v-if="isSaving">
                        {{ creatingOrSaving | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
                    </v-spinner>

                    <!-- actions -->
                    <div v-else key="actions">
                        <v-button primary type="submit">
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

    export default {
        computed: {
            ...mapState('inventories', {
                context: state => state.inventoryForm.context,
                isSaving: state => state.inventoryForm.isSaving,
                isVisible: state => state.inventoryForm.isVisible,
                lang: state => state.lang,
            }),
            creatingOrSaving() {
                return this.context === 'create'
                    ? 'backend.form.creating_name'
                    : 'backend.form.saving_name';
            },
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
                save: 'saveInventoryForm',
            }),
        },
    };
</script>

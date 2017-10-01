<template>
    <v-modal :visible="isVisible" @close="close">
        <form @submit.prevent="confirm">
            <!-- header -->
            <v-modal-header @close="close">
                {{ title | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-modal-header>

            <!-- body -->
            <v-modal-body>
                <!-- name -->
                <v-form-input v-model="name" data-input="name" required>
                    {{ 'bedard.shop.options.form.name' | trans(lang) }}
                </v-form-input>

                <!-- placeholder -->
                <v-form-input v-model="placeholder" data-input="placeholder">
                    {{ 'bedard.shop.options.form.placeholder' | trans(lang) }}
                </v-form-input>

                <!-- option values -->
                <v-values />
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

    export default {
        components: {
            'v-values': require('./values/values').default,
        },
        computed: {
            ...mapState('inventories', {
                context: state => state.optionForm.context,
                isSaving: state => state.optionForm.isSaving,
                isVisible: state => state.optionForm.isVisible,
                lang: state => state.lang,
            }),
            ...mapTwoWayState('inventories', {
                name: 'setOptionFormName',
                placeholder: 'setOptionFormPlaceholder',
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
                close: 'hideOptionForm',
            }),
            confirm() {
                if (this.context === 'create') {
                    this.$store.dispatch('inventories/createOption');
                } else {
                    this.$store.dispatch('inventories/updateOption');
                }
            },
        },
    };
</script>

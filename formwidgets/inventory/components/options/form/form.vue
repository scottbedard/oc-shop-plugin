<template>
    <v-modal :visible="isVisible" @close="close">
        <form @submit.prevent="save">
            <!-- header -->
            <v-modal-header @close="close">
                {{ title | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-modal-header>

            <!-- body -->
            <v-modal-body>

                <!-- delete message -->
                <div v-if="isDeleted" class="callout fade in callout-warning no-subheader">
                    <div class="header">
                        <i class="icon-exclamation-triangle"></i>
                        <p>{{ 'bedard.shop.options.form.delete_warning' | trans(lang) }}</p>
                    </div>
                </div>

                <!-- name -->
                <v-form-input
                    v-model="name"
                    data-input="name"
                    ref="name"
                    required>
                    {{ 'bedard.shop.options.form.name' | trans(lang) }}
                </v-form-input>

                <!-- placeholder -->
                <v-form-input
                    v-model="placeholder"
                    data-input="placeholder"
                    ref="placeholder">
                    {{ 'bedard.shop.options.form.placeholder' | trans(lang) }}
                </v-form-input>

                <!-- option values -->
                <v-values @focus-placeholder="focusPlaceholder" />
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
            'v-values': require('./values/values').default,
        },
        computed: {
            ...mapState('inventories', {
                context: state => state.optionForm.context,
                isDeleted: state => state.optionForm.data._delete,
                isSaving: state => state.optionForm.isSaving,
                isVisible: state => state.optionForm.isVisible,
                lang: state => state.lang,
            }),
            ...mapTwoWayState('inventories', {
                'optionForm.data.name': 'setOptionFormName',
                'optionForm.data.placeholder': 'setOptionFormPlaceholder',
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
                close: 'hideOptionForm',
                save: 'saveOption',
            }),
            focusName() {
                this.$refs.name.focus();
            },
            focusPlaceholder() {
                this.$refs.placeholder.focus();
            },
        },
        watch: {
            isVisible(isVisible) {
                if (isVisible) {
                    setTimeout(this.focusName, 100);
                }
            },
        },
    };
</script>

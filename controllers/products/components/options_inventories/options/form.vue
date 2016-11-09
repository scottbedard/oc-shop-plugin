<template>
    <div>
        <!-- Header -->
        <v-popup-header>{{ title }}</v-popup-header>

        <!-- Body -->
        <v-popup-body>
            <v-input-field
                :label="lang.options.form.name"
                :prevent-submit="true"
                v-model="option.name">
            </v-input-field>
            <v-input-field
                :label="lang.options.form.placeholder"
                :prevent-submit="true"
                v-model="option.placeholder">
            </v-input-field>
            <v-value-fields
                :label="lang.options.form.values"
                :lang="lang"
                :values="option.values"
                @add="onValueAdded"
                @delete="onValueDeleted"
                @reorder="onValuesReordered">
            </v-value-fields>
        </v-popup-body>

        <!-- Footer -->
        <v-popup-footer>
            <v-loader v-if="isLoading">
                {{ lang.options.form.loading_message }}
            </v-loader>
            <div v-else>
                <v-button class="btn-default" data-dismiss="modal">{{ lang.form.cancel }}</v-button>
                <v-button class="btn-primary" @click="onSaveClicked">{{ createOrUpdate }}</v-button>
            </div>
        </v-popup-footer>
    </div>
</template>

<script>
    import CreateOption from './factory';
    import ValueFieldsComponent from './_value_fields';

    export default {
        data() {
            return {
                isLoading: false,
                option: CreateOption(),
            };
        },
        components: {
            'v-value-fields': ValueFieldsComponent,
        },
        computed: {
            context() {
                return this.option.id === null
                    ? 'create'
                    : 'update';
            },
            createOrUpdate() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.relation.update;
            },
            title() {
                return this.lang.options.form[this.context + '_title'];
            },
        },
        methods: {
            focus() {
                $(this.$el).find('input').first().focus();
            },
            onSaveClicked() {
                this.isLoading = true;
                this.validate(this.option)
                    .then(response => this.$emit('save', this.option))
                    .catch(error => $.oc.flashMsg({ text: error.body, class: 'error' }))
                    .then(() => this.isLoading = false)
            },
            onSourceModelChanged(model) {
                this.option = JSON.parse(JSON.stringify(model));
            },
            onValueAdded(value) {
                this.option.values.push(value);
            },
            onValueDeleted(value) {
                this.option.values.splice(this.option.values.indexOf(value), 1);
            },
            onValuesReordered({ newIndex, oldIndex }) {
                this.option.values.splice(newIndex, 0, this.option.values.splice(oldIndex, 1)[0]);
            },
            validate(option) {
                return this.$http.post(this.validationEndpoint, { option })
            },
        },
        props: [
            'lang',
            'sourceModel',
            'validationEndpoint',
        ],
        watch: {
            sourceModel: 'onSourceModelChanged',
        }
    };
</script>

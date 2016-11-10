<template>
    <div>
        <!-- Header -->
        <v-popup-header>{{ title }}</v-popup-header>

        <!-- Body -->
        <v-popup-body>
            <v-dropdown-field
                display="name"
                v-for="option in options"
                :empty-message="lang.ui.dropdown.no_results"
                :label="option.name"
                :options="option.values"
                :placeholder="getPlaceholder(option)"
                :value="inventory.values[option.id] || null"
                @change="onOptionChanged">
            </v-dropdown-field>
            <v-input-field
                span="left"
                v-model="inventory.quantity"
                :label="lang.inventories.form.quantity"
                :prevent-submit="true">
            </v-input-field>
            <v-input-field
                span="right"
                v-model="inventory.sku"
                :label="lang.inventories.form.sku"
                :prevent-submit="true">
            </v-input-field>
        </v-popup-body>

        <!-- Footer -->
        <v-popup-footer>
            <v-loader v-if="isLoading">
                {{ lang.inventories.form.loading_message }}
            </v-loader>
            <div v-else>
                <v-button class="btn-default" data-dismiss="modal">{{ lang.form.cancel }}</v-button>
                <v-button class="btn-primary" @click="onSaveClicked">{{ createOrUpdate }}</v-button>
            </div>
        </v-popup-footer>
    </div>
</template>

<script>
    import Factory from '../factory';

    export default {
        data() {
            return {
                isLoading: false,
                inventory: Factory.inventory(),
            };
        },
        computed: {
            context() {
                return this.inventory.id === null
                    ? 'create'
                    : 'update';
            },
            createOrUpdate() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.relation.update;
            },
            title() {
                return this.lang.inventories.form[this.context + '_title'];
            },
        },
        methods: {
            focus() {
                console.log ('focusing...');
            },
            getPlaceholder(option) {
                let langString = this.lang.inventories.form.option_placeholder;
                
                return langString.replace(':name', option.name.toLowerCase().trim());
            },
            onOptionChanged(model, collection) {
                let option = this.options.find(o => o.values === collection);
                this.inventory.values[option.id] = model;
                this.$forceUpdate();
            },
            onSaveClicked() {

            },
        },
        props: [
            'lang',
            'options',
            'sourceModel',
            'validationEndpoint',
        ],
    };
</script>

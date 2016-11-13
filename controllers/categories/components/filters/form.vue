<template>
    <div>
        <v-popup-header>{{ title }}</v-popup-header>
        <v-popup-body>
            <v-dropdown-field
                display="name"
                :label="lang.filters.form.left"
                :options="leftValues"
                :value="filter.left"
                @change="onLeftChanged">
            </v-dropdown-field>
            <v-dropdown-field
                display="name"
                :label="lang.filters.form.comparator"
                :options="comparatorValues"
                :value="filter.comparator"
                @change="onComparatorChanged">
            </v-dropdown-field>
        </v-popup-body>
        <v-popup-footer>
            <v-loader v-if="isLoading">
                {{ lang.filters.form.loading_message }}
            </v-loader>
            <div v-else>
                <v-button class="btn-default" data-dismiss="modal">{{ lang.form.cancel }}</v-button>
                <v-button class="btn-primary" @click="onSaveClicked">{{ createOrUpdate }}</v-button>
            </div>
        </v-popup-footer>
    </div>
</template>

<script>
    import Filter from './factory';

    export default {
        data() {
            return {
                filter: Filter(),
                isLoading: false,
            };
        },
        computed: {
            context() {
                return this.filter.id === null ? 'create' : 'update';
            },
            createOrUpdate() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.relation.form.update;
            },
            comparatorValues() {
                return [
                    { value: '=', name: this.lang.filters.form.comparator_equal_to },
                    { value: '<>', name: this.lang.filters.form.comparator_not_equal_to },
                    { value: '<', name: this.lang.filters.form.comparator_less_than },
                    { value: '<=', name: this.lang.filters.form.comparator_less_than_or_equal },
                    { value: '>', name: this.lang.filters.form.comparator_greater_than },
                    { value: '>=', name: this.lang.filters.form.comparator_greater_than_or_equal },
                ];
            },
            leftValues() {
                return [
                    { value: 'price', name: this.lang.filters.form.left_actual_price },
                    { value: 'base_price', name: this.lang.filters.form.left_base_price },
                    { value: 'created_at', name: this.lang.filters.form.left_created_at },
                    { value: 'updated_at', name: this.lang.filters.form.left_updated_at },
                ];
            },
            title() {
                return this.context === 'create'
                    ? this.lang.filters.form.create
                    : this.lang.filters.form.update;
            },
        },
        methods: {
            onComparatorChanged(comparator) {
                this.filter.comparator = comparator;
            },
            onLeftChanged(left) {
                this.filter.left = left;
            },
            onSaveClicked() {
                console.log ('Saving');
            },
            onSourceModelChanged(filter) {
                this.filter = JSON.parse(JSON.stringify(filter));
            },
        },
        props: [
            'lang',
            'sourceModel',
        ],
        watch: {
            sourceModel: 'onSourceModelChanged',
        },
    };
</script>

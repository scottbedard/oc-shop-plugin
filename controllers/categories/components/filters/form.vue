<template>
    <div>
        <v-popup-header>{{ title }}</v-popup-header>
        <v-popup-body>
            <v-dropdown-field
                display="name"
                :label="lang.filters.form.left"
                :options="leftValues"
                :required="true"
                :value="filter.left"
                @change="onLeftChanged">
            </v-dropdown-field>
            <v-dropdown-field
                display="name"
                :label="lang.filters.form.comparator"
                :options="comparatorValues"
                :required="true"
                :value="filter.comparator"
                @change="onComparatorChanged">
            </v-dropdown-field>
            <div class="form-group">
                <v-dropdown-field
                    display="name"
                    v-show="isLeftPrice && filter.left"
                    :class="{ 'span-left': isCustom }"
                    :label="lang.filters.form.right"
                    :options="rightValues"
                    :required="true"
                    :value="filter.right"
                    @change="onRightChanged">
                </v-dropdown-field>
                <v-input-field
                    ref="value"
                    type="number"
                    v-show="filter.left && (! isLeftPrice || isCustom)"
                    v-model="filter.value"
                    :class="{ 'span-right': isLeftPrice }"
                    :label="valueLabel"
                    :prevent-submit="true"
                    :required="true">
                </v-input-field>
            </div>
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
            isCustom() {
                return this.filter.right && this.filter.right.value === 'custom';
            },
            isLeftPrice() {
                return this.filter.left !== null && (
                    this.filter.left.value === 'base_price' ||
                    this.filter.left.value === 'price'
                );
            },
            leftValues() {
                return [
                    { value: 'price', name: this.lang.filters.form.actual_price },
                    { value: 'base_price', name: this.lang.filters.form.base_price },
                    { value: 'created_at', name: this.lang.filters.form.left_created_at },
                    { value: 'updated_at', name: this.lang.filters.form.left_updated_at },
                ];
            },
            rightValues() {
                return [
                    { value: 'price', name: this.lang.filters.form.actual_price },
                    { value: 'base_price', name: this.lang.filters.form.base_price },
                    { value: 'custom', name: this.lang.filters.form.right_custom },
                ];
            },
            title() {
                return this.context === 'create'
                    ? this.lang.filters.form.create
                    : this.lang.filters.form.update;
            },
            valueLabel() {
                return this.isLeftPrice
                    ? this.lang.filters.form.right_custom
                    : this.lang.filters.form.right_days_ago;
            },
        },
        methods: {
            onComparatorChanged(comparator) {
                this.filter.comparator = comparator;
            },
            onLeftChanged(left) {
                this.filter.left = left;

                if (left.value === 'created_at' || left.value === 'updated_at') {
                    this.filter.right = this.rightValues.find(option => option.value === 'custom');
                }
            },
            onRightChanged(right) {
                this.filter.right = right;

                if (right.value === 'custom') {
                    this.$nextTick(() => this.$refs.value.focus());
                }
            },
            onSaveClicked() {
                this.isLoading = true;

                this.validate(this.filter)
                    .then(response => this.$emit('save', this.filter))
                    .catch(error => $.oc.flashMsg({ text: error.body, class: 'error' }))
                    .then(() => this.isLoading = false);
            },
            onSourceModelChanged(filter) {
                this.filter = JSON.parse(JSON.stringify(filter));
            },
            validate(filter) {
                return this.$http.post(this.filterValidation, { filter })
            },
        },
        props: [
            'filterValidation',
            'lang',
            'sourceModel',
        ],
        watch: {
            sourceModel: 'onSourceModelChanged',
        },
    };
</script>

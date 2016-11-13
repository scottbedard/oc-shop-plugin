<template>
    <div>
        <v-relation-list>
            <ul v-sortable="{
                animation: 150,
                handle: '.oc-icon-bars',
                onUpdate: onFiltersReordered,
            }">
                <li
                    v-for="filter in filters"
                    :class="{ 'is-deleted': filter.is_deleted }"
                    :key="filter.id || filter.newId"
                    @click="onFilterClicked(filter)">
                    <a href="#" @click.prevent class="oc-icon-filter"></a>
                    <div class="item">{{ getFilterString(filter) }}</div>
                    <a href="#" @click.prevent.stop="onDeleteClicked(filter)" class="oc-icon-trash-o"></a>
                </li>
            </ul>
        </v-relation-list>

        <v-relation-list-create @click="onCreateClicked">
            {{ lang.categories.form.create_filter }}
        </v-relation-list-create>

        <v-popup ref="filterPopup">
            <v-filters-form
                ref="filtersForm"
                :filter-validation="filterValidation"
                :source-model="filter"
                :lang="lang"
                @save="onFilterSaved">
            </v-filters-form>
        </v-popup>

        <input type="hidden" :value="filtersJson" name="categoryFilters">
    </div>
</template>

<script>
    import Filter from './factory';
    import FiltersFormComponent from './form';
    import Names from './names';

    export default {
        data() {
            return {
                filter: Filter(),
                filters: this.filtersProp.map(filter => {
                    let comparatorName = this.lang.filters.form[Names.comparator[filter.comparator]];
                    let leftName = this.lang.filters.form[Names.left[filter.left]];
                    let rightName = this.lang.filters.form[Names.right[filter.right]];

                    filter.comparator = { value: filter.comparator, name: comparatorName };
                    filter.left = { value: filter.left, name: leftName };
                    filter.right = { value: filter.right, name: rightName };
                    filter.is_deleted = false;

                    return JSON.parse(JSON.stringify(filter));
                }),
            };
        },
        components: {
            'v-filters-form': FiltersFormComponent,
        },
        computed: {
            filtersJson() {
                let filters = JSON.parse(JSON.stringify(this.filters));

                return JSON.stringify(filters.map(filter => {
                    filter.comparator = filter.comparator.value || null;
                    filter.left = filter.left.value || null;
                    filter.right = filter.right.value || null;
                    delete filter.newId;
                    return filter;
                }));
            },
        },
        methods: {
            getFilterString(filter) {
                let leftString = filter.left.name;

                let comparatorString = filter.comparator.name.toLowerCase();

                let rightString = filter.right.value !== 'custom'
                    ? filter.right.name.toLowerCase()
                    : filter.value;

                if (filter.left.value === 'created_at' || filter.left.value === 'updated_at') {
                    rightString = filter.value === 1
                        ? this.lang.filters.form.right_n_days_ago_singular.replace(':n', filter.value)
                        : this.lang.filters.form.right_n_days_ago_plural.replace(':n', filter.value);
                }

                return `${ leftString } ${ comparatorString } ${ rightString }`;
            },
            onCreateClicked() {
                this.filter = Filter();
                this.$refs.filterPopup.show();
            },
            onDeleteClicked(filter) {
                filter.is_deleted = ! filter.is_deleted;
            },
            onFilterClicked(filter) {
                this.filter = filter;
                this.$refs.filterPopup.show();
            },
            onFilterSaved(filter) {
                let existingFilter = this.filters.find(model => {
                    return (filter.id && filter.id === model.id)
                        || (! filter.id && filter.newId === model.newId);
                });

                if (existingFilter) {
                    this.filters.splice(this.filters.indexOf(existingFilter), 1, filter);
                } else {
                    this.filters.push(filter);
                }

                this.$refs.filterPopup.dismiss();
            },
            onFiltersReordered() {

            },
        },
        props: [
            'filters-prop',
            'filterValidation',
            'lang',
        ],
    };
</script>

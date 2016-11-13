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
                    <a href="#" @click.prevent class="oc-icon-trash-o"></a>
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
    </div>
</template>

<script>
    import Filter from './factory';
    import FiltersFormComponent from './form';

    export default {
        data() {
            return {
                filter: Filter(),
                filters: this.filtersProp.slice(0),
            };
        },
        components: {
            'v-filters-form': FiltersFormComponent,
        },
        methods: {
            getFilterString(filter) {
                let rightString = filter.right.value !== 'custom'
                    ? filter.right.name.toLowerCase()
                    : filter.value;

                return `${ filter.left.name.toLowerCase() } ${ filter.comparator.name.toLowerCase() } ${ rightString }`;
            },
            onCreateClicked() {
                this.filter = Filter();
                this.$refs.filterPopup.show();
            },
            onFilterClicked(filter) {
                console.log ('opening', filter);
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

<template>
    <div>
        <v-relation-list-create @click="onCreateClicked">
            {{ lang.categories.form.create_filter }}
        </v-relation-list-create>

        <v-popup ref="filtersPopup">
            <v-filters-form
                ref="filtersForm"
                :source-model="filter"
                :lang="lang">
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
            };
        },
        components: {
            'v-filters-form': FiltersFormComponent,
        },
        methods: {
            onCreateClicked() {
                this.filter = Filter();
                this.$refs.filtersPopup.show();
            },
        },
        props: [
            'lang',
        ],
    };
</script>

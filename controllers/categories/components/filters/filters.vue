<template>
    <div>
        <v-relation-list-create @click="onCreateClicked">
            {{ lang.categories.form.create_filter }}
        </v-relation-list-create>

        <v-popup ref="filtersPopup">
            <v-filters-list
                ref="filtersList"
                :lang="lang">
            </v-filters-list>
        </v-popup>
    </div>
</template>

<script>
    import FiltersListComponent from './list';

    export default {
        components: {
            'v-filters-list': FiltersListComponent,
        },
        methods: {
            onCreateClicked() {
                this.$refs.filtersPopup.show();
            },
        },
        props: [
            'lang',
        ],
    };
</script>

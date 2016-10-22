<template>
    <li>
        {{ category.name }}
        <v-reorder-list @sort="onSort">
            <v-reorder-item
                v-for="category in childCategories"
                :category="category"
                :categories="categories"
                @sort="onSort">
            </v-reorder-item>
            <li></li>
        </v-reorder-list>
    </li>
</template>

<script>
    import ReorderListComponent from './reorder_list';

    export default {
        components: {
            'v-reorder-list': ReorderListComponent,
        },
        computed: {
            childCategories() {
                return this.categories.filter(category => category.parent_id === this.category.id);
            },
        },
        methods: {
            onSort() {
                this.$emit("sort")
            },
        },
        name: 'v-reorder-item',
        props: [
            'category',
            'categories',
        ],
    };
</script>

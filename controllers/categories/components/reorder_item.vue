<style lang="scss" scoped>
    a {
        cursor: move;
        color: #aaa;
    }

    ol {
        margin-left: 5px !important;
        padding-left: 18px !important;
    }

    li {
        line-height: 32px;
    }
</style>

<template>
    <li>
        <a href="#" @click.prevent class="move oc-icon-bars"></a>
        <span>{{ category.name }}</span>
        <ol>
            <v-reorder-item
                v-for="category in childCategories"
                :category="category"
                :categories="categories">
            </v-reorder-item>
        </ol>
    </li>
</template>

<script>
    export default {
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

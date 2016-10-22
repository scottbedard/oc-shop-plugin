<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ lang['bedard.shop::lang.categories.list']['reorder_button'] }}</h4>
        </div>
        <div class="modal-body">
            <v-reorder-list>
                <v-reorder-item
                    v-for="category in rootCategories"
                    :category="category"
                    :categories="categories"
                    @sort="onListChanged">
                </v-reorder-item>
            </v-reorder-list>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import ReorderItemComponent from './reorder_item';
    import ReorderListComponent from './reorder_list';
    import Sortable from 'vue-sortable';
    Vue.use(Sortable);

    export default {
        components: {
            'v-reorder-item': ReorderItemComponent,
            'v-reorder-list': ReorderListComponent,
        },
        computed: {
            rootCategories() {
                return this.categories.filter(category => category.parent_id === null);
            },
        },
        methods: {
            onListChanged() {
                console.log ('oon list changek');
            },
        },
        props: [
            'categories',
            'lang',
        ],
    }
</script>

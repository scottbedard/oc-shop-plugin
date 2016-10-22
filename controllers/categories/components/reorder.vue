<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ lang.categories.list.reorder_button }}</h4>
        </div>
        <div class="modal-body">
            <div
                class="control-treelist"
                data-control="treelist"
                data-handle="a">
                <ol>
                    <v-reorder-item
                        v-for="category in rootCategories"
                        :category="category"
                        :categories="categories">
                    </v-reorder-item>
                </ol>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                {{ lang.form.cancel }}
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                {{ lang.form.apply }}
            </button>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import ReorderItemComponent from './reorder_item';

    export default {
        components: {
            'v-reorder-item': ReorderItemComponent,
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

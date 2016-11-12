<style lang="scss" scoped>
    .modal-footer {
        color: #999;
        display: flex;
        justify-content: flex-end;
    }
</style>

<template>
    <div>
        <v-popup-header>{{ lang.categories.list.reorder_button }}</v-popup-header>
        <v-popup-body>
            <div
                class="control-treelist"
                data-control="treelist"
                data-handle="a"
                v-if="categories.length">
                <ol ref="root" data-parent-id="null">
                    <v-reorder-item
                        v-for="category in rootCategories"
                        :category="category"
                        :categories="categories">
                    </v-reorder-item>
                </ol>
            </div>
            <p v-else>
                {{ lang.categories.list.reorder_empty }}
            </p>
        </v-popup-body>
        <v-popup-footer>
            <v-loader v-if="isLoading">
                {{ lang.form.saving }}
            </v-loader>
            <div v-else>
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal">
                    {{ lang.form.cancel }}
                </button>
                <button
                    v-if="categories.length"
                    type="button"
                    class="btn btn-primary"
                    @click="onApplyClicked">
                    {{ lang.form.apply }}
                </button>
            </div>
        </v-popup-footer>
    </div>
</template>

<script>
    import Vue from 'vue';
    import ReorderItemComponent from './reorder_item';

    export default {
        data() {
            return {
                isLoading: false,
            };
        },
        components: {
            'v-reorder-item': ReorderItemComponent,
        },
        computed: {
            rootCategories() {
                return this.categories.filter(category => category.parent_id === null);
            },
        },
        methods: {
            closePopup() {
                this.$destroy();
                $(this.$el).closest('.modal').trigger('close.oc.popup');
            },
            getTree() {
                let tree = [];
                $(this.$el).find('li').each((i, el) => {
                    tree.push({
                        id: $(el).data('id'),
                        parent_id: $(el).closest('ol').data('parent-id'),
                        sort_order: i,
                    });
                });

                return tree;
            },
            onApplyClicked() {
                this.isLoading = true;

                this.$http.post(this.endpoint, { categories: this.getTree() })
                    .then(this.onSuccess)
                    .catch(this.onFailure)
            },
            onFailure(response) {
                $.oc.flashMsg({ text: response.body, class: 'error' });
                this.isLoading = false;
            },
            onSuccess(response) {
                $.oc.flashMsg({ text: response.body, class: 'success' });
                this.closePopup();
            },
        },
        props: [
            'categories',
            'endpoint',
            'lang',
        ],
    }
</script>

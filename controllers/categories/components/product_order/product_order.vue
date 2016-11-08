<style lang="scss" scoped>@import 'assets/scss/utils';
    ul {
        display: flex;
        flex-wrap: wrap;
        margin: -10px;
        padding: 0;
    }

    li {
        cursor: move;
        flex-basis: auto;
        list-style: none;
        padding: 10px;

        > div {
            border: 1px dashed #ccc;
            border-radius: 3px;
            padding: 10px;
            text-align: center;
        }
    }

    .sortable-ghost > div {
        opacity: 0;
    }
</style>

<template>
    <div>
        <ul v-sortable="{ animation: 150, onUpdate: onProductsReordered }">
            <li v-for="product in products" :key="product.id" :style="{ 'width': columnWidth + '%' }">
                <div>
                    {{ product.name }}
                </div>
                <input type="hidden" name="product_order[]" :value="product.id">
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        created() {
            this.watchSortValues();
            this.watchGridValues();
        },
        data() {
            return {
                columns: null,
                rows: null,
                products: this.productsProp.slice(0),
                sort: null,
            };
        },
        computed: {
            columnWidth() {
                return 100 / this.columns;
            },
        },
        methods: {
            onProductsReordered(e) {
                this.products.splice(e.newIndex, 0, this.products.splice(e.oldIndex, 1)[0]);
                $('#Form-field-Category-product_sort').val('custom').trigger('change');
            },
            onSortChanged(sort) {
                if (sort === 'name:asc') {
                    this.products.sort((a, b) => {
                        let nameA = a.name.trim().toLowerCase();
                        let nameB = b.name.trim().toLowerCase();
                        if(nameA < nameB) return -1;
                        if(nameA > nameB) return 1;
                        return 0;
                    });
                } else if (sort === 'name:desc') {
                    this.products.sort((a, b) => {
                        let nameA = a.name.trim().toLowerCase();
                        let nameB = b.name.trim().toLowerCase();
                        if(nameA > nameB) return -1;
                        if(nameA < nameB) return 1;
                        return 0;
                    });
                } else if (sort === 'price:asc') {
                    this.products.sort((a, b) => a.price - b.price);
                } else if (sort === 'price:desc') {
                    this.products.sort((a, b) => b.price - a.price);
                } else if (sort === 'created_at:asc') {
                    this.products.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                } else if (sort === 'created_at:desc') {
                    this.products.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                } else if (sort === 'updated_at:asc') {
                    this.products.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
                } else if (sort === 'updated_at:desc') {
                    this.products.sort((a, b) => new Date(a.updated_at) - new Date(b.updated_at));
                }
            },
            watchSortValues() {
                let $sort = $('#Form-field-Category-product_sort');
                this.sort = $sort.val();
                $sort.on('change', e => this.sort = e.currentTarget.value);
            },
            watchGridValues() {
                let $rows = $('#Form-field-Category-product_rows');
                let $columns = $('#Form-field-Category-product_columns');

                this.rows = Number($rows.val());
                this.columns = Number($columns.val());
                $rows.on('change', e => this.rows = Number(e.currentTarget.value));
                $columns.on('change', e => this.columns = Number(e.currentTarget.value));
            },
        },
        props: [
            'lang',
            'productsProp',
        ],
        watch: {
            sort: 'onSortChanged',
        },
    };
</script>

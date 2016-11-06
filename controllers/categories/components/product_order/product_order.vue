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
        <ul v-sortable="{
            animation: 150,
        }">
            <li v-for="product in products" :style="{ 'width': columnWidth + '%' }">
                <div>
                        {{ product.name }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        created() {
            this.watchGridValues();
        },
        data() {
            return {
                columns: null,
                rows: null,
            };
        },
        computed: {
            columnWidth() {
                return 100 / this.columns;
            },
        },
        methods: {
            watchGridValues() {
                let $columns = $('#Form-field-Category-product_columns');
                let $rows = $('#Form-field-Category-product_rows');

                this.columns = Number($columns.val().trim());
                this.rows = Number($rows.val().trim());

                $columns.on('input', e => {
                    let value = Number(e.currentTarget.value.trim());

                    if (! Number.isNaN(value) && value > 0) {
                        this.columns = value;
                    }
                });

                $rows.on('input', e => {
                    let value = Number(e.currentTarget.value.trim());

                    if (! Number.isNaN(value) && value >= 0) {
                        this.rows = value;
                    }
                });
            },
        },
        props: [
            'lang',
            'products',
        ],
    };
</script>

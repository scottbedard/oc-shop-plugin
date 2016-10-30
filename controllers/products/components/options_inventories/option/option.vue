<style lang="scss" scoped>@import 'assets/scss/utils';
    ul {
        margin: 0;
        list-style: none;
        padding: 0;
    }

    .values {
        // @todo: figure out why using the form-group class causes
        // focus issues on the surrounding elements.
        padding-bottom: 20px;
    }

    li {
        margin-bottom: 10px;
        padding: 0;
        position: relative;

        > div {
            align-items: center;
            display: flex;

            :nth-child(2) {
                flex-grow: 1;
            }
        }

        i {
            color: #ccc;
        }

        a:hover i {
            color: $red;
            text-decoration: none;
        }

        input {
            border: 0;
            outline: none;
            padding-left: 10px;
            width: 100%;
        }
    }
</style>

<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
            <div class="form-group text-field span-full is-required">
                <label for="bedard-shop-option-name">{{ lang.options.form.name }}</label>
                <input id="bedard-shop-option-name" v-model="option.name" type="text" class="form-control" ref="name">
            </div>
            <div class="form-group text-field span-full">
                <label for="bedard-shop-option-name">{{ lang.options.form.placeholder }}</label>
                <input id="bedard-shop-option-name" v-model="option.placeholder" type="text" class="form-control">
            </div>
            <div class="values">
                <label for="bedard-shop-option-values">{{ lang.options.form.values }}</label>
                <ul v-sortable="{ handle: '.icon-bars' }">
                    <li class="form-group" v-for="(value, index) in option.values">
                        <div class="form-control">
                            <i class="icon-bars"></i>
                            <div>
                                <input type="text" v-model="value.name" @keydown="onValueKeydown">
                            </div>
                            <a href="#" @click.prevent="onDeleteValueClicked(index)">
                                <i class="icon-trash-o"></i>
                            </a>
                        </div>
                    </li>
                </ul>
                <input
                    @keydown="onNewValueKeydown"
                    class="form-control"
                    id="bedard-shop-option-values"
                    :placeholder="lang.options.form.values_placeholder"
                    type="text"
                    v-model="newValue">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                {{ lang.form.cancel }}
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                {{ createOrSave }}
            </button>
        </div>
    </div>
</template>

<script>
    import EventChannel from '../_channel';

    export default {
        created() {
            EventChannel.$on('option:opened', this.onOpened);
        },
        data() {
            return {
                newValue: '',
                option: {
                    name: '',
                    placeholder: '',
                    values: [],
                },
            };
        },
        computed: {
            context() {
                return this.sourceModel.id === null
                    ? 'create'
                    : 'update';
            },
            createOrSave() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.form.save;
            },
            title() {
                return this.context === 'create'
                    ? this.lang.options.form.create_title
                    : this.lang.options.form.update_title
            },
        },
        methods: {
            addValue() {
                this.option.values.push({
                    id: null,
                    sort_order: this.option.values.length,
                    name: this.newValue,
                });

                this.newValue = '';
            },
            onDeleteValueClicked(index) {
                this.option.values.splice(index, 1);
            },
            onNewValueKeydown(e) {
                let charCode = e.which || e.keyCode;
                if (charCode === 13 || charCode === 9) {
                    e.preventDefault();
                    this.addValue();
                }
            },
            onOpened() {
                setTimeout(() => this.$refs.name.focus(), 200);
            },
            onValueKeydown(e) {
                let charCode = e.which || e.keyCode;
                if (charCode === 13) {
                    e.preventDefault(); // prevent the enter key from submitting the form
                }
            },
        },
        props: [
            'channel',
            'inventories',
            'lang',
            'options',
            'sourceModel',
        ],
    };
</script>

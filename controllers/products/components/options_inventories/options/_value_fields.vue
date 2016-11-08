<style lang="scss" scoped>@import 'assets/scss/utils';
    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    li {
        padding: 0;
    }

    li > div {
        align-items: center;
        display: flex;
        margin-bottom: 10px;
        padding: 0;

        a {
            align-items: center;
            color: #aaa;
            display: flex;
            height: 100%;
            padding: 0 10px;
            text-decoration: none;
            &:before { margin: 0 }
            &.oc-icon-bars { cursor: move }
            &.oc-icon-bars:hover { color: #666 }
            &.oc-icon-trash-o:hover { color: $red }
        }

        input {
            background-color: transparent;
            border: none;
            flex-grow: 1;
            height: 100%;
            outline: none;
        }
    }
</style>

<template>
    <div>
        <label>{{ label }}</label>
        <ul v-sortable="{
            animation: 150,
            handle: 'a.oc-icon-bars',
            onUpdate: onValuesReordered,
        }">
            <li v-for="value in values" :key="value">
                <div class="form-control">
                    <a class="oc-icon-bars" href="#" @click.prevent></a>
                    <input v-model="value.name" @keydown="onValueKeydown">
                    <a class="oc-icon-trash-o" href="#" @click.prevent="onDeleteValue(value)"></a>
                </div>
            </li>
        </ul>
        <v-input-field
            :placeholder="lang.options.form.values_placeholder"
            :prevent-submit="true"
            @keydown="onNewValueKeydown"
            v-model="newValue">
        </v-input-field>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                newValue: '',
            };
        },
        computed: {
            newValueIsEmpty() {
                return this.newValueName.length < 1;
            },
            newValueName() {
                return this.newValue.trim();
            },
        },
        methods: {
            addNewValue() {
                this.$emit('add', {
                    name: this.newValueName,
                    option_id: null,
                    sort_order: null,
                });

                this.newValue = '';
            },
            onDeleteValue(value) {
                this.$emit('delete', value);
            },
            onNewValueKeydown(e) {
                if (e.keyCode === 9 || e.keyCode === 13) {
                    e.preventDefault();
                    if (! this.newValueIsEmpty) {
                        this.addNewValue();
                    }
                }
            },
            onValueKeydown(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                }
            },
            onValuesReordered(e) {
                this.$emit('reorder', e);
            },
        },
        props: [
            'label',
            'lang',
            'values',
        ],
    };
</script>

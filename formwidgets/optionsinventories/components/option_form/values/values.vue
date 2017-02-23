<style lang="scss" scoped>@import 'core';
    .v-required-label {
        &:after {
            background-color: #c20a0a;
            border-radius: 8px;
            content: "";
            display: inline-block;
            font-size: 60%;
            height: 5px;
            margin-left: 3px;
            vertical-align: super;
            width: 5px;
        }
    }
</style>

<template>
    <div>
        <label class="v-required-label">
            {{ 'bedard.shop.options.form.values' | trans(lang) }}
        </label>
        <div v-sortable="{ handle: '.oc-icon-bars', onEnd: onReorder }">
            <v-value-input
                v-for="value in values"
                :key="value._key"
                :value="value"
                @enter="onEnter"
                @input="onInput"
                @remove="onRemove">
            </v-value-input>
        </div>
        <v-form-input
            v-model="input"
            :placeholder="placeholder"
            @keydown.tab.prevent="onAdd"
            @keypress.enter.prevent="onAdd">
        </v-form-input>
    </div>
</template>

<script>
    import trans from 'assets/js/filters/trans/trans';
    import Sortable from 'sortablejs';

    export default {
        data() {
            return {
                input: '',
            };
        },
        components: {
            'v-value-input': require('./value_input/value_input'),
        },
        computed: {
            placeholder() {
                return trans('bedard.shop.options.form.values_placeholder', this.lang);
            },
        },
        directives: {
            sortable: {
                inserted: (el, binding) => new Sortable(el, binding.value || {}),
            },
        },
        methods: {
            clearInput() {
                this.input = '';
            },
            onAdd() {
                let trimmedValue = this.input.trim();

                if (trimmedValue.length) {
                    this.input = '';
                    this.$emit('add', trimmedValue);
                }
            },
            onEnter(e) {
                this.$emit('enter', e);
            },
            onInput(e, value) {
                this.$emit('input', e, value);
            },
            onRemove(value) {
                this.$emit('remove', value);
            },
            onReorder(indexes) {
                this.$emit('reorder', indexes);
            },
        },
        props: [
            'lang',
            'values',
        ],
    };
</script>

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
                ref="values"
                v-for="value in values"
                :key="value._key"
                :lang="lang"
                :value="value"
                @delete="onDelete"
                @enter="onEnter"
                @input="onInput"
                @remove="onRemove"
                @keydown.tab.prevent="onTab($event, value)">
            </v-value-input>
        </div>
        <v-form-input
            ref="input"
            v-model="input"
            :placeholder="placeholder"
            @keydown.tab.prevent="onInputTab"
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
            currentValues() {
                return this.values.filter(value => ! value._deleted);
            },
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
            focus() {
                if (this.values.length) {
                    this.$refs.values[0].focus();
                } else {
                    this.$refs.input.focus();
                }
            },
            focusNextValue(index) {
                let nextIndex = index + 1;
                if (nextIndex === this.values.length) {
                    this.$refs.input.focus();
                } else {
                    this.$refs.values[nextIndex].focus();
                }
            },
            focusLastValue() {
                if (this.values.length) {
                    this.$refs.values[this.$refs.values.length - 1].focus();
                } else {
                    this.$emit('focus-placeholder');
                }
            },
            focusPreviousValue(index) {
                let previousIndex = index - 1;
                if (previousIndex < 0) {
                    this.$emit('focus-placeholder');
                } else {
                    this.$refs.values[previousIndex].focus();
                }
            },
            onAdd() {
                let trimmedValue = this.input.trim();

                if (trimmedValue.length) {
                    this.input = '';
                    this.$emit('add', trimmedValue);
                }
            },
            onDelete(value) {
                this.$emit('delete', value);
            },
            onEnter(e) {
                this.$emit('enter', e);
            },
            onInput(e, value) {
                this.$emit('input', e, value);
            },
            onInputTab(e) {
                if (e.shiftKey) {
                    this.focusLastValue();
                }
            },
            onRemove(value) {
                this.$emit('remove', value);
            },
            onReorder(indexes) {
                this.$emit('reorder', indexes);
            },
            onTab(e, value) {
                let index = this.values.indexOf(value);

                if (e.shiftKey) {
                    this.focusPreviousValue(index);
                } else {
                    this.focusNextValue(index);
                }
            },
        },
        props: [
            'lang',
            'values',
        ],
    };
</script>

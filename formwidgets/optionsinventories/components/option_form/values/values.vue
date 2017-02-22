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
        <v-value-input
            v-for="value in values"
            :value="value">
        </v-value-input>
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
        methods: {
            onAdd() {
                let trimmedValue = this.input.trim();

                if (trimmedValue.length) {
                    this.input = '';
                    this.$emit('add', trimmedValue);
                }
            },
        },
        props: [
            'lang',
            'values',
        ],
    };
</script>

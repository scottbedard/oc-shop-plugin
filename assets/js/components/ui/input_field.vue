<style lang="scss" scoped>@import 'assets/scss/utils';
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<template>
    <div class="form-group text-field" :class="{
        'is-required': required,
        'span-full': span === 'full',
        'span-left': span === 'left',
        'span-right': span === 'right',
    }">
        <label v-if="label" @click="onLabelClicked">{{ label }}</label>
        <input
            class="form-control"
            ref="input"
            :placeholder="placeholder"
            :type="type"
            :value="value"
            @input="onInput"
            @keydown="onKeydown">
    </div>
</template>

<script>
    export default {
        methods: {
            onInput(e) {
                this.$emit('input', e.target.value);
            },
            onLabelClicked() {
                this.$refs.input.focus();
            },
            onKeydown(e) {
                if (this.preventSubmit && e.keyCode === 13) {
                    e.preventDefault();
                }

                this.$emit('keydown', e);
            },
        },
        props: {
            label: { default: null },
            placeholder: { default: '', type: String },
            preventSubmit: { default: false, type: Boolean },
            span: { default: 'full', type: String },
            required: { default: false, type: Boolean },
            type: { default: 'text', type: String },
            value: { default: null, required: true },
        },
    };
</script>

<style lang="scss" scoped>@import 'assets/scss/utils';
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<template>
    <div class="form-group text-field span-full" :class="{ 'is-required': required }">
        <label v-if="label">{{ label }}</label>
        <input class="form-control" @input="onInput" :type="type" :value="value">
    </div>
</template>

<script>
    export default {
        methods: {
            onInput(e) {
                this.$emit('input', e.target.value);
            },
        },
        props: {
            label: { default: null },
            required: { default: false, type: Boolean },
            type: { default: 'text', type: String },
            value: { default: null, required: true },
        },
    };
</script>

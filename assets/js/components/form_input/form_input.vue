<style lang="scss" scoped>@import 'core';
    label {
        &:empty {
            display: none;
        }
    }
</style>

<template>
    <div class="form-group text-field" :class="{ 'is-required': required }">
        <label @click="onLabelClicked"><slot></slot></label>
        <input
            class="form-control"
            ref="input"
            :placeholder="placeholder"
            :type="type"
            :value="value"
            @input="onInput"
            @keydown="onKeydown"
            @keypress="onKeypress"
        />
    </div>
</template>

<script>
    export default {
        methods: {
            onKeydown(e) {
                this.$emit('keydown', e);
            },
            onKeypress(e) {
                this.$emit('keypress', e);
            },
            onInput(e) {
                this.$emit('input', e.target.value);
            },
            onLabelClicked() {
                this.$refs.input.focus();
            },
        },
        props: {
            placeholder: { type: String, default: '' },
            required: { type: Boolean, default: false },
            type: { type: String, default: 'text' },
            value: { default: '' },
        },
    };
</script>

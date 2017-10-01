<style lang="scss" scoped>@import 'core';
    label {
        &:empty {
            display: none;
        }
    }
</style>

<template>
    <div class="form-group text-field" :class="{ 'is-required': required }">
        <label @click="focus"><slot></slot></label>
        <input
            class="form-control"
            ref="input"
            :data-input="dataInput"
            :placeholder="placeholder"
            :type="type"
            :value="value"
            @input="onInput"
            @keydown="onKeydown"
            @keypress="onKeypress"
            @keyup="onKeyup"
        />
        <p
            v-if="comment"
            class="help-block">
            {{ comment }}
        </p>
    </div>
</template>

<script>
    export default {
        methods: {
            focus() {
                this.$refs.input.focus();
            },
            onKeydown(e) {
                this.$emit('keydown', e);
            },
            onKeypress(e) {
                this.$emit('keypress', e);
            },
            onKeyup(e) {
                this.$emit('keyup', e);
            },
            onInput(e) {
                this.$emit('input', e.target.value);
            },
        },
        props: {
            comment: {
                default: null,
                type: String,
            },
            dataInput: {
                default: null,
            },
            placeholder: {
                default: '',
                type: String,
            },
            required: {
                default: false,
                type: Boolean,
            },
            type: {
                default: 'text',
                type: String,
            },
            value: {
                default: '',
            },
        },
    };
</script>

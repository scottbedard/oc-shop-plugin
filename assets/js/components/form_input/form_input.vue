<style lang="scss" scoped>@import 'core';

</style>

<template>
    <div class="form-group text-field" :class="{
        'is-required': required,
    }">
        <label @click="onLabelClicked">
            <slot></slot>
        </label>
        <input
            class="form-control"
            ref="input"
            :type="type"
            :value="value"
            @input="onInput"
            @keypress.enter="onEnter"
        />
    </div>
</template>

<script>
    export default {
        methods: {
            onEnter(e) {
                this.$emit('enter', e);
            },
            onInput(e) {
                this.$emit('input', e.target.value);
            },
            onLabelClicked() {
                this.$refs.input.focus();
            },
        },
        props: {
            required: { type: Boolean, default: false },
            type: { type: String, default: 'text' },
            value: { default: '' },
        },
    };
</script>

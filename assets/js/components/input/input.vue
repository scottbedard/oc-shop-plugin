<style lang="scss" scoped>@import 'core';

</style>

<template>
    <div class="form-group text-field" :class="{
        'is-required': required,
    }">
        <slot name="label"></slot>
        <input
            class="form-control"
            :type="type"
            :value="value"
            @input="onInput"
        />
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
            required: { type: Boolean, default: false },
            type: { type: String, default: 'text' },
            value: { default: '' },
        },
    };
</script>

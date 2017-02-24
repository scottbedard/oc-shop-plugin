<style lang="scss" scoped>@import 'core';
    select {
        width: 100%;
    }
</style>

<template>
    <select ref="select">
        <slot></slot>
    </select>
</template>

<script>
    export default {
        mounted() {
            $(this.$refs.select).select2({
                dropdownCssClass: 'ocInspectorDropdown'
                placeholder: this.placeholder,
            });

            $(this.$refs.select).on('select2:select', this.onChange);
        },
        methods: {
            onChange(e) {
                this.$emit('input', e.target.value);
            },
        },
        props: {
            placeholder: '',
        },
    };
</script>

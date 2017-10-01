<style lang="scss" scoped>@import 'core';
    a {
        align-items: center;
        border-radius: 3px;
        border: 2px dashed #ccc;
        color: #bdc3c7;
        display: flex;
        font-size: 0.85em;
        font-weight: 600;
        margin-top: 20px;
        outline: none;
        padding: 15px;
        text-decoration: none;
        text-transform: uppercase;
        transition:
            border-color $transition-duration $transition-timing-function,
            color $transition-duration $transition-timing-function;

        &:hover {
            border-color: $blue;
            color: $blue;
            text-decoration: none;
        }
    }

    i {
        padding-right: 12px;
    }
</style>

<template>
    <a href="#" @click.prevent="onClick">
        <i class="icon-plus"></i>
        <span>
            <slot />
        </span>
    </a>
</template>

<script>
    export default {
        methods: {
            onClick(e) {
                this.$emit('click', e);
            },
        },
    };
</script>

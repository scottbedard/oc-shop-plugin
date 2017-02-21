<style lang="scss" scoped>@import 'core';
    a {
        align-items: center;
        border-radius: 3px;
        border: 2px dashed #ccc;
        color: #bdc3c7;
        display: flex;
        font-size: 0.9em;
        font-weight: 600;
        margin-top: 20px;
        padding: 15px;
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
        padding-right: 15px;
    }
</style>

<template>
    <a href="#" @click.prevent="onCreateClicked">
        <i class="icon-plus"></i>
        <slot></slot>
    </a>
</template>

<script>
    export default {
        methods: {
            onCreateClicked() {
                this.$emit('click');
            },
        },
    };
</script>

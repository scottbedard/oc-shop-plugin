<style lang="scss" scoped>
    a {
        align-items: center;
        border: 2px dashed #BDC3C7;
        border-radius: 3px;
        color: #bdc3c7;
        display: flex;
        font-size: 13px;
        font-weight: 600;
        margin-top: 20px;
        padding: 15px;
        text-transform: uppercase;

        &:hover {
            border-color: #1F99DC;
            color: #1F99DC;
            text-decoration: none;
        }
    }

    i {
        padding-right: 15px;
    }
</style>

<template>
    <a href="#" @click.prevent="onClicked">
        <i class="icon-plus"></i>
        <slot></slot>
    </a>
</template>

<script>
    export default {
        methods: {
            onClicked() {
                this.$emit('click');
            },
        },
    };
</script>

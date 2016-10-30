<style lang="scss" scoped>
    a {
        align-items: center;
        border: dotted 2px #ebebeb;
        border-radius: 5px;
        color: #bdc3c7;
        display: flex;
        font-size: 13px;
        font-weight: 600;
        margin-top: 20px;
        padding: 15px;
        text-transform: uppercase;

        &:hover {
            text-decoration: none;
            background-color: #58b6f7;
            color: #ffffff;
            border: none;
        }
    }

    i {
        padding-right: 15px;
    }
</style>

<template>
    <a data-toggle="modal" :href="href">
        <i class="icon-plus"></i>
        <slot></slot>
    </a>
</template>

<script>
    export default {
        props: ['href'],
    };
</script>

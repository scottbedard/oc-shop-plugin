<style lang="scss" scoped>@import 'core';
    .v-value-input {
        align-items: center;
        display: flex;
        margin-bottom: 10px;
    }

    .v-value-input-container {
        flex: 1;
    }

    .oc-icon-bars {
        color: #ccc;
        font-size: 16px;

        &:after {
            margin-right: 13px;
        }
    }

    input {
        appearance: none;
        background-color: transparent;
        border: 0;
        width: 100%;
    }
</style>

<template>
    <div class="v-value-input form-control">
        <a class="oc-icon-bars" href="#" @click.prevent></a>
        <div class="v-value-input-container">
            <input type="text" :value="value.name">
        </div>
        <a class="oc-icon-trash-o" href="#" @click.prevent></a>
    </div>
</template>

<script>
    export default {
        props: [
            'value',
        ],
    };
</script>

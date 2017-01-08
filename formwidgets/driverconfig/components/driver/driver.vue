<style lang="scss" scoped>@import 'assets/scss/utils';
    .v-tile {
        align-items: center;
        background-color: #fff;
        border: 1px solid #ccc;
        display: inline-flex;
        height: 120px;
        justify-content: center;
        width: 120px;
    }
</style>

<template>
    <a class="v-tile" href="#" @click.prevent="onDriverClicked">
        {{ driver.details.name }}
    </a>
</template>

<script>
    export default {
        methods: {
            onDriverClicked() {
                this.$emit('click', this.driver.driver);
            },
        },
        props: [
            'driver'
        ]
    };
</script>

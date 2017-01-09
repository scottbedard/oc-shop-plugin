<style lang="scss" scoped>@import 'assets/scss/utils';
    .v-tile {
        align-items: center;
        background-color: #fff;
        border-radius: 3px;
        border: 1px solid #ccc;
        display: flex;
        float: left;
        height: 125px;
        justify-content: center;
        margin: 10px;
        text-overflow: ellipsis;
        transition: border-color 200ms;
        white-space: nowrap;
        width: 125px;

        &:hover {
            border-color: #999;
        }
    }

    img {
        max-width: 75px;
        height: auto;
    }
</style>

<template>
    <a class="v-tile" href="#" @click.prevent="onDriverClicked" :title="driver.details.name">
        <img v-if="driver.details.thumbnail" :src="driver.details.thumbnail">
        <span v-else>{{ driver.details.name }}</span>
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

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
        position: relative;
        text-overflow: ellipsis;
        transition: border-color 200ms;
        white-space: nowrap;
        width: 125px;

        &:hover {
            border-color: #999;
        }
    }

    .checkbox {
        left: 10px;
        margin: 0;
        position: absolute;
        top: 10px;

        label {
            position: static;
            width: 0;
        }
    }

    img {
        max-width: 75px;
        height: auto;
    }
</style>

<template>
    <a class="v-tile" href="javascript:;" @click.prevent="onDriverClicked" :title="driver.details.name">
        <div class="checkbox custom-checkbox" @click.stop>
            <input
                type="checkbox"
                value="1"
                :id="driver.driver"
                :name="`drivers[${ driver.driver }]`"
                :checked="enabledDrivers.indexOf(driver.driver) > -1" />
            <label :for="driver.driver"></label>
        </div>
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
            'driver',
            'enabledDrivers',
        ],
    };
</script>

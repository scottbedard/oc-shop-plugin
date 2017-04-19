<style lang="scss" scoped>@import 'core';
    .drivers {
        $spacing: 10px;
        display: flex;
        flex-wrap: wrap;
        margin: -$spacing;

        > a {
            $size: 150px;
            align-items: center;
            background-color: #fff;
            border-radius: 3px;
            border: 1px solid #ccc;
            display: block;
            display: flex;
            height: $size;
            justify-content: center;
            margin: $spacing;
            padding: 12px 20px;
            width: $size;

            &:hover {
                text-decoration: none;
            }

            img {
                height: auto;
                max-width: 100%;
            }
        }
    }
</style>

<template>
    <div>
        <div class="drivers">
            <a
                v-for="driver in drivers"
                href="#"
                :title="driver.name"
                @click.prevent="onDriverClicked(driver)">
                <img v-if="driver.thumbnail" :src="driver.thumbnail" />
                <span v-else>{{ driver.name }}</span>
            </a>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        methods: {
            onDriverClicked(driver) {
                $(this.$el).popup({
                    handler: 'onDriverClicked',
                    extraData: {
                        class: driver.class,
                    },
                });
            },
        },
        props: [
            'drivers',
            'lang',
        ],
    };
</script>

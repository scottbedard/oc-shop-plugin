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
            position: relative;
            width: $size;

            &:hover {
                text-decoration: none;
            }

            .v-checkbox {
                position: absolute;
                top: 10px;
                left: 10px;
            }

            img {
                height: auto;
                max-width: 100%;
            }
        }
    }
</style>

<template>
    <div class="drivers">
        <a
            v-for="driver in sortedDrivers"
            href="#"
            :title="driver.name"
            @click.prevent="onDriverClicked(driver)">
            <v-checkbox v-model="findEnabledObject(driver).isEnabled" />
            <img v-if="driver.thumbnail" :src="driver.thumbnail" />
            <span v-else>{{ driver.name }}</span>
        </a>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                enabled: this.drivers.map(driver => ({
                    class: driver.class,
                    isEnabled: false,
                })),
            };
        },
        computed: {
            sortedDrivers() {
                let firstParty = this.drivers.filter(driver => {
                    return driver.class.startsWith('Bedard\\Shop');
                });

                let thirdParty = this.drivers.filter(driver => {
                    return ! driver.class.startsWith('Bedard\\Shop');
                });

                return firstParty.concat(thirdParty);
            },
        },
        methods: {
            findEnabledObject(driver) {
                return this.enabled.find(d => d.class === driver.class);
            },
            isEnabled(driver) {
                return this.findEnabledObject(driver).isEnabled;
            },
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

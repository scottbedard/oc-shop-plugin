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
                bottom: 0px;
                color: #bbb;
                left: 0px;
                padding: 0 0 10px 10px;
                position: absolute;
                transition: color 50ms ease-in-out;

                &.is-checked {
                    color: #2a3e51;
                }
            }

            img {
                height: auto;
                max-width: 100%;
            }
        }
    }

    .driver-cover {
        transition: transform 50ms ease-in-out;

        &:not(.is-enabled) {
            opacity: 0.4;
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
            <v-checkbox v-model="findEnabledObject(driver).isEnabled">
                {{
                    findEnabledObject(driver).isEnabled
                        ? 'bedard.shop::lang.drivers.form.is_enabled_true'
                        : 'bedard.shop::lang.drivers.form.is_enabled_false'
                    | trans(lang)
                }}
            </v-checkbox>
            <div class="driver-cover" :class="{ 'is-enabled': findEnabledObject(driver).isEnabled }">
                <img v-if="driver.thumbnail" :src="driver.thumbnail" />
                <span v-else>{{ driver.name }}</span>
            </div>
        </a>
        <input type="hidden" :name="name" :value="outputValue" />
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        created() {
            this.parseValue();
        },
        data() {
            return {
                enabled: this.drivers.map(driver => ({
                    class: driver.class,
                    isEnabled: false,
                })),
            };
        },
        computed: {
            outputValue() {
                return JSON.stringify(this.enabled);
            },
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
            parseValue() {
                let enabledDrivers = this.value.filter(driver => driver.isEnabled);

                enabledDrivers.forEach(enabledDriver => {
                    let driver = this.enabled.find(driver => driver.class === enabledDriver.class);

                    driver.isEnabled = true;
                });
            },
        },
        props: [
            'drivers',
            'lang',
            'name',
            'value',
        ],
    };
</script>

<style lang="scss" scoped>
    .v-driver-config {
        margin: -10px;
    }
</style>

<template>
    <div class="v-driver-config">
        <v-driver
            v-for="driver in drivers"
            :driver="driver"
            :enabled-drivers="enabledDrivers"
            @click="onDriverClicked">
        </v-driver>
    </div>
</template>

<script>
    export default {
        components: {
            'v-driver': require('./driver/driver'),
        },
        methods: {
            onDriverClicked(driver) {
                let lang = this.lang;

                $(this.$el).popup({
                    handler: 'onLoadDriverSettings',
                    extraData: { driver, lang },
                });
            },
        },
        props: [
            'drivers',
            'enabledDrivers',
            'lang',
        ],
    };
</script>

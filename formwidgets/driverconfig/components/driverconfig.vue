<template>
    <div>
        <v-driver
            v-for="driver in drivers"
            :driver="driver"
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
                $(this.$el).popup({
                    handler: 'onLoadDriverSettings',
                    extraData: {
                        driver,
                    },
                });
            },
        },
        props: [
            'drivers',
        ],
    };
</script>

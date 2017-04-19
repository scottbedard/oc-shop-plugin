<style lang="scss" scoped>@import 'core';
    .v-status-selector {

    }
</style>

<template>
    <div class="v-status-selector">
        <v-select ref="select">
            <option v-for="status in statuses" :value="status.id">
                {{ status.name }}
            </option>
        </v-select>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.refresh();
        },
        methods: {
            refresh() {
                // give our modal some time to enter, than refresh
                // the select boxes so they can match the width
                if (this.$refs.select) {
                    setTimeout(() => this.$refs.select.refresh(), 100);
                }
            },
        },
        props: [
            'lang',
            'statuses',
        ],
    };
</script>

<style lang="scss" scoped>@import 'core';
    .v-status-selector {

    }
</style>

<template>
    <div class="v-status-selector">
        <v-select :name="name" ref="select" clearable>
            <option
                v-for="status in statuses"
                :selected="status.id == value"
                :value="status.id">
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
            'name',
            'statuses',
            'value',
        ],
    };
</script>

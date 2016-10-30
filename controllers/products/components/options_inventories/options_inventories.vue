<style lang="scss" scoped>@import 'assets/scss/utils';
    .v-options-inventories {
        $space-between-containers: 15px;
        display: flex;
        flex-wrap: wrap;
        margin: -$space-between-containers;

        > div {
            flex-basis: auto;
            padding: $space-between-containers;
            width: 100%;
            @include bp(tablet) { width: 50% }
        }
    }
</style>

<template>
    <div class="v-options-inventories">
        <div>
            <label>{{ lang.options.plural }}</label>
            <v-create-button @click="onCreateOptionClicked">
                {{ lang.options.form.create_button }}
            </v-create-button>
        </div>
        <div>
            <label>{{ lang.inventories.plural }}</label>
            <v-create-button @click="onCreateInventoryClicked">
                {{ lang.inventories.form.create_button }}
            </v-create-button>
        </div>
    </div>
</template>

<script>
    import CreateButtonComponent from './_create';

    export default {
        components: {
            'v-create-button': CreateButtonComponent,
        },
        methods: {
            onCreateInventoryClicked() {
                console.log ('create inventory');
            },
            onCreateOptionClicked() {
                console.log ('create option');
            },
        },
        props: [
            'lang',
        ],
    };
</script>

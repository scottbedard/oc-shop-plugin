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
            <v-create-button href="#bedard-shop-option">
                {{ lang.options.form.create_button }}
            </v-create-button>
            <v-popup id="bedard-shop-option">
                <v-option></v-option>
            </v-popup>
        </div>
        <div>
            <label>{{ lang.inventories.plural }}</label>
            <v-create-button href="#bedard-shop-inventory">
                {{ lang.inventories.form.create_button }}
            </v-create-button>
            <v-popup id="bedard-shop-inventory">
                <v-inventory></v-inventory>
            </v-popup>
        </div>
    </div>
</template>

<script>
    import CreateButtonComponent from './_create';
    import InventoryComponent from './inventory/inventory';
    import OptionComponent from './option/option';

    export default {
        components: {
            'v-create-button': CreateButtonComponent,
            'v-inventory': InventoryComponent,
            'v-option': OptionComponent,
        },
        props: [
            'lang',
        ],
    };
</script>

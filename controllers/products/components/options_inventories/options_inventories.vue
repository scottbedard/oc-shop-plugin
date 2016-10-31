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
            <v-create-button href="#bedard-shop-option" @click="onCreateOptionClicked">
                {{ lang.options.form.create_button }}
            </v-create-button>
            <v-popup id="bedard-shop-option">
                <v-option
                    @save="onOptionSaved"
                    :inventories="inventories"
                    :lang="lang"
                    :options="options"
                    :source-model="activeOption">
                </v-option>
            </v-popup>
        </div>
        <div>
            <label>{{ lang.inventories.plural }}</label>
            <v-create-button href="#bedard-shop-inventory" @click="onCreateInventoryClicked">
                {{ lang.inventories.form.create_button }}
            </v-create-button>
            <v-popup id="bedard-shop-inventory">
                <v-inventory
                    :inventories="inventories"
                    :lang="lang"
                    :options="options"
                    :source-model="activeInventory">
                </v-inventory>
            </v-popup>
        </div>
        <pre>
            {{ JSON.stringify($data) }}
        </pre>
    </div>
</template>

<script>
    import EventChannel from './_channel';
    import CreateButtonComponent from './_create';
    import InventoryComponent from './inventory/inventory';
    import OptionComponent from './option/option';

    export default {
        data() {
            return {
                activeInventory: {},
                activeOption: {},
                inventories: this.inventoriesProp.slice(0),
                options: this.optionsProp.slice(0),
            };
        },
        components: {
            'v-create-button': CreateButtonComponent,
            'v-inventory': InventoryComponent,
            'v-option': OptionComponent,
        },
        methods: {
            onCreateInventoryClicked() {
                this.activeInventory = {};
            },
            onCreateOptionClicked() {
                this.activeOption = {
                    id: null,
                    name: '',
                    placeholder: '',
                    values: [],
                };

                EventChannel.$emit('option:opened');
            },
            onOptionSaved(option) {
                if (option.id === null) {
                    this.options.push(option);
                }
            },
        },
        props: [
            'inventoriesProp',
            'lang',
            'optionsProp',
        ],
    };
</script>

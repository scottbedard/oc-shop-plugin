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

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    li {
        align-items: center;
        cursor: pointer;
        display: flex;
        height: 60px;
        padding: 0;

        &:hover {
            background-color: $light-blue;
            color: #fff;

            a {
                display: inline-block;
            }
        }

        a {
            background-color: $blue;
            color: #fff;
            display: none;
            margin-left: 1px;
            text-decoration: none;
        }

        i {
            align-items: center;
            display: flex;
            font-size: 20px;
            height: 60px;
            justify-content: center;
            width: 60px;
        }

        div {
            flex-grow: 1;
        }
    }
</style>

<template>
    <div class="v-options-inventories">
        <div>
            <label>{{ lang.options.plural }}</label>
            <ul>
                <li
                    @click="onOptionClicked(option)"
                    data-toggle="modal"
                    href="#bedard-shop-option"
                    v-for="option in options">
                    <i class="icon-plus"></i>
                    <div>
                        <div>{{ option.name }}</div>
                        <small>{{ optionSummary(option) }}</small>
                    </div>
                    <a href="#" @click.prevent><i class="icon-bars"></i></a>
                    <a href="#" @click.prevent><i class="icon-trash-o"></i></a>
                </li>
            </ul>
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
                newId: 0,
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
                    newId: null,
                    placeholder: '',
                    values: [],
                };

                EventChannel.$emit('option:opened');
            },
            onOptionClicked(option) {
                this.activeOption = option;
                EventChannel.$emit('option:opened');
            },
            onOptionSaved(option) {
                if (option.id === null && option.newId === null) {
                    option.newId = ++this.newId;
                    this.options.push(option);
                } else {
                    // update the old option
                }
            },
            optionSummary(option) {
                return option.values.map(value => value.name).join(', ');
            },
        },
        props: [
            'inventoriesProp',
            'lang',
            'optionsProp',
        ],
    };
</script>

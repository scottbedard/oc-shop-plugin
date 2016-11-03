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
                align-items: center;
                display: flex;
                flex-direction: column;
                height: 60px;
                justify-content: center;
                text-align: center;
                width: 60px;
            }
        }

        &.is-deleted {
            background-color: lighten($red, 20%);
            color: #fff;

            a {
                background-color: $red;
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
            justify-content: center;
            width: 60px;
        }

        div {
            flex-grow: 1;
        }
    }

    .sort-handle {
        cursor: move;
    }

    .pending-delete {
        font-size: 10px;
        word-wrap: break-word;
    }
</style>

<template>
    <div class="v-options-inventories">
        <div>
            <label>{{ lang.options.plural }}</label>
            <ul v-sortable="{ handle: '.sort-handle', onUpdate: onOptionsSorted }" ref="options">
                <li
                    v-for="option in options"
                    :class="{ 'is-deleted': option.is_deleted }"
                    @click="onOptionClicked(option)"
                    :data-id="option.id"
                    :data-new-id="option.newId"
                    data-toggle="modal"
                    href="#bedard-shop-option">
                    <i :class="{ 'icon-plus': ! option.is_deleted, 'icon-times': option.is_deleted }"></i>
                    <div>
                        <div>{{ option.name }}</div>
                        <small>{{ optionSummary(option) }}</small>
                    </div>
                    <a href="#" v-if="! option.is_deleted" @click.prevent.stop class="sort-handle"><i class="icon-bars"></i></a>
                    <a href="#" @click.prevent.stop="onDeleteOptionClicked(option)">
                        <i v-if="! option.is_deleted" class="icon-trash-o"></i>
                        <span class="pending-delete" v-else>
                            {{ lang.options.form.pending_delete }}
                        </span>
                    </a>
                </li>
            </ul>
            <v-create-button href="#bedard-shop-option" @click="onCreateOptionClicked">
                {{ lang.options.form.create_button }}
            </v-create-button>
            <v-popup id="bedard-shop-option">
                <v-option
                    @save="onOptionSaved"
                    :create-value-endpoint="optionCreateValue"
                    :inventories="inventories"
                    :lang="lang"
                    :options="options"
                    :source-model="activeOption"
                    :validation-endpoint="optionValidation">
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
                    :source-model="activeInventory"
                    :validation-endpoint="inventoryValidation">
                </v-inventory>
            </v-popup>
        </div>

        <!-- Pass data to form widget -->
        <input type="hidden" name="optionsInventories" :value="formData">
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
                inventories: this.inventoriesProp.map(inventory => {
                    inventory.is_deleted = false;
                    return inventory;
                }),
                newId: 0,
                options: this.optionsProp.map((option, index) => {
                    option.is_deleted = false;
                    option.sort_order = index;
                    return option;
                }),
            };
        },
        components: {
            'v-create-button': CreateButtonComponent,
            'v-inventory': InventoryComponent,
            'v-option': OptionComponent,
        },
        computed: {
            formData() {
                return JSON.stringify({
                    inventories: this.inventories,
                    options: this.options,
                });
            },
        },
        methods: {
            onCreateInventoryClicked() {
                this.activeInventory = {};
            },
            onCreateOptionClicked() {
                this.activeOption = {
                    is_deleted: false,
                    id: null,
                    name: '',
                    newId: null,
                    placeholder: '',
                    values: [],
                };

                EventChannel.$emit('option:opened');
            },
            onDeleteOptionClicked(option) {
                if (! option.is_deleted) {
                    $.oc.confirm(this.lang.options.form.delete_confirmation, () => {
                        option.is_deleted = true;
                    });
                } else {
                    option.is_deleted = false;
                }
            },
            onOptionClicked(option) {
                this.activeOption = option;
                EventChannel.$emit('option:opened');
            },
            onOptionSaved(data) {
                if (data.id === null && data.newId === null) {
                    data.newId = ++this.newId;
                    data.sort_order = this.options.length;
                    data.values.forEach(value => value.newId = value.id ? null : ++this.newId);

                    this.options.push(data);
                } else {
                    let option = this.options.find(model => {
                        return (model.id !== null && model.id === data.id) ||
                            (model.newId === data.newId && typeof model.newId !== 'undefined');
                    });

                    option.name = data.name;
                    option.placeholder = data.placeholder;
                    option.values = JSON.parse(JSON.stringify(data.values));
                }
            },
            onOptionsSorted() {
                // Unfortunately the sortable library is not reliable enough
                // to correctly splice the order of the values array when
                // sorting, so we'll just grab the order from the DOM.
                $(this.$refs.options).find('li').each((index, el) => {
                    let option = this.options.find(option => {
                        return (typeof el.dataset.id !== 'undefined' && el.dataset.id == option.id) ||
                            (typeof el.dataset.newId !== 'undefined' && el.dataset.newId == option.newId);
                    });

                    if (typeof option !== 'undefined') {
                        option.sort_order = index;
                    }
                });
            },
            optionSummary(option) {
                return option.values
                    .slice(0)
                    .sort((a, b) => {
                        if (a.sort_order > b.sort_order) return 1;
                        else if (a.sort_order < b.sort_order) return -1;
                        else return 0;
                    })
                    .map(value => value.name).join(', ');
            },
        },
        props: [
            'inventoriesProp',
            'inventoryValidation',
            'lang',
            'optionsProp',
            'optionCreateValue',
            'optionValidation',
        ],
    };
</script>

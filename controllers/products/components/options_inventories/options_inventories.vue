<style lang="scss">@import 'assets/scss/utils';
    ul.options-inventories-list {
        list-style: none;
        margin: 0;
        padding: 0;

        > li {
            align-items: center;
            display: flex;
            height: 60px;
            padding: 0;
            position: relative;

            &.is-deleted {
                $faded-color: #ccc;
                color: $faded-color !important;
                a { color: $faded-color !important }

                &:hover {
                    background-color: transparent;
                    a { background-color: transparent }
                }
            }

            &:hover {
                background-color: $light-blue;
                color: #fff;
                cursor: pointer;

                > a {
                    background-color: $blue;
                    color: #fff;
                }
            }

            > a {
                align-items: center;
                color: #333;
                display: flex;
                font-size: 1.25em;
                height: 60px;
                justify-content: center;
                text-decoration: none;
                width: 60px;
                &:before { margin: 0 }
                &:not(:first-of-type) { margin-left: 1px }
            }

            > div.item {
                padding: 0 10px;
                flex-grow: 1;

                > small {
                    font-size: 0.85em;
                }
            }
        }
    }
</style>

<template>
    <div>
        <!-- Lists -->
        <div class="layout-row">
            <v-options-list
                class="form-group span-left"
                :lang="lang"
                :options="options"
                @create="onOptionCreateClicked"
                @delete="onOptionDeleteClicked"
                @open="onOptionClicked"
                @reorder="onOptionsReordered">
            </v-options-list>
            <v-inventories-list
                class="form-group span-right"
                :lang="lang"
                :inventories="inventories"
                :options="options"
                @create="onInventoryCreateClicked"
                @delete="onInventoryDeleteClicked"
                @open="onInventoryClicked">
            </v-inventories-list>
        </div>

        <!-- Popups -->
        <v-popup ref="optionPopup">
            <v-options-form
                ref="optionForm"
                :create-endpoint="optionCreate"
                :create-value-endpoint="optionCreateValue"
                :lang="lang"
                :source-model="option"
                :validation-endpoint="optionValidation"
                @dismiss="onOptionDismissed"
                @save="onOptionSaved">
            </v-options-form>
        </v-popup>

        <v-popup ref="inventoryPopup">
            <v-inventory-form
                ref="inventoryForm"
                :lang="lang"
                :options="options"
                :source-model="inventory"
                :validation-endpoint="inventoryValidation"
                @dismiss="onInventoryDismissed"
                @save="onInventorySaved">
            </v-inventory-form>
        </v-popup>

        <!-- Form data -->
        <input type="hidden" name="optionsInventories" :value="formData">
    </div>
</template>

<script>
    import Factory from './factory';
    import InventoryFormComponent from './inventories/form';
    import InventoriesListComponent from './inventories/list';
    import OptionsFormComponent from './options/form';
    import OptionsListComponent from './options/list';

    export default {
        data() {
            return {
                focusDelay: 200,
                inventories: this.inventoriesProp.map(inventory => {
                    inventory.is_deleted = false;
                    inventory.values = {};
                    for (let value of inventory.option_values) {
                        inventory.values[value.option_id] = value;
                    }

                    delete inventory.option_values;
                    return inventory;
                }),
                inventory: Factory.inventory(),
                option: Factory.option(),
                options: this.optionsProp.map(option => {
                    option.is_deleted = false;
                    option.values.forEach(value => value.is_deleted = false);
                    return option;
                }),
            };
        },
        components: {
            'v-inventories-list': InventoriesListComponent,
            'v-inventory-form': InventoryFormComponent,
            'v-options-form': OptionsFormComponent,
            'v-options-list': OptionsListComponent,
        },
        computed: {
            formData() {
                let data = JSON.parse(JSON.stringify({
                    inventories: this.inventories,
                    options: this.options,
                }));

                data.inventories.map(inventory => {
                    inventory.valueIds = Object.keys(inventory.values)
                        .filter(id => inventory.values[id])
                        .map(id => inventory.values[id].id);

                    delete inventory.newId;
                    delete inventory.values;
                    return inventory;
                });

                return JSON.stringify(data);
            },
        },
        methods: {
            onInventoryCreateClicked() {
                this.inventory = Factory.inventory();
                this.openInventoryPopup();
            },
            onInventoryClicked(inventory) {
                this.inventory = inventory;
                this.openInventoryPopup();
            },
            onInventoryDeleteClicked(inventory) {
                inventory.is_deleted = ! inventory.is_deleted;
            },
            onInventoryDismissed() {
                this.$refs.inventoryPopup.dismiss();
            },
            onInventorySaved(inventory) {
                let existingInventory = this.inventories.find(model => {
                    return (inventory.id && inventory.id === model.id)
                        || (! inventory.id && inventory.newId === model.newId);
                });

                if (existingInventory) {
                    this.inventories.splice(this.inventories.indexOf(existingInventory), 1, inventory);
                } else {
                    this.inventories.push(inventory);
                }

                this.$refs.inventoryPopup.dismiss();
            },
            onOptionClicked(option) {
                this.option = option;
                this.openOptionPopup();
            },
            onOptionCreateClicked() {
                this.option = Factory.option();
                this.openOptionPopup();
            },
            onOptionDeleteClicked(option) {
                option.is_deleted = ! option.is_deleted;
            },
            onOptionDismissed() {
                this.$refs.optionPopup.dismiss();
            },
            onOptionSaved(option) {
                let existingOption = this.options.find(model => {
                    return (option.id && option.id === model.id)
                        || (! option.id && option.newId === model.newId);
                });

                if (existingOption) {
                    this.options.splice(this.options.indexOf(existingOption), 1, option);
                } else {
                    this.options.push(option);
                }

                this.syncOptionValueReferences();
                this.$refs.optionPopup.dismiss();
            },
            onOptionsReordered({ newIndex, oldIndex }) {
                this.options.splice(newIndex, 0, this.options.splice(oldIndex, 1)[0]);
            },
            openInventoryPopup() {
                this.$refs.inventoryPopup.show();
                setTimeout(this.$refs.inventoryForm.focus, this.focusDelay);
            },
            openOptionPopup() {
                this.$refs.optionPopup.show();
                setTimeout(this.$refs.optionForm.focus, this.focusDelay);
            },
            syncOptionValueReferences() {
                for (let inventory of this.inventories) {
                    for (let id of Object.keys(inventory.values)) {
                        let value = inventory.values[id];
                        let option = this.options.find(model => model.id === value.option_id);
                        inventory.values[id] = option.values.find(model => model.id === value.id);
                    }
                }
            },
        },
        props: [
            'inventoriesProp',
            'inventoryValidation',
            'lang',
            'optionCreate',
            'optionsProp',
            'optionCreateValue',
            'optionValidation',
        ],
    };
</script>

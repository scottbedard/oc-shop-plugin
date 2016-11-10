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

            &:hover {
                background-color: $light-blue;
                color: #fff;
                cursor: pointer;

                > a {
                    background-color: $blue;
                    color: #fff;
                }

                > .delete-border {
                    border: 2px dashed #ccc;
                    bottom: 0;
                    left: 0;
                    pointer-events: none;
                    position: absolute;
                    right: 0;
                    top: 0;
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
                @create="onInventoryCreateClicked">
            </v-inventories-list>
        </div>

        <!-- Popups -->
        <v-popup ref="optionPopup">
            <v-options-form
                ref="optionForm"
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
                :validation-endpoint="inventoryValidation">
            </v-inventory-form>
        <v-popup>

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
                return JSON.stringify({
                    inventories: this.inventories,
                    options: this.options,
                });
            },
        },
        methods: {
            onInventoryCreateClicked() {
                this.inventory = Factory.inventory();
                this.openInventoryPopup();
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

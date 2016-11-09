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
            <v-inventories-list class="form-group span-right"></v-inventories-list>
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

        <!-- Form data -->
        <input type="text" name="optionsInventories" :value="formData">
    </div>
</template>

<script>
    import CreateOption from './options/factory';
    import InventoriesListComponent from './inventories/list';
    import OptionsFormComponent from './options/form';
    import OptionsListComponent from './options/list';

    export default {
        data() {
            return {
                focusDelay: 200,
                inventory: {},
                option: CreateOption(),
                options: this.optionsProp.map(option => {
                    option.is_deleted = false;
                    return option;
                }),
                inventories: [],
            };
        },
        components: {
            'v-inventories-list': InventoriesListComponent,
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
            onOptionClicked(option) {
                this.option = option;
                this.openOptionPopup();
            },
            onOptionCreateClicked() {
                this.option = CreateOption();
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

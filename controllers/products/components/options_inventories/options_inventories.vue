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

            > div {
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
                @create="onCreateOption"
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
                options: this.optionsProp.slice(0),
            };
        },
        components: {
            'v-inventories-list': InventoriesListComponent,
            'v-options-form': OptionsFormComponent,
            'v-options-list': OptionsListComponent,
        },
        methods: {
            onCreateOption() {
                this.option = CreateOption();
                this.openOptionPopup();
            },
            onOptionClicked(option) {
                this.option = option;
                this.openOptionPopup();
            },
            onOptionDismissed() {
                this.$refs.optionPopup.dismiss();
            },
            onOptionSaved(option) {
                let existingOption = this.options.find(model => {
                    return (option.id && option.id === model.id)
                        || option.newId === model.newId;
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

<style lang="scss" scoped>@import 'assets/scss/utils';

</style>

<template>
    <div>
        <!-- Lists -->
        <div class="layout-row">
            <v-options-list
                class="form-group span-left"
                :lang="lang"
                :options="options"
                @create="onCreateOption">
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
            };
        },
        components: {
            'v-inventories-list': InventoriesListComponent,
            'v-options-form': OptionsFormComponent,
            'v-options-list': OptionsListComponent,
        },
        methods: {
            createOption(option) {
                this.options.push(option);
            },
            onCreateOption() {
                this.option = CreateOption();
                this.$refs.optionPopup.show();
                setTimeout(this.$refs.optionForm.focus, this.focusDelay);
            },
            onOptionDismissed() {
                this.$refs.optionPopup.dismiss();
            },
            onOptionSaved(option) {
                if (option.id) this.updateOption(option);
                else this.createOption(option);
                this.$refs.optionPopup.dismiss();
            },
            updateOption(option) {

            },
        },
        props: [
            'inventoriesProp',
            'inventoryValidation',
            'lang',
            'options',
            'optionCreateValue',
            'optionValidation',
        ],
    };
</script>

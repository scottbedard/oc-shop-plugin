<style lang="scss" scoped>@import 'core';
    .option {
        // margin-bottom: 12px;
    }
</style>

<template>
    <div v-if="isVisible">
        <div
            v-for="option in options"
            v-if="!option._delete"
            class="form-group option"
            :data-option="option._key"
            :key="option._key">
            <label>{{ option.name }}</label>
            <v-select
                :clearable="true"
                :placeholder="option.placeholder"
                @input="select(option.values, $event)">
                <option
                    v-for="value in option.values"
                    :data-value="value._key"
                    :disabled="value._delete"
                    :title="deleteValueTitle"
                    :key="value._key"
                    :value="value._key"
                    :selected="isSelected(value._key)">
                    {{ value.name }}
                </option>
            </v-select>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        computed: {
            ...mapState('inventories', {
                isVisible: state => state.inventoryForm.isVisible,
                lang: state => state.lang,
                options: state => state.options,
            }),
            deleteValueTitle() {
                return trans('bedard.shop.inventories.form.delete_value_title', this.lang);
            },
        },
        methods: {
            isSelected(key) {
                return this.$store.state.inventories.inventoryForm.data.valueKeys.includes(key);
            },
            select(values, selectedKey) {
                this.$store.dispatch('inventories/selectInventoryValue', { values, selectedKey });
            },
        },
    };
</script>

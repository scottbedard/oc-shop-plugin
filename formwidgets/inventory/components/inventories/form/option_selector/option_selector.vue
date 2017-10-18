<style lang="scss" scoped>@import 'core';
    .option {
        // margin-bottom: 12px;
    }
</style>

<template>
    <div v-if="isVisible">
        <div v-for="option in options" class="form-group option" :key="option._key">
            <label>{{ option.name }}</label>
            <v-select
                :clearable="true"
                :placeholder="option.placeholder"
                @input="select(option.values, $event)">
                <option
                    v-for="value in option.values"
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

    export default {
        computed: {
            ...mapState('inventories', {
                isVisible: state => state.inventoryForm.isVisible,
                options: state => state.options,
            }),
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

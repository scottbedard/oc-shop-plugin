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
                @input="selectValue">
                <option
                    v-for="value in option.values"
                    :key="value._key"
                    :value="value._key">
                    {{ value.name }}
                </option>
            </v-select>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data: () => ({ foo: null }),
        computed: {
            ...mapState('inventories', {
                options: state => state.options,
                isVisible: state => state.inventoryForm.isVisible,
            }),
        },
        methods: {
            selectValue(value) {
                console.log ('selecting', value);
            },
        },
    };
</script>

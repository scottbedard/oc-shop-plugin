<style lang="scss" scoped>@import 'core';
    .v-option {
        padding-bottom: 20px;
    }
</style>

<style lang="scss">@import 'core';
    .select2-results__option.is-deleted {
        background-color: #fafafa;
        color: #999;
    }
</style>

<template>
    <div class="v-option-selector">
        <div v-for="option in options" class="v-option">
            <label>{{ option.name }}</label>
            <v-select
                ref="select"
                :clearable="isClearable(option)"
                :placeholder="option.placeholder"
                @input="onInput"
                @clear="onClear(option)">
                <option
                    v-for="value in option.values"
                    :class="{ 'is-deleted': value._deleted }"
                    :selected="inventory.value_ids.indexOf(value.id) > -1"
                    :value="value.id">
                    {{ value.name }}
                </option>
            </v-select>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            isClearable(option) {
                return Boolean(option.values.find(value => {
                    return this.inventory.value_ids.indexOf(value.id) > -1;
                }));
            },
            onClear(option) {
                this.$emit('clear', option);
            },
            onInput(value) {
                this.$emit('change', parseInt(value));
            },
            refresh() {
                // give our modal some time to enter, than refresh
                // the select boxes so they can match the width
                setTimeout(() => this.$refs.select.forEach(select => select.refresh()), 300);
            },
        },
        props: [
            'inventory',
            'options',
        ],
    };
</script>

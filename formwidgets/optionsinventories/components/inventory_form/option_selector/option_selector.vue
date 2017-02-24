<style lang="scss" scoped>@import 'core';

</style>

<template>
    <div class="v-option-selector">
        <div v-for="option in options">
            <label>{{ option.name }}</option>
            <v-select :placeholder="option.placeholder">
                <option
                    v-for="value in option.values"
                    :value="value.id">
                    {{ value.name }}
                </option>
            </v-select>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'options',
        ],
    };
</script>

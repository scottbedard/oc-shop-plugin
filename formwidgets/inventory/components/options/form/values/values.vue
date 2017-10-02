<style lang="scss" scoped>@import 'core';
    .list {
        .form-group {
            padding-bottom: 10px;
        }
    }
</style>

<template>
    <div class="form-group text-field is-required">
        <!-- label -->
        <label class="v-required-label" @click="focusNewValue">
            {{ 'bedard.shop.options.form.values' | trans(lang) }}
        </label>

        <!-- list -->
        <div class="list">
            <div v-for="value in values" :key="value._key">
                <v-form-input
                    ref="value"
                    :value="value.name"
                />
            </div>
        </div>

        <!-- create -->
        <v-form-input
            v-model="newValue"
            data-input="new-value"
            ref="newValue"
            :placeholder="placeholder"
            @keydown.tab.prevent="onNewValueTab"
            @keydown.enter.prevent="addValue"
        />
    </div>
</template>

<script>
    import trans from 'assets/js/filters/trans/trans';
    import { mapActions, mapState } from 'vuex';
    import { mapTwoWayState } from 'spyfu-vuex-helpers';

    export default {
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
            ...mapTwoWayState('inventories', {
                'optionForm.data.newValue': 'setOptionFormNewValue',
                'optionForm.data.values': 'setOptionFormValues',
            }),
            placeholder() {
                return trans('bedard.shop.options.form.values_placeholder', this.lang);
            },
        },
        methods: {
            ...mapActions('inventories', {
                addValue: 'addValueToOption',
            }),
            focusNewValue() {
                this.$refs.newValue.focus();
            },
            focusLastValueOrPlaceholder() {
                if (this.$refs.value) {
                    this.$refs.value[this.$refs.value.length - 1].focus();
                } else {
                    this.$emit('focus-placeholder');
                }
            },
            onNewValueTab(e) {
                if (e.shiftKey) {
                    this.focusLastValueOrPlaceholder();
                } else {
                    this.addValue();
                }
            },
        },
    };
</script>

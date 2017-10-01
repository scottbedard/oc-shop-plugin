<template>
    <div>

        <!-- create value -->
        <div>
            <v-form-input
                v-model="newValue"
                data-input="new-value"
                :placeholder="placeholder"
                @keydown.tab.prevent="addValue"
                @keydown.enter.prevent="addValue"
            />
        </div>
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
        },
    };
</script>

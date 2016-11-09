<style lang="scss" scoped>@import 'assets/scss/utils';
    .oc-icon-bars {
        cursor: move;
    }

    .values {
        font-size: 0.85em;
    }
</style>

<template>
    <div>
        <label>{{ lang.options.plural }}</label>

        <ul class="options-inventories-list" v-sortable="{
            animation: 150,
            handle: '.oc-icon-bars',
            onUpdate: onOptionsReordered,
        }">
            <li v-for="option in options" :key="option.id || option.newId" @click="onOptionClicked(option)">
                <a href="#" @click.prevent class="oc-icon-plus"></a>
                <div>
                    <div>{{ option.name }}</div>
                    <div class="values">{{ valueString(option) }}</div>
                </div>
                <a href="#" @click.prevent class="oc-icon-bars"></a>
                <a href="#" @click.prevent.stop="onDeleteOptionClicked(option)" class="oc-icon-trash-o"></a>
            </li>
        </ul>

        <v-create-button @click="onCreateClicked">
            {{ lang.options.form.create_button }}
        </v-create-button>
    </div>
</template>

<script>
    import CreateButtonComponent from '../_create_button';

    export default {
        components: {
            'v-create-button': CreateButtonComponent,
        },
        methods: {
            onCreateClicked() {
                this.$emit('create');
            },
            onDeleteOptionClicked(option) {

            },
            onOptionClicked(option) {
                this.$emit('open', option);
            },
            onOptionsReordered(e) {
                this.$emit('reorder', e);
            },
            valueString(option) {
                return option.values.map(value => value.name).join(', ');
            },
        },
        props: [
            'label',
            'lang',
            'options',
            'values',
        ],
    };
</script>

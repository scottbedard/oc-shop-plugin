<style lang="scss" scoped>@import 'assets/scss/utils';
    .oc-icon-bars {
        cursor: move;
    }

    .values {
        font-size: 0.85em;
    }

    ul.options-inventories-list {
        > li.is-deleted {
            $faded-color: #ccc;
            color: $faded-color;
            a { color: $faded-color }

            &:hover {
                background-color: transparent;
                a { background-color: transparent }
            }
        }
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
            <li
                v-for="option in options"
                :class="{ 'is-deleted': option.is_deleted }"
                :key="option.id || option.newId"
                @click="onOptionClicked(option)">
                <div v-show="option.is_deleted" class="delete-border"></div>
                <a href="#" @click.prevent class="oc-icon-plus"></a>
                <div class="item">
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
                this.$emit('delete', option);
                this.$forceUpdate();
            },
            onOptionClicked(option) {
                this.$emit('open', option);
            },
            onOptionsReordered(e) {
                this.$emit('reorder', e);
            },
            valueString(option) {
                return option.values
                    .filter(value => ! value.is_deleted)
                    .map(value => value.name).join(', ');
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

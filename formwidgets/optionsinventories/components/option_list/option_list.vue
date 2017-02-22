<style lang="scss" scoped>@import 'core';
    .v-small {
        font-size: 0.75em;
    }

    .icon-bars {
        cursor: move;
    }
</style>

<template>
    <v-list @reorder="onReorder" sortable>
        <v-list-item
            v-for="option in options"
            :key="option.id"
            @click="onOptionClicked(option)">
            <i class="icon-plus" slot="icon"></i>
            <div slot="content">
                <div>{{ option.name }}</div>
                <div class="v-small">{{ getValues(option) }}</div>
            </div>
            <div slot="actions">
                <i class="icon-bars"></i>
                <i class="icon-trash-o"></i>
            </div>
        </v-list-item>
    </v-list>
</template>

<script>
    export default {
        components: {
            'v-list': require('../list/list'),
            'v-list-item': require('../list/item/item'),
        },
        methods: {
            getValues(option) {
                if (option.values.length === 0) {
                    return;
                }

                return option.values.map(value => value.name).join(', ');
            },
            onOptionClicked(option) {
                this.$emit('click', option);
            },
            onReorder(indexes) {
                this.$emit('reorder', indexes);
            },
        },
        props: [
            'options',
        ],
    };
</script>

<style lang="scss" scoped>@import 'core';
    .name {
        font-size: 16px;
    }

    .values {
        font-size: 12px;
    }
</style>

<template>
    <div>
        <v-list-item
            v-for="option in options"
            :key="option._key"
            @click="edit(option)">
            <div class="square" slot="icon">
                <i class="icon-plus"></i>
            </div>
            <div slot="main">
                <div class="name">{{ option.name }}</div>
                <div class="values">{{ valuesString(option.values) }}</div>
            </div>
            <template slot="actions">
                <div class="square">
                    <i class="icon-bars"></i>
                </div>
                <div class="square">
                    <i class="icon-trash-o"></i>
                </div>
            </template>
        </v-list-item>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        components: {
            'v-list-item': require('../../shared/list_item').default,
        },
        computed: {
            ...mapState('inventories', [
                'options',
            ]),
        },
        methods: {
            ...mapActions('inventories', {
                edit: 'showEditOptionForm',
            }),
            valuesString(values) {
                return values.map(value => value.name.trim()).join(', ');
            },
        },
    };
</script>

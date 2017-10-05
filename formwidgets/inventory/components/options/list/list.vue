<style lang="scss" scoped>@import 'core';
    .list-item.is-deleted {
        opacity: 0.6;

        .square.delete {
            &:hover {
                background-color: transparent;
                color: $green;
            }
        }
    }

    .list-item.is-deleted,
    .is-reordering /deep/ .list-item {
        &:hover,
        &:hover .square {
            background-color: transparent;
            color: #333;
        }
    }

    .name {
        font-size: 16px;
    }

    .values {
        font-size: 12px;
    }

    .handle {
        cursor: move;
    }

    .list-item .square.delete {
        // &:hover {
        //     background-color: $red;
        //     color: #fff;
        // }
    }

    .sortable-ghost {
        border: 2px dashed #333;
        opacity: 0.2;
    }
</style>

<template>
    <div v-sortable="sortableOptions">
        <v-list-item
            v-for="option in options"
            :class="{ 'is-deleted': option._delete }"
            :key="option._key"
            @click="edit(option)">
            <div class="square icon" slot="icon">
                <i class="icon-plus"></i>
            </div>
            <div slot="main">
                <div class="name">{{ option.name }}</div>
                <div class="values">{{ valuesString(option.values) }}</div>
            </div>
            <template slot="actions">
                <div class="square handle" :title="reorderTitle">
                    <i class="icon-bars"></i>
                </div>
                <div class="square delete" :title="deleteTitle(option)" @click="toggleOptionDelete(option)">
                    <i v-if="option._delete" class="icon-undo"></i>
                    <i v-else class="icon-trash-o"></i>
                </div>
            </template>
        </v-list-item>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        components: {
            'v-list-item': require('../../shared/list_item').default,
        },
        computed: {
            ...mapState('inventories', {
                isReordering: state => state.optionsIsReordering,
                lang: state => state.lang,
                options: state => state.options,
            }),
            reorderTitle() {
                return trans('bedard.shop.options.list.reorder_title', this.lang);
            },
            sortableOptions() {
                return {
                    animation: 100,
                    handle: '.handle',
                    onEnd: this.onReorderEnd,
                    onStart: this.onReorderStart,
                };
            },
        },
        methods: {
            ...mapActions('inventories', {
                edit: 'showEditOptionForm',
                toggleOptionDelete: 'toggleOptionDelete',
            }),
            deleteTitle(option) {
                return option._delete
                    ? trans('bedard.shop.options.list.restore_title', this.lang)
                    : trans('bedard.shop.options.list.delete_title', this.lang);
            },
            onReorderStart() {
                this.$store.commit('inventories/setOptionsIsReordering', true);
            },
            onReorderEnd(indices) {
                this.$store.commit('inventories/setOptionsIsReordering', false);
                this.$store.dispatch('inventories/reorderOption', indices);
            },
            valuesString(values) {
                return values.map(value => value.name.trim()).join(', ');
            },
        },
    };
</script>

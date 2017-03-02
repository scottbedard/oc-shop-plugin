<style lang="scss">@import 'core';
    .bedard-shop {
        .v-options-inventories-list {
            small {
                font-size: 0.75em;
            }
        }
    }
</style>

<template>
    <div class="v-options-inventories-list">
        <div v-if="sortable" v-sortable="{ onEnd: onReorder }">
            <slot></slot>
        </div>
        <div v-else>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import Sortable from 'sortablejs';

    export default {
        directives: {
            sortable: {
                inserted: (el, binding) => new Sortable(el, binding.value || {}),
            },
        },
        methods: {
            onReorder(indexes) {
                this.$emit('reorder', indexes);
            },
        },
        props: {
            sortable: {
                type: Boolean,
                default: false,
            },
        },
    };
</script>

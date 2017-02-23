<style lang="scss" scoped>@import 'core';

</style>

<template>
    <div v-if="sortable" v-sortable="{ onEnd: onReorder }">
        <slot></slot>
    </div>

    <div v-else>
        <slot></slot>
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

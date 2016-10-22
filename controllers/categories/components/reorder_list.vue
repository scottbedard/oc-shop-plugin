<template>
    <ul v-sortable="{ group: 'v-reorder-list', onSort: onSort }">
        <slot></slot>
    </ul>
</template>

<script>
    export default {
        methods: {
            onSort() {
                this.$emit("sort");
            },
        },
    };
</script>

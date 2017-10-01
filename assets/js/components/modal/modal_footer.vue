<style lang="scss" scoped>
    .v-modal-footer {
        align-items: center;
        display: flex;
        justify-content: flex-end;
        min-height: 60px;
        padding: 0 20px 20px;
    }

    .v-modal-footer /deep/ button + button {
        margin-left: 12px;
    }
</style>

<template>
    <div class="v-modal-footer">
        <slot></slot>
    </div>
</template>

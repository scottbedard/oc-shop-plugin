<style lang="scss" scoped>@import 'assets/scss/utils';
    .modal-footer {
        align-items: center;
        display: flex;
        justify-content: flex-end;
        min-height: 60px;
    }
</style>

<template>
    <div class="modal-footer">
        <slot></slot>
    </div>
</template>

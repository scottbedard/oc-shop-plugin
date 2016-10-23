<style lang="scss" scoped>
    .v-loader {
        align-items: center;
        display: flex;
    }

    .spinner {
        animation: spin 1s linear infinite;
        background-image: url(../../../../../../../modules/system/assets/ui/images/loader-transparent.svg);
        background-position: 50% 50%;
        background-size: 40px 40px;
        height: 40px;
        width: 40px;
    }

    .side-content {
        &:not(:empty) {
            padding-left: 20px;
        }
    }
</style>

<template>
    <div class="v-loader">
        <div class="spinner"></div>
        <div class="side-content">
            <slot></slot>
        </div>
    </div>
</template>

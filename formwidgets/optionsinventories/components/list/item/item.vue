<style lang="scss" scoped>@import 'core';
    $size: 60px;
    .v-item {
        align-items: center;
        border-radius: 3px;
        color: #333;
        display: flex;
        height: $size;
        outline: none;
        overflow: hidden;
        text-decoration: none;
        transition: opacity $transition-duration $transition-timing-function;

        &:not(.is-deleted):hover {
            background-color: $light-blue;
            color: #fff;

            .v-icon,
            .v-actions i {
                background-color: $blue;
            }
        }
    }

    .v-icon {
        align-items: center;
        display: none;
        height: 100%;
        justify-content: center;
        width: $size;
        @include bp(desktop) { display: inline-flex }
    }

    .v-content {
        display: flex;
        align-items: center;
        padding: 0 10px;
        flex-grow: 1;
    }

    .v-actions {
        height: 100%;

        div {
            display: flex;
            height: 100%;
        }

        i {
            display: inline-flex;
            margin-left: 1px;
            height: 100%;
            align-items: center;
            justify-content: center;
            width: $size;
        }
    }

    .is-deleted {
        opacity: 0.5;
    }
</style>

<template>
    <a class="v-item" href="#" @click.prevent="onClicked">
        <div class="v-icon">
            <slot name="icon"></slot>
        </div>
        <div class="v-content">
            <div>
                <slot name="content"></slot>
            </div>
        </div>
        <div class="v-actions">
            <slot name="actions"></slot>
        </div>
    </a>
</template>

<script>
    export default {
        methods: {
            onClicked() {
                this.$emit('click');
            },
        },
    };
</script>

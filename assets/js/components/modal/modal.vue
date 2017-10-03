<style lang="scss" scoped>@import 'core';
    .modal-enter-active,
    .modal-leave-active {
        transition: all 150ms ease-in-out;

        .modal-dialog {
            transition: all 150ms ease-in-out;
        }
    }

    .modal-enter,
    .modal-leave-to {
        background-color: rgba(0, 0, 0, 0);

        .modal-dialog {
            opacity: 0;
            transform: scale(0.7);
        }
    }

    .modal-enter-to,
    .modal-leave {
        .modal-dialog {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<template>
    <transition name="modal" mode="out-in">
        <div v-show="visible" class="popup-backdrop" @click="onBackdropClick">
            <div class="modal" style="display: block">
                <div class="modal-dialog">
                    <div class="modal-content" @click.stop>
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        methods: {
            onBackdropClick() {
                this.$emit('close');
            },
        },
        props: {
            visible: {
                required: true,
                type: Boolean,
            },
        },
    };
</script>

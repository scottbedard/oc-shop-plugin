<template>
    <transition name="fade" mode="out-in">
        <div v-show="visible" class="popup-backdrop" @click="onBackdropClick">
            <div class="control-popup modal fade" style="display: block" :class="inClass">
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
        data() {
            return {
                in: this.visible,
            };
        },
        computed: {
            inClass() {
                return this.in ? 'in' : null;
            },
        },
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
        watch: {
            visible(visible) {
                this.$nextTick(() => this.$nextTick(() => this.in = visible));
            },
        },
    };
</script>

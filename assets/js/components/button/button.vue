<template>
    <button
        class="btn"
        :class="{
            'btn-default': isDefault,
            'btn-primary': primary,
        }"
        :type="type"
        @click="onClicked">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        computed: {
            isDefault() {
                return ! this.primary;
            },
        },
        methods: {
            onClicked(e) {
                this.$emit('click', e);
            },
        },
        props: {
            primary: { type: Boolean, default: false },
            type: { default: 'button', type: String },
        },
    };
</script>

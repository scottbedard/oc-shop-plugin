<template>
    <button :type="type" class="btn" @click="onClicked">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        methods: {
            onClicked(e) {
                this.$emit('click', e)
            },
        },
        props: {
            type: { default: 'button', type: String },
        },
    };
</script>

<style lang="scss" scoped>@import 'core';
    .v-value-input {
        align-items: center;
        cursor: text;
        display: flex;
        margin-bottom: 10px;
    }

    .v-value-input-container {
        flex: 1;
    }

    .oc-icon-bars {
        cursor: move;

        &:before { margin-right: 10px }
        &:hover { color: #999 }
    }

    .oc-icon-trash-o {
        &:before { margin-right: 0 }
        &:hover { color: $red }
    }

    a {
        color: #ccc;
        text-decoration: none;
        transition: color $transition-duration $transition-timing-function;
    }

    input {
        appearance: none;
        border: 0;
        background-color: transparent;
        width: 100%;
    }
</style>

<template>
    <div class="v-value-input form-control" @click="onWrapperClicked">
        <a
            class="oc-icon-bars"
            href="#"
            @click.prevent.stop>
        </a>
        <div class="v-value-input-container">
            <input
                ref="input"
                type="text"
                :value="value.name"
                @input="onInput"
                @keydown="onKeydown">
        </div>
        <a
            class="oc-icon-trash-o"
            href="#"
            @click.prevent.stop="onDeleteClicked">
        </a>
    </div>
</template>

<script>
    export default {
        methods: {
            focus() {
                this.$refs.input.focus();
            },
            onDeleteClicked() {
                this.$emit('delete', this.value);
            },
            onInput(e) {
                this.$emit('input', e, this.value);
            },
            onKeydown(e) {
                this.$emit('keydown', e);
            },
            onWrapperClicked() {
                this.focus();
            },
        },
        props: [
            'value',
        ],
    };
</script>

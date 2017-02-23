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
    }

    .oc-icon-trash-o {
        cursor: pointer;
        text-decoration: none;
        &:hover { color: $red }
    }

    a {
        color: #999;
        font-size: 16px;
        text-decoration: none;
        &:before { margin-right: 0 }
    }

    input {
        appearance: none;
        background-color: transparent;
        border: 0;
        padding: 0 10px;
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
        <div class="v-value-input-container" @click.stop>
            <input
                ref="input"
                type="text"
                :value="value.name"
                @input="onInput"
                @keypress.enter="onEnter"
            />
        </div>
        <a
            class="oc-icon-trash-o"
            href="#"
            @click.prevent.stop="onRemoveClicked">
        </a>
    </div>
</template>

<script>
    export default {
        methods: {
            onEnter(e) {
                this.$emit('enter', e);
            },
            onInput(e) {
                this.$emit('input', e, this.value);
            },
            onRemoveClicked() {
                this.$emit('remove', this.value);
            },
            onWrapperClicked() {
                this.$refs.input.focus();
            },
        },
        props: [
            'value',
        ],
    };
</script>

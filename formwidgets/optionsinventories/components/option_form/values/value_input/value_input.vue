<style lang="scss" scoped>@import 'core';
    .v-value-input {
        align-items: center;
        display: flex;
        margin-bottom: 10px;
    }

    .v-value-input-container {
        flex: 1;
    }

    .oc-icon-bars {
        &:after {
            margin-right: 12px
        }
    }

    input {
        appearance: none;
        border: 0;
        background-color: transparent;
    }
</style>

<template>
    <div class="v-value-input form-control">
        <a class="oc-icon-bars" href="#" @click.prevent></a>
        <div class="v-value-input-container">
            <input
                ref="input"
                type="text"
                :value="value.name"
                @keydown="onKeydown">
        </div>
        <a class="oc-icon-trash-o" href="#" @click.prevent></a>
    </div>
</template>

<script>
    export default {
        methods: {
            focus() {
                this.$refs.input.focus();
            },
            onKeydown(e) {
                this.$emit('keydown', e);
            },
        },
        props: [
            'value',
        ],
    };
</script>

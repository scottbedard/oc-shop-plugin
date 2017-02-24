<style lang="scss" scoped>@import 'core';
    .v-value-input {
        align-items: center;
        cursor: text;
        display: flex;
        margin-bottom: 10px;
        padding-right: 8px;
        transition: opacity $transition-duration $transition-timing-function;
    }

    .is-deleted {
        opacity: 0.5;
    }

    .v-value-input-container {
        flex: 1;
    }

    .oc-icon-bars {
        cursor: move;

        &:before { margin-right: 10px }
        &:hover { color: #999 }
    }

    .is-deleted-icon {
        text-align: center;
        width: 18px;

        &:before { margin-right: 0 }
    }

    .delete-icon {
        &:hover { color: $red }
    }

    .restore-icon {
        &:hover { color: $green }
    }

    a {
        color: #ccc;
        outline: none;
        text-decoration: none;
        transition: color $transition-duration $transition-timing-function;
    }

    input {
        appearance: none;
        background-color: transparent;
        border: 0;
        width: 100%;
    }
</style>

<template>
    <div
        class="v-value-input form-control"
        :class="{ 'is-deleted': value._deleted }"
        @click="onWrapperClicked">
        <a
            class="oc-icon-bars"
            href="#"
            :title="reorderTitle"
            @click.prevent.stop>
        </a>
        <div class="v-value-input-container">
            <input
                ref="input"
                type="text"
                :disabled="value._deleted"
                :value="value.name"
                @input="onInput"
                @keydown="onKeydown">
        </div>
        <a
            href="#"
            class="is-deleted-icon"
            :class="{
                'delete-icon oc-icon-trash-o': ! value._deleted,
                'restore-icon oc-icon-undo': value._deleted,
            }"
            :title="deleteTitle"
            @click.prevent.stop="onDeleteClicked">
        </a>
    </div>
</template>

<script>
    import trans from 'assets/js/filters/trans/trans';

    export default {
        computed: {
            deleteTitle() {
                return this.value._deleted
                    ? trans('bedard.shop.options.form.values_restore_title', this.lang)
                    : trans('bedard.shop.options.form.values_delete_title', this.lang);
            },
            reorderTitle() {
                return trans('bedard.shop.options.form.values_reorder_title', this.lang);
            },
        },
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
            'lang',
            'value',
        ],
    };
</script>

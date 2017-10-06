<style lang="scss" scoped>@import 'core';
    .v-select {
        position: relative;
    }

    select {
        width: 100%;
    }

    .v-select-clear {
        color: #666;
        font-size: 16px;
        position: absolute;
        right: 32px;
        text-decoration: none;
        top: 50%;
        transform: translateY(-50%);

        &:hover {
            color: $red;
        }
    }
</style>

<template>
    <div class="v-select">
        <select ref="select" :name="name">
            <option></option>
            <slot></slot>
        </select>
        <a
            class="v-select-clear"
            href="#"
            v-if="clearable"
            @click.prevent="onClearClicked">
            &times;
        </a>
    </div>
</template>

<script>
    export default {
        beforeDestroy() {
            this.unbind();
        },
        mounted() {
            this.refresh();
        },
        methods: {
            bind() {
                $(this.$refs.select).select2({
                    placeholder: this.placeholder,
                    templateResult(data, container) {
                        if (data.element) {
                            // add any classes from our <option> element so that
                            // we can gray out values that are being deleted.
                            $(container).addClass($(data.element).attr('class'));
                        }

                        return data.text;
                    },
                });

                // @todo: fix a bug where these events are fired twice
                // emit input event so our component works with v-model
                $(this.$refs.select).on('select2:select', e => this.$emit('input', e.target.value));
            },
            onClearClicked() {
                this.$emit('input', null);
                $(this.$refs.select).val('').trigger('change');
            },
            refresh() {
                this.unbind();
                this.bind();
            },
            unbind() {
                let $select = $(this.$refs.select);

                if ($select.hasClass('select2')) {
                    $select.select2('destroy');
                }
            },
        },
        props: {
            clearable: {
                default: false,
                type: Boolean,
            },
            name: {
                default: null,
            },
            placeholder: {
                default: '',
            },
        },
    };
</script>

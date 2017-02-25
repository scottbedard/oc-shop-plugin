<style lang="scss" scoped>@import 'core';
    select {
        width: 100%;
    }
</style>

<template>
    <div>
        <select class="bedard-shop-select" ref="select">
            <option v-if="placeholder.length"></option>
            <slot></slot>
        </select>
    </div>
</template>

<script>
    export default {
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

                // emit an input event so our component works with v-model
                $(this.$refs.select).on('select2:select', e => {
                    this.$emit('input', e.target.value);
                });
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
            placeholder: { default: '' },
        },
    };
</script>

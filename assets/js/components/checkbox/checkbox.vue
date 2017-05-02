<style lang="scss" scoped>@import 'core';;
    a {
        display: inline-flex;
        align-items: center;
        text-decoration: none;

        .v-checkbox-target {
            background-color: #fff;
            border: 1px solid #d1d6d9;
            border-radius: 3px;
            box-shadow: inset 0 1px 0 rgba(209,214,217,0.25), 0 1px 0 rgba(255,255,255,.5);
            color: #ffffff;
            display: inline-block;
            height: 18px;
            width: 18px;
            text-align: center;

            &.is-checked {
                -webkit-font-smoothing: antialiased;
                background-color: #1f99dc;
                border-color: #1f99dc;
                border-width: 2px;
                box-shadow: none;
                content: "\f00c";
                font-family: FontAwesome;
                font-size: 12px;
                font-style: normal;
                font-weight: normal;
                line-height: 17px;
                text-decoration: inherit;
            }
        }

        .v-checkbox-label {
            color: #2a3e51;
            font-size: 13px;
            margin-left: 7px;

            &:empty {
                display: none;
            }
        }
    }
</style>

<template>
        <a
            class="v-checkbox"
            href="#"
            @click.prevent.stop="onClick">
            <span class="v-checkbox-target" :class="{ 'is-checked': value }">
                <i class="icon-check" v-if="value"></i>
            </span>
            <span class="v-checkbox-label">
                <slot></slot>
            </span>
        </a>
</template>

<script>
    export default {
        methods: {
            onClick(e) {
                this.$emit('click', e);
                this.$emit('input', ! this.value);
            },
        },
        props: {
            id: {
                type: String,
            },
            value: {
                type: Boolean,
            },
        },
    };
</script>

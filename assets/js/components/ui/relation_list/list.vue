<style lang="scss" scoped>@import 'assets/scss/utils';
    ul {
        list-style: none;
        margin: 0;
        padding: 0;

        > li {
            align-items: center;
            display: flex;
            height: 60px;
            padding: 0;
            position: relative;

            &.is-deleted {
                $faded-color: #ccc;
                color: $faded-color !important;
                a { color: $faded-color !important }

                &:hover {
                    background-color: transparent;
                    a { background-color: transparent }
                }
            }

            &:hover {
                background-color: $light-blue;
                color: #fff;
                cursor: pointer;

                > a {
                    background-color: $blue;
                    color: #fff;
                }
            }

            > a {
                align-items: center;
                color: #333;
                display: flex;
                font-size: 1.25em;
                height: 60px;
                justify-content: center;
                text-decoration: none;
                min-width: 60px;
                &:before { margin: 0 }
                &:not(:first-of-type) { margin-left: 1px }
                &.oc-icon-bars { cursor: move }
            }

            > div.item {
                padding: 0 10px;
                flex-grow: 1;

                > small {
                    font-size: 0.85em;
                }
            }
        }
    }
</style>

<template>
    <div>
        <slot></slot>
    </div>
</template>

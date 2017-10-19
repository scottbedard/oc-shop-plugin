<style lang="scss" scoped>@import 'core';
    $size: 60px;

    .list-item {
        align-items: center;
        border-radius: 3px;
        cursor: pointer;
        display: flex;
        height: $size;
        overflow: hidden;
    }

    .list-item:hover {
        background-color: $light-blue;
        color: #fff;
    }

    //
    // deleted state
    //
    .list-item.is-deleted {
        color: #bbb !important;

        .square {
            color: #bbb !important;
        }

        .square.delete:hover {
            background-color: transparent;
            color: $green !important;
        }

        &:hover,
        &:hover .square {
            background-color: transparent;
            color: #333;
        }
    }

    //
    // square icons
    //
    .square {
        align-items: center;
        display: flex;
        font-size: 16px;
        height: $size;
        justify-content: center;
        width: $size;
    }

    .list-item:hover .square {
        background-color: $blue;
    }

    //
    // main content
    //
    .main {
        flex: 1;
        padding: 0 12px;
    }

    //
    // actions
    //
    .actions {
        display: flex;

        /deep/ .square {
            margin-left: 1px;
        }
    }
</style>

<template>
    <div class="list-item" @click="onClick">
        <!-- icon -->
        <div class="icon">
            <slot name="icon" />
        </div>

        <!-- main -->
        <div class="main">
            <slot name="main" />
        </div>

        <!-- actions -->
        <div class="actions" @click.stop>
            <slot name="actions" />
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClick(e) {
                this.$emit('click', e);
            },
        },
    };
</script>

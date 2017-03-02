<style lang="scss" scoped>@import 'core';
    small {
        font-size: 0.75em;
    }

    .icon-bars {
        cursor: move;
    }

    .icon-undo {
        &:hover {
            color: $green;
        }
    }
</style>

<style lang="scss">@import 'core';
    // gray out the deleted option values
    .bedard-shop {
        .v-option-list .value-is-deleted { color: #ccc }
        .v-option-list-item:not(.is-deleted):hover span { color: #fff }
    }
</style>

<template>
    <v-list class="v-option-list" @reorder="onReorder" sortable>
        <v-list-item
            class="v-option-list-item"
            v-for="option in options"
            :class="{ 'is-deleted': Boolean(option._deleted) }"
            :key="option.id"
            @click="onOptionClicked(option)">
            <i class="icon-plus" slot="icon"></i>
            <div slot="content">
                <div>{{ option.name }}</div>
                <small v-html="getValues(option)"></small>
            </div>
            <div slot="actions">
                <i class="icon-bars" :title="reorderTitle"></i>
                <i
                    :class="[option._deleted ? 'icon-undo' : 'icon-trash-o']"
                    :title="getDeleteTitle(option)"
                    @click.prevent.stop="onDeleteClicked(option)">
                </i>
            </div>
        </v-list-item>
    </v-list>
</template>

<script>
    import e from 'escape-html';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        components: {
            'v-list': require('../list/list'),
            'v-list-item': require('../list/item/item'),
        },
        computed: {
            reorderTitle() {
                return trans('bedard.shop.options.list.reorder_title', this.lang);
            },
        },
        methods: {
            getDeleteTitle(option) {
                return option._deleted
                    ? trans('bedard.shop.options.list.restore_title', this.lang)
                    : trans('bedard.shop.options.list.delete_title', this.lang);
            },
            getValues(option) {
                return option.values
                    .map(value => {
                        return value._deleted
                            ? `<span class="value-is-deleted">${ e(value.name) }</span>`
                            : e(value.name);
                    })
                    .join(', ');
            },
            onDeleteClicked(option) {
                this.$emit('delete', option);
            },
            onOptionClicked(option) {
                if (! option._deleted) {
                    this.$emit('click', option);
                } else {
                    let text = trans('bedard.shop.options.list.restore_warning', this.lang);
                    $.oc.flashMsg({ text, class: 'warning' });
                }
            },
            onReorder(indexes) {
                this.$emit('reorder', indexes);
            },
        },
        props: [
            'lang',
            'options',
        ],
    };
</script>

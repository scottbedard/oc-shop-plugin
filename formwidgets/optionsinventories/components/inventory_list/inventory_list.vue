<style lang="scss" scoped>@import 'core';

</style>

<template>
    <v-list>
        <v-list-item
            v-for="inventory in inventories"
            :class="{ 'is-deleted': Boolean(inventory._deleted) }"
            :key="inventory.id"
            @click="onInventoryClicked(inventory)">
            <i class="icon-cube" slot="icon"></i>
            <div slot="content">
                <div>{{ getValues(inventory) }}</div>
                <small>{{ getQuantity(inventory) }}</small>
            </div>
            <div slot="actions">
                <i
                    :class="{
                        'icon-undo': inventory._deleted,
                        'icon-trash-o': ! inventory._deleted,
                    }"
                    :title="getDeleteTitle(inventory)"
                    @click.prevent.stop="onDeleteClicked(inventory)">
                </i>
            </div>
        </v-list-item>
    </v-list>
</template>

<script>
    import trans from 'assets/js/filters/trans/trans';

    export default {
        components: {
            'v-list': require('../list/list'),
            'v-list-item': require('../list/item/item'),
        },
        methods: {
            getDeleteTitle(inventory) {
                return inventory._deleted
                    ? trans('bedard.shop.inventories.list.restore_title', this.lang)
                    : trans('bedard.shop.inventories.list.delete_title', this.lang);
            },
            getQuantity(inventory) {
                let translationData = { quantity: inventory.quantity };

                // multiple in stock
                if (inventory.quantity > 1) {
                    return trans('bedard.shop.inventories.list.multiple_in_stock', this.lang, translationData);
                }

                // one or none in stock
                return inventory.quantity
                    ? trans('bedard.shop.inventories.list.single_in_stock', this.lang, translationData)
                    : trans('bedard.shop.inventories.list.out_of_stock', this.lang, translationData);
            },
            getValues(inventory) {
                if (! inventory.values.length) {
                    return trans('bedard.shop.inventories.list.default_name', this.lang);
                }

                return inventory.values
                    .map(value => value.name)
                    .join(', ');
            },
            onDeleteClicked(inventory) {
                this.$emit('delete', inventory);
            },
            onInventoryClicked(inventory) {
                if (! inventory._deleted) {
                    this.$emit('click', inventory);
                } else {
                    let text = trans('bedard.shop.inventories.list.restore_warning', this.lang);
                    $.oc.flashMsg({ text, class: 'warning' });
                }
            },
        },
        props: [
            'inventories',
            'lang',
        ],
    };
</script>

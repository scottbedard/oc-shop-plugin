<style lang="scss" scoped>@import 'core';
    small {
        font-size: 0.75em;
    }
</style>

<template>
    <v-list>
        <v-list-item
            v-for="inventory in inventories"
            :class="{
                'is-deleted': Boolean(inventory._deleted) || hasDeletedRelation(inventory),
            }"
            :key="inventory.id"
            @click="onInventoryClicked(inventory)">
            <i :class="[getInventoryIcon(inventory)]" slot="icon"></i>
            <div slot="content">
                <div>{{ getValues(inventory) }}</div>
                <small>{{ getQuantity(inventory) }}</small>
            </div>
            <div slot="actions">
                <i
                    :class="[inventory._deleted ? 'icon-undo' : 'icon-trash-o']"
                    :title="getDeleteTitle(inventory)"
                    @click.prevent.stop="onDeleteClicked(inventory)">
                </i>
            </div>
        </v-list-item>
    </v-list>
</template>

<script>
    import { inventoryCollsionCheck, inventoryHasDeletedRelation } from '../helpers';
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
            getInventoryIcon(inventory) {
                if (inventory.quantity <= 0) {
                    return 'icon-exclamation-circle';
                } else if (inventory.quantity === 1) {
                    return 'icon-cube';
                } else {
                    return 'icon-cubes';
                }
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

                return inventory.values.slice()
                    .sort((a, b) => a.option_id - b.option_id)
                    .map(value => value.name)
                    .join(', ');
            },
            hasDeletedRelation(inventory) {
                return inventoryHasDeletedRelation(inventory, this.options);
            },
            onDeleteClicked(inventory) {
                // check if the inventory can be restored, and if not throw a warning
                if (inventory._deleted && inventoryCollsionCheck(inventory, this.inventories)) {
                    let text = inventory.values.length
                        ? trans('bedard.shop.inventories.list.restore_collision_values', this.lang)
                        : trans('bedard.shop.inventories.list.restore_collision_default', this.lang);

                    $.oc.flashMsg({ text, class: 'warning' });
                    return;
                }

                // otherwise emit our delete event
                this.$emit('delete', inventory);
            },
            onInventoryClicked(inventory) {
                if (inventory._deleted) {
                    $.oc.flashMsg({
                        class: 'warning',
                        text: trans('bedard.shop.inventories.list.restore_warning', this.lang),
                    });
                } else if (this.hasDeletedRelation(inventory)) {
                    $.oc.flashMsg({
                        class: 'warning',
                        text: trans('bedard.shop.inventories.list.deleted_option_warning', this.lang),
                    });
                } else {
                    this.$emit('click', inventory);
                }
            },
        },
        props: [
            'inventories',
            'lang',
            'options',
        ],
    };
</script>

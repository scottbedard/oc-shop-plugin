<template>
    <div>
        <v-list-item
            v-for="inventory in inventories"
            :class="{ 'is-deleted': inventory._delete || relationshipIsDeleted(inventory) }"
            :data-inventory="inventory._key"
            :key="inventory._key"
            @click="edit(inventory)">
            <div class="square icon" slot="icon">
                <i v-if="inventory.quantity" class="icon-cubes"></i>
                <i v-else class="icon-exclamation-circle"></i>
            </div>
            <div slot="main">
                <div class="primary">
                    {{ selectedOptionValues(inventory) }}
                </div>
                <div class="secondary">
                    <span v-if="hasSku(inventory)" data-sku>
                        {{ inventory.sku }},
                    </span>
                    <span data-quantity>
                        {{ quantitySentence(inventory.quantity) }}
                    </span>
                </div>
            </div>
            <template slot="actions">
                <div
                    class="square delete"
                    data-action="toggle-delete"
                    :title="deleteTitle(inventory)"
                    @click="toggleDelete(inventory)">
                    <i v-if="inventory._delete" class="icon-undo"></i>
                    <i v-else class="icon-trash-o"></i>
                </div>
            </template>
        </v-list-item>
    </div>
</template>

<script>
    import { mapActions, mapGetters, mapState } from 'vuex';
    import { intersection } from 'assets/js/utilities/array';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        components: {
            'v-list-item': require('../../shared/list_item').default,
        },
        computed: {
            ...mapState('inventories', {
                inventories: state => state.inventories,
                lang: state => state.lang,
                options: state => state.options,
            }),
            ...mapGetters('inventories', [
                'allDeletedValueKeys',
                'allValueNames',
            ]),
        },
        methods: {
            ...mapActions('inventories', {
                toggleDelete: 'toggleInventoryDelete',
            }),
            deleteTitle(inventory) {
                return inventory._delete
                    ? trans('bedard.shop.inventories.list.restore_title', this.lang)
                    : trans('bedard.shop.inventories.list.delete_title', this.lang);
            },
            edit(inventory) {
                if (inventory._delete) {
                    $.oc.flashMsg({
                        class: 'error',
                        text: trans('bedard.shop.inventories.list.delete_warning', this.lang),
                    });
                } else if (this.relationshipIsDeleted(inventory)) {
                    $.oc.flashMsg({
                        class: 'error',
                        text: trans('bedard.shop.inventories.list.delete_option_warning', this.lang),
                    });
                } else {
                    this.$store.dispatch('inventories/showEditInventoryForm', inventory);
                }
            },
            hasSku(inventory) {
                return typeof inventory.sku === 'string'
                    ? inventory.sku.trim().length > 0
                    : false;
            },
            quantitySentence(quantity) {
                if (quantity <= 0) {
                    return trans('bedard.shop.inventories.list.out_of_stock', this.lang);
                } else if (quantity === 1) {
                    return trans('bedard.shop.inventories.list.single_in_stock', this.lang, { quantity });
                } else {
                    return trans('bedard.shop.inventories.list.multiple_in_stock', this.lang, { quantity });
                }
            },
            relationshipIsDeleted(inventory) {
                return inventory.valueKeys.length > 0
                    && intersection(inventory.valueKeys, this.allDeletedValueKeys).length > 0;
            },
            selectedOptionValues(inventory) {
                return inventory.valueKeys.length
                    ? inventory.valueKeys.map(key => this.allValueNames[key]).join(', ')
                    : trans('bedard.shop.inventories.list.default_name', this.lang);
            },
        },
    };
</script>

<style lang="scss" scoped>@import 'core';

</style>

<template>
    <div>
        <v-list-item
            v-for="inventory in inventories"
            :class="{ 'is-deleted': inventory._delete }"
            :data-inventory="inventory._key"
            :key="inventory._key"
            @click="edit(inventory)">
            <div class="square icon" slot="icon">
                <i class="icon-cubes"></i>
            </div>
            <div slot="main">
                {{ selectedOptionValues(inventory) }}
            </div>
            <template slot="actions">
                <div class="square delete" :title="deleteTitle(inventory)" @click="toggleDelete(inventory)">
                    <i v-if="inventory._delete" class="icon-undo"></i>
                    <i v-else class="icon-trash-o"></i>
                </div>
            </template>
        </v-list-item>
    </div>
</template>

<script>
    import { mapActions, mapGetters, mapState } from 'vuex';
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
                'allValueNames',
            ]),
        },
        methods: {
            ...mapActions('inventories', {
                edit: 'showEditInventoryForm',
                toggleDelete: 'toggleInventoryDelete',
            }),
            deleteTitle(inventory) {
                return inventory._delete
                    ? trans('bedard.shop.inventories.list.restore_title', this.lang)
                    : trans('bedard.shop.inventories.list.delete_title', this.lang);
            },
            selectedOptionValues(inventory) {
                return inventory.valueKeys.length
                    ? inventory.valueKeys.map(key => this.allValueNames[key]).join(', ')
                    : trans('bedard.shop.inventories.list.default_name', this.lang);
            },
            toggleDelete(inventory) {

            },
        },
    };
</script>

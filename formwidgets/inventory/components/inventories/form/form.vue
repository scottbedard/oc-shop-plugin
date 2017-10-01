<template>
    <v-modal :visible="isVisible" @close="onClose">

        <!-- header -->
        <v-modal-header @close="onClose">
            {{ title | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
        </v-modal-header>

        <!-- body -->
        <v-modal-body>
            Hello from the body
        </v-modal-body>

        <!-- footer -->
        <v-modal-footer>
            actions
        </v-modal-footer>
    </v-modal>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        computed: {
            ...mapState('inventories', {
                context: state => state.inventoryForm.context,
                isVisible: state => state.inventoryForm.isVisible,
                lang: state => state.lang,
            }),
            title() {
                return this.context === 'create'
                    ? 'backend.relation.create_name'
                    : 'backend.relation.update_name';
            },
        },
        methods: {
            ...mapActions('inventories', {
                onClose: 'hideInventoryForm',
            }),
        },
    };
</script>

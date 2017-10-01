<template>
    <div>
        <!-- create button -->
        <v-create-button @click="onCreate" data-action="create">
            {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
        </v-create-button>

        <!-- create form -->
        <v-inventory-form />
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        components: {
            'v-create-button': require('../shared/create_button').default,
            'v-inventory-form': require('./form/form').default,
        },
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
        },
        methods: {
            ...mapActions('inventories', {
                onCreate: 'showCreateInventoryForm',
            }),
        },
    };
</script>

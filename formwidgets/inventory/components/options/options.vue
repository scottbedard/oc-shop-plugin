<template>
    <div>
        <!-- create button -->
        <v-create-button @click="create" data-action="create">
            {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
        </v-create-button>

        <!-- create form -->
        <v-option-form />
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        components: {
            'v-create-button': require('../shared/create_button').default,
            'v-option-form': require('./form/form').default,
        },
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
        },
        methods: {
            ...mapActions('inventories', {
                create: 'showCreateOptionForm',
            }),
        },
    };
</script>

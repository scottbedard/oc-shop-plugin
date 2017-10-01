<template>
    <div>

        <!-- create option -->
        <v-create-button>
            {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
        </v-create-button>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        components: {
            'v-create-button': require('../shared/create_button').default,
        },
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
        },
    };
</script>

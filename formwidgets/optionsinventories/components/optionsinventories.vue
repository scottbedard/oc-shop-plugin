<template>
    <div class="bedard-shop options-inventories">
        <div class="form-group span-left">
            <label>{{ 'bedard.shop.options.plural' | trans(lang) }}</label>
            <v-create @click="onCreateOptionClicked">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.options.singular' }) }}
            </v-create>
        </div>
        <div class="form-group span-right">
            <label>{{ 'bedard.shop.inventories.plural' | trans(lang) }}</label>
            <v-create @click="onCreateInventoryClicked">
                {{ 'backend.relation.create_name' | trans(lang, { name: 'bedard.shop.inventories.singular' }) }}
            </v-create>
        </div>
    </div>
</template>

<script>
    import trans from 'assets/js/filters/trans';

    export default {
        components: {
            'v-create': require('./create/create'),
        },
        filters: { trans },
        methods: {
            onCreateOptionClicked() {
                console.log ('creating option');
            },
            onCreateInventoryClicked() {
                console.log ('creating inventory');
            },
        },
        props: [
            'lang',
        ],
    };
</script>

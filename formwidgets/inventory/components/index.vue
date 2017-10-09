<template>
    <div>
        <div class="form-group span-left">
            <v-options />
        </div>
        <div class="form-group span-right">
            <v-inventories />
        </div>

        <!-- this field passes data back to october -->
        <input type="hidden" :name="fieldName" :value="JSON.stringify(fieldData)" />

        <!-- and this we'll get rid of before releasing -->
        <div class="form-group span-full">
            <pre>{{ fieldData }}</pre>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState } from 'vuex';

    export default {
        components: {
            'v-inventories': require('./inventories/inventories').default,
            'v-options': require('./options/options').default,
        },
        computed: {
            ...mapGetters('inventories', [
                'fieldData',
            ]),
            ...mapState('inventories', [
                'fieldName',
            ]),
        },
    };
</script>

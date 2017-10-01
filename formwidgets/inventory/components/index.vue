<template>
    <div>
        <div class="form-group span-left">
            <v-options />
        </div>
        <div class="form-group span-right">
            <v-inventories />
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'v-inventories': require('./inventories/inventories').default,
            'v-options': require('./options/options').default,
        },
    };
</script>

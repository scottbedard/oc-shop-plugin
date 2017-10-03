<template>
    <div>
        <div v-for="option in options">
            {{ option }}
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState('inventories', [
                'options',
            ]),
        },
    };
</script>

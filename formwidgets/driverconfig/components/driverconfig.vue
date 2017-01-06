<template>
    <div>
        <pre>{{ drivers }}</pre>
    </div>
</template>

<script>
    export default {
        components: {
            'v-driver': require('./driver/driver'),
        },
        props: [
            'drivers',
        ],
    };
</script>

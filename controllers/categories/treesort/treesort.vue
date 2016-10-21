<template>
    <a
        href="#"
        class="btn btn-default oc-icon-sitemap"
        @click.prevent="onClick">
        Reorder Categories
    </a>
</template>

<script>
    export default {
        methods: {
            onClick: function() {
                console.log ('Hello');
            },
        },
    };
</script>

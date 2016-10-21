<template>
    <a href="#" @click.prevent="onClick">
        <slot><!-- a language string is automatically inserted here --></slot>
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

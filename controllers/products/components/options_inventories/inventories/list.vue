<template>
    <div>
        <label>{{ lang.inventories.plural }}</label>

        <v-create-button @click="onCreateClicked">
            {{ lang.inventories.form.create_button }}
        </v-create-button>
    </div>
</template>

<script>
    import CreateButtonComponent from '../_create_button';

    export default {
        components: {
            'v-create-button': CreateButtonComponent,
        },
        methods: {
            onCreateClicked() {
                this.$emit('create');
            },
        },
        props: [
            'lang',
        ],
    };
</script>

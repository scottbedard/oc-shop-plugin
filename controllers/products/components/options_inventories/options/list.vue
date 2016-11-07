<template>
    <div>
        <label>{{ lang.options.plural }}</label>

        <ul>
            <li v-for="option in options">
                {{ option.name }}
            </li>
        </ul>

        <v-create-button @click="onCreateClicked">
            {{ lang.options.form.create_button }}
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
            'label',
            'lang',
            'options',
            'values',
        ],
    };
</script>

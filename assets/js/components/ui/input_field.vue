<template>
    <div class="form-group text-field span-full" :class="{ 'is-required': required }">
        <label v-if="label">{{ label }}</label>
        <input type="text" class="form-control" @input="onInput" :value="value">
    </div>
</template>

<script>
    export default {
        methods: {
            onInput(e) {
                this.$emit('input', e.target.value);
            },
        },
        props: {
            label: { default: null },
            required: { default: false },
            value: { default: null, required: true },
        },
    };
</script>

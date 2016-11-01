<template>
    <div class="form-group dropdown-field span-full" :class="{ 'is-required': required }">
        <label v-if="label">{{ label }}</label>
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
        },
    };
</script>

<template>
    <div>
        <label>{{ lang.options.plural }}</label>

        <v-relation-list>
            <ul v-sortable="{
                animation: 150,
                handle: '.oc-icon-bars',
                onUpdate: onOptionsReordered,
            }">
                <li
                    v-for="option in options"
                    :class="{ 'is-deleted': option.is_deleted }"
                    :key="option.id || option.newId"
                    @click="onOptionClicked(option)">
                    <a href="#" @click.prevent class="oc-icon-plus"></a>
                    <div class="item">
                        <div>{{ option.name }}</div>
                        <small>{{ valueString(option) }}</small>
                    </div>
                    <a href="#" @click.prevent class="oc-icon-bars"></a>
                    <a href="#" @click.prevent.stop="onDeleteOptionClicked(option)" class="oc-icon-trash-o"></a>
                </li>
            </ul>
        </v-relation-list>

        <v-relation-list-create @click="onCreateClicked">
            {{ lang.options.form.create_button }}
        </v-relation-list-create>
    </div>
</template>

<script>
    export default {
        methods: {
            onCreateClicked() {
                this.$emit('create');
            },
            onDeleteOptionClicked(option) {
                this.$emit('delete', option);
                this.$forceUpdate();
            },
            onOptionClicked(option) {
                this.$emit('open', option);
            },
            onOptionsReordered(e) {
                this.$emit('reorder', e);
            },
            valueString(option) {
                return option.values
                    .filter(value => ! value.is_deleted)
                    .map(value => value.name).join(', ');
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

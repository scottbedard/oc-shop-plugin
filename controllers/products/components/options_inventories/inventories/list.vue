<template>
    <div>
        <label>{{ lang.inventories.plural }}</label>

        <ul class="options-inventories-list">
            <li
                v-for="inventory in inventories"
                :class="{ 'is-deleted': inventory.is_deleted }"
                @click="onInventoryClicked(inventory)">
                <div v-show="inventory.is_deleted" class="delete-border"></div>
                <a href="#" @click.prevent class="oc-icon-cube"></a>
                <div class="item">
                    <div>{{ getInventoryValues(inventory) }}</div>
                    <div class="values">5 in stock</div>
                </div>
                <a href="#" @click.prevent.stop="onDeleteInventory(inventory)" class="oc-icon-trash-o"></a>
            </li>
        </ul>

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
            getInventoryValues(inventory) {
                if (Object.keys(inventory.values).length === 0) {
                    return this.lang.inventories.list.default;
                }

                let valueNames = [];
                for (let option of this.options) {
                    if (typeof inventory.values[option.id] !== 'undefined') {
                        valueNames.push(inventory.values[option.id].name);
                    }
                }

                return valueNames.join(', ');
            },
            onCreateClicked() {
                this.$emit('create');
            },
            onDeleteClicked(inventory) {
                this.$emit('delete', inventory);
            },
            onInventoryClicked(inventory) {
                this.$emit('open', inventory);
            },
        },
        props: [
            'lang',
            'inventories',
            'options',
        ],
    };
</script>

<template>
    <div>
        <label>{{ lang.inventories.plural }}</label>

        <v-relation-list>
            <ul>
                <li
                    v-for="inventory in inventories"
                    :class="{ 'is-deleted': inventory.is_deleted }"
                    @click="onInventoryClicked(inventory)">
                    <a href="#" @click.prevent class="oc-icon-cube"></a>
                    <div class="item">
                        <div>{{ getInventoryValues(inventory) }}</div>
                        <small>{{ getQuantityString(inventory) }}</small>
                    </div>
                    <a href="#" @click.prevent.stop="onDeleteClicked(inventory)" class="oc-icon-trash-o"></a>
                </li>
            </ul>
        </v-relation-list>

        <v-relation-list-create @click="onCreateClicked">
            {{ lang.inventories.form.create_button }}
        </v-relation-list-create>
    </div>
</template>

<script>
    export default {
        methods: {
            getInventoryValues(inventory) {
                if (Object.keys(inventory.values).length === 0) {
                    return this.lang.inventories.list.default;
                }

                let valueNames = [];
                for (let option of this.options) {
                    if (inventory.values[option.id]) {
                        valueNames.push(inventory.values[option.id].name);
                    }
                }

                return valueNames.join(', ');
            },
            getQuantityString(inventory) {
                if (inventory.quantity < 1) {
                    return this.lang.inventories.list.out_of_stock;
                }

                let langString = inventory.quantity === 1
                    ? this.lang.inventories.list.in_stock_singular
                    : this.lang.inventories.list.in_stock_plural;

                return langString.replace(':quantity', inventory.quantity);
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

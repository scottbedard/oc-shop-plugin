<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            context() {
                return typeof this.activeInventory.id === 'undefined'
                    ? 'create'
                    : 'update';
            },
            title() {
                return this.context === 'create'
                    ? this.lang.inventories.form.create_title
                    : this.lang.inventories.form.update_title
            },
        },
        props: [
            'activeInventory',
            'inventories',
            'lang',
            'inventories',
        ],
    };
</script>

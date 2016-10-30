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
                return typeof this.activeOption.id === 'undefined'
                    ? 'create'
                    : 'update';
            },
            title() {
                return this.context === 'create'
                    ? this.lang.options.form.create_title
                    : this.lang.options.form.update_title
            },
        },
        props: [
            'activeOption',
            'inventories',
            'lang',
            'options',
        ],
    };
</script>

<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
            Option form goes here
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                {{ lang.form.cancel }}
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                {{ createOrSave }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                option: JSON.parse(JSON.stringify(this.sourceModel)),
            };
        },
        computed: {
            context() {
                return typeof this.sourceModel.id === 'undefined'
                    ? 'create'
                    : 'update';
            },
            createOrSave() {
                return this.context === 'create'
                    ? this.lang.form.create
                    : this.lang.form.save;
            },
            title() {
                return this.context === 'create'
                    ? this.lang.options.form.create_title
                    : this.lang.options.form.update_title
            },
        },
        props: [
            'inventories',
            'lang',
            'options',
            'sourceModel',
        ],
    };
</script>

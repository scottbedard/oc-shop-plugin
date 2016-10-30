<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
            <div class="form-group text-field span-full is-required">
                <label for="bedard-shop-option-name">{{ lang.options.form.name }}</label>
                <input id="bedard-shop-option-name" v-model="option.name" type="text" class="form-control" ref="name">
            </div>
            <div class="form-group text-field span-full">
                <label for="bedard-shop-option-name">{{ lang.options.form.placeholder }}</label>
                <input id="bedard-shop-option-name" v-model="option.placeholder" type="text" class="form-control">
            </div>
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
    import EventChannel from '../_channel';

    export default {
        created() {
            EventChannel.$on('option:opened', this.onOpened);
        },
        data() {
            return {
                option: {
                    name: this.sourceModel.name,
                    placeholder: this.sourceModel.placeholder,
                },
            };
        },
        computed: {
            context() {
                return this.sourceModel.id === null
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
        methods: {
            onOpened() {
                setTimeout(() => this.$refs.name.focus(), 200);
            },
        },
        props: [
            'channel',
            'inventories',
            'lang',
            'options',
            'sourceModel',
        ],
    };
</script>

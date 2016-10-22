<template>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ lang['bedard.shop::lang.categories.list']['reorder_button'] }}</h4>
        </div>
        <div class="modal-body">
            todo...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'categories',
            'lang',
        ],
    }
</script>

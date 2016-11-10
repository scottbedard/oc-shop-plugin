<template>
    <div class="control-popup modal fade" ref="modal" data-bedard-shop="popup-component">
        <div class="modal-dialog">
            <div class="modal-content">
                <slot @dismiss="dismiss"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            dismiss() {
                $(this.$refs.modal).modal('hide');
            },
            show() {
                $(this.$refs.modal).modal();
            },
        },
    };
</script>

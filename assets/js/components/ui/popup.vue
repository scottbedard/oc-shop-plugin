<template>
    <div class="control-popup modal fade" :id="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
    };
</script>

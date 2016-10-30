<template>
    <div class="control-popup modal fade" :id="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
    };
</script>

<template>
    <div class="modal-header">
        <h4 class="modal-title">
            <button type="button" class="close" @click="$emit('close')">&times;</button>
            <slot></slot>
        </h4>
    </div>
</template>

<style lang="scss" scoped>@import 'assets/scss/utils';
    .v-checkbox {
        left: 0;
        padding: 4px 6px;
        position: absolute;
        top: 0;
    }

    i {
        color: #999;
        font-size: 16px;

        &.icon-check-square {
            color: $green;
        }
    }
</style>

<template>
    <div class="v-checkbox">
        <v-loader thickness="3" size="14px" color="#999" v-if="isLoading">Saving...</v-loader>
        <div v-else>
            <i :class="[checkboxClass]" @click.stop="onClicked"></i>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isEnabled: false,
                isLoading: false,
            };
        },
        computed: {
            checkboxClass() {
                return this.isEnabled ? 'icon-check-square' : 'icon-square-o';
            },
        },
        methods: {
            onClicked() {
                this.isEnabled = ! this.isEnabled;
            },
        },
    };
</script>

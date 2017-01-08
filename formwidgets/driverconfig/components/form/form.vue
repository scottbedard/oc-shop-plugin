<template>
    <div>
        <v-popup-header>header</v-popup-header>
        <v-popup-body v-html="form"></v-popup-body>
        <v-popup-footer>
            <v-loader v-if="isLoading">{{ lang.form.saving }}</v-loader>
            <div v-else>
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal">
                    {{ lang.form.cancel }}
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="onApplyClicked">
                    {{ lang.form.apply }}
                </button>
            </div>
        </v-popup-footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isLoading: false,
            };
        },
        computed: {
            form() {
                // grab our october form from the <script> block we stashed it in
                return $('script[data-bedard-shop="driverconfig-form-fields"]').first().html();
            },
        },
        methods: {
            onApplyClicked() {
                console.log ('ok...');
            },
        },
        props: [
            'lang',
        ],
    };
</script>

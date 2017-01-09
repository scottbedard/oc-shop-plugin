<template>
    <div>
        <!-- Form header -->
        <v-popup-header>{{ details.name }}</v-popup-header>

        <!-- Form body -->
        <v-popup-body v-html="form"></v-popup-body>

        <!-- Form controls -->
        <v-popup-footer>
            <v-loader v-if="isLoading">{{ lang.form.saving }}</v-loader>
            <div v-else>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{ lang.form.cancel }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ lang.form.save }}
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
        mounted() {
            this.bindSubmitEventHandler();
        },
        computed: {
            form() {
                // grab our october form from the <script> block we stashed it in
                return $('script[data-bedard-shop="driverconfig-form"]').first().html();
            },
        },
        methods: {
            bindSubmitEventHandler() {
                let $form = $(this.$el).closest('form');

                $form.on('submit', this.onFormSubmitted);
            },
            onFormSubmitted(e) {
                e.preventDefault();
                this.isLoading = true;
                $(e.target).request('onFormSubmitted', {
                    success: data => this.onFormSuccess(data),
                    complete: () => this.isLoading = false,
                });
            },
            onFormSuccess(data) {
                // hide the popup
                $(this.$el).closest('.control-popup').modal('hide');
            },
        },
        props: [
            'details',
            'driver',
            'lang',
        ],
    };
</script>

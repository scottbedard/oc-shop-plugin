import Vue from 'vue';
import trans from 'assets/js/filters/trans/trans';

Vue.use({
    install() {
        Vue.prototype.$flashError = this.$flashError;
    },
    $flashError(message) {
        $.oc.flashMsg({ class: 'error', text: trans(message, this.$store.state.inventories.lang) });
    },
});

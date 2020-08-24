require('./bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('jquery-ujs')
require('bootstrap4-toggle')
window.Vue = require('vue');
window.axios = require('axios');
Vue.mixin(require('./trans'))


Vue.component('v-input', require('./components/Form/Input').default)
Vue.component('v-date-picker', require('./components/Form/DatePicker').default)
Vue.component('v-text-area', require('./components/Form/TextArea').default)
Vue.component('v-checkbox', require('./components/Form/Checkbox').default)
Vue.component('v-button', require('./components/Button/Button').default)
Vue.component('v-dropdown', require('./components/Form/Dropdown').default)
Vue.component('select2', require('./components/Form/Select2').default)

Vue.component('v-add-item', require('./components/Purchasing/AddItem').default)
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

const app = new Vue({
  el: '#app',
});

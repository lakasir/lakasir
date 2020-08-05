require('./bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
window.Vue = require('vue');
window.axios = require('axios');


Vue.component('v-input', require('./components/Form/Input').default)
Vue.component('v-text-area', require('./components/Form/TextArea').default)
Vue.component('v-checkbox', require('./components/Form/Checkbox').default)
Vue.component('v-button', require('./components/Button/Button').default)
Vue.component('v-dropdown', require('./components/Form/Dropdown').default)
Vue.component('select2', require('./components/Form/Select2').default)

const app = new Vue({
  el: '#app',
});

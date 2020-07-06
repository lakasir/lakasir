require('./bootstrap');
window.Vue = require('vue');
window.axios = require('axios');
Vue.component('v-input', require('./components/Form/Input').default)
Vue.component('v-dropdown', require('./components/Form/Dropdown').default)

const app = new Vue({
  el: '#app',
});

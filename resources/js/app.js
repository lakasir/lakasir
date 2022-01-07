require('./bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('jquery-ujs')
require('bootstrap4-toggle')
window.Vue = require('vue');
window.axios = require('axios');
const _ = require('lodash');
import helpers from './helpers';
import route from 'ziggy';
import {Ziggy} from './ziggy.js';
import store from './store';
import Notifications from 'vue-notification'

Vue.mixin({
  methods: {
    route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    priceFormat: (number) => {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      })
      return formatter.format(number)
    }
  },
});
window.helpers = helpers

Vue.mixin(require('./trans'))

Vue.use(Notifications)

Vue.component('create-purchasing', require('./Form/CreatePurchasing/Index').default)

Vue.component('v-add-item', require('./components/Purchasing/AddItem.vue').default)

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
  store
});

$(document).ready(() => {
  $(".select2").select2({
    width: "100%",
  });
});

require('./bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('jquery-ujs')
require('bootstrap4-toggle')
window.Vue = require('vue');
window.axios = require('axios');
const _ = require('lodash');
import route from 'ziggy';
import { Ziggy } from './ziggy.js';

Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    },
});
Vue.mixin(require('./trans'))

Vue.component('v-input', require('./components/Form/Input').default)
Vue.component('v-date-picker', require('./components/Form/DatePicker').default)
Vue.component('v-text-area', require('./components/Form/TextArea').default)
Vue.component('v-checkbox', require('./components/Form/Checkbox').default)
Vue.component('v-button', require('./components/Button/Button').default)
Vue.component('v-button-upload', require('./components/Button/UploadFile').default)
Vue.component('v-dropdown', require('./components/Form/Dropdown').default)
Vue.component('select2', require('./components/Form/Select2').default)
Vue.component('v-select', require('./components/Form/Select').default)

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

Vue.component('v-select2', {
  props: ['options', 'value', 'url'],
  template: '#select2-template',
  data: function() {
    return {
      ajaxOptions: {
        url: this.url,
        dataType: 'Json',
        delay: 250,
        tags: true,
        data: function(params) {
          return {
            term: params.term,
            page: params.page
          }
        },
        processResults: function(data, params) {
          console.log(data);
          return {
            results: data
          }
        },
        cache: true
      }
    }
  },
  mounted: function() {
    let vm = this;
    $(this.$el)
      .select2({
        placeholder: "Click to see Options",
        ajax: this.ajaxOptions
      })
  },
  watch: {
    url: function(value) {
      this.ajaxOptions.url = this.url;
      $(this.$el).select2({ ajax: this.ajaxOptions })
    }
  }
})

const app = new Vue({
  el: '#app',
});

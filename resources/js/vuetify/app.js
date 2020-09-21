const _ = require('lodash');
window.Vue = require('vue');
Vue.mixin(require('./../trans'));
const mixin = {
  methods: {
    priceFormat: (number) => {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      })
      return formatter.format(number)
    }
  }
}

Vue.mixin(mixin)

import vuetify from './config';
import App from './App';
import VueRouter from 'vue-router';
import 'es6-promise/auto'
import store from './store';

const route = require('./router').default;

const router = new VueRouter({
  routes: route
})
router.beforeEach((to, from, next) => {
  const authenticate = store.getters["auth/isAuthenticated"];
  // const checkToken = store.dispatch('auth/checkToken');
  // checkToken
  //   .then(res => {
  //     console.log(res);
  //   })
  //   .catch(err => {
  //     console.log(err);
  //   })
  // console.log(checkToken);
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!authenticate) {
      next({
        path: '/login',
      })
    } else {
      next()
    }
  } else {
    next() // make sure to always call next()!
  }
})

Vue.use(VueRouter)

const app = new Vue({
  vuetify,
  render: h => h(App),
  router,
  store,
  el: '#app',
});

const _ = require('lodash');
window.Vue = require('vue');
Vue.mixin(require('./../trans'));

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

window.Vue = require('vue');

import vuetify from './config'
import App from './App'
import VueRouter from 'vue-router'

const route = require('./router').default;

const router = new VueRouter({
  routes: route
})
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!auth.loggedIn()) {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
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
  el: '#app',
});

import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth';
import items from './modules/items';
import carts from './modules/carts';
import activity from './modules/activity';
import profiles from './modules/profiles';
import paymentMethods from './modules/paymentMethods';

Vue.use(Vuex)

const state = () => ({
  loading: false,
  snackbar: false,
  message: ''
})

const mutations = {
  setLoading: (state) => {
    state.loading = true
  },
  setFlash: (state, { message }) => {
    state.snackbar = true
    state.message = message
  }
}

export default new Vuex.Store({
  modules: {
    auth,
    items,
    carts,
    paymentMethods,
    activity,
    profiles
  },
  state,
  mutations
})

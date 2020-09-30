import Vue from 'vue'
import Vuex from 'vuex'
import items from './modules/items';
import cart from './modules/cart';
import paymentMethods from './modules/paymentMethods';
import customers from './modules/customers';

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
  setFlash: (state, {message}) => {
    state.snackbar = true
    state.message = message
  }
}

export default new Vuex.Store({
  modules: {
    items,
    cart,
    paymentMethods,
    customers
  },
  state,
  mutations
})


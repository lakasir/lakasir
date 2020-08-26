import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth';
import items from './modules/items';

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    items
  }
})

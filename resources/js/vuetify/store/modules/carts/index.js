import trans from './../../../../trans';

const state = () => ({
  cartItems: [],
  loading: false,
  cartCount: 0,
  refund: 0,
  money: 0,
  allTotalPrice: 0
});

const actions = {
  addToCart({ commit }, item) {
    commit('setFlash',
      {
        message: trans.methods.__('app.sellings.cart.added')
      },
      {
        root: true
      }
    )
    commit({
      type: 'addCartItmes',
      item: item
    })
  },
  addQty({ commit }, item) {
    commit('setFlash',
      {
        message: trans.methods.__('app.sellings.cart.qty.added')
      },
      {
        root: true
      }
    )
    commit({
      type: 'addCartItmes',
      item: item
    })
  },
  reduceQty({ commit }, item) {
    commit('setFlash',
      {
        message: trans.methods.__('app.sellings.cart.qty.reduced')
      },
      {
        root: true
      }
    )
    commit({
      type: 'reduceCartItmes',
      item: item
    })
  },
  calculateRefund({ commit, getters }, money) {
    let refund = money - getters.getTotalPrice;
    commit({
      type: 'setRefund',
      refund: refund
    })
  },
  reset({ state }) {
    state.refund = 0
    state.cartItems = []
    state.cartCount = 0
    state.money = 0
    state.allTotalPrice = 0
  },
  removeItem({ commit, state }, { id }) {
    let items = state.cartItems.filter((item) => item.id != id)
    commit({
      type: 'setItems',
      items: items
    })
  }
}

const getters = {
  getTotalPrice: state => {
    let totalPrice = 0
    state.cartItems.map((data) => {
      totalPrice += data.totalPrice
    })

    return totalPrice;
  },
  getRefund: state => {
    return state.refund
  }
}


const mutations = {
  setItems: (state, { items }) => {
    state.cartItems = items;
    state.cartCount--;
  },
  addCartItmes: (state, payload) => {
    let found = state.cartItems.find(product => product.id == payload.item.id);
    if (found) {
      if (found.qty < found.maxStock) {
        found.qty ++;
        found.totalPrice = found.qty * found.sellingPrice;
      }
    } else {
      if (payload.item.maxStock > 0) {
        state.cartItems.push(payload.item);
        state.cartCount++;
      }
    }
  },
  reduceCartItmes: (state, payload) => {
    let item = state.cartItems.find(product => product.id == payload.item.id);
    if (item.qty > 1) {
      item.qty--;
      item.totalPrice = item.qty *  item.sellingPrice;
    }
  },
  setRefund: (state, payload) => {
    state.refund = payload.refund
  }
}

export default {
  namespaced: true,
  actions,
  mutations,
  getters,
  state
}

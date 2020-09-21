import api from './../api'

const state = () => ({
  paymentMethods: [],
  loading: false
});

const actions = {
  getPaymentMethods({commit}, req) {
    commit({
      type: 'paymentMethodRequest'
    })
    return new Promise((resolve, reject) => {
      api.payment_method_list(req)
        .then(res => {
          commit({
            type: 'paymentMethodRequestSuccess',
            data: res.data.payload.map(data => {
              return {value: data.id, text: data.name}
            })
          })
        })
        .catch(res => {
          commit({
            type: 'paymentMethodRequestError'
          })
        })
    })
  }
};

const mutations = {
  paymentMethodRequest: state => {
    state.loading = true
  },
  paymentMethodRequestSuccess: (state, payload) => {
    state.paymentMethods = payload.data
    state.loading = false
  },
  paymentMethodRequestError: state => {
    state.loading = false
  }
}


export default {
  actions,
  state,
  mutations
}

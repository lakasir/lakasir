
import api from './../api'

const state = () => ({
  customers: [],
  loading: false
});

const actions = {
  getCustomers({commit}, req) {
    commit({
      type: 'customerRequest'
    })
    return new Promise((resolve, reject) => {
      api.customer_list(req)
        .then(res => {
          commit({
            type: 'customerRequestSuccess',
            data: res.data.payload.map(data => {
              return {value: data.id, text: data.name}
            })
          })
        })
        .catch(res => {
          commit({
            type: 'customerRequestError'
          })
        })
    })
  }
};

const mutations = {
  customerRequest: state => {
    state.loading = true
  },
  customerRequestSuccess: (state, payload) => {
    state.customers = payload.data
    state.loading = false
  },
  customerRequestError: state => {
    state.loading = false
  }
}


export default {
  actions,
  state,
  mutations
}


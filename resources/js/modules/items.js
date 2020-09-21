import api from './../api'
import trans from './../trans';

const state = () => ({
  items: [],
  loading: false,
  errors: []
});

const actions = {
  getItem({commit}, req) {
    return new Promise((resolve, reject) => {
      commit({
        type: 'itemRequest'
      })
      api.list_item(req)
        .then(res => {
          commit({
            type: 'itemRequestSucces',
            data: res.data.payload
          })
          resolve(res.data.payload)
        })
        .catch(err => {
          commit({
            type: 'itemRequestError'
          })
          resolve(err)
        })
    })
  },
  submit({commit}, req) {
    commit({
      type: 'itemRequest'
    })
    return new Promise((resolve, reject) => {
      api.submit_item(req)
        .then(res => {
          commit('setFlash',
            {
              message: trans.methods.__('app.sellings.message.created')
            },
            {
              root: true
            }
          )
          resolve(res.data.payload)
        })
        .catch(err => {
          let errors = JSON.parse(err.request.response)
          commit({
            type: 'itemRequestError',
            errors: errors.errors
          })
          reject(err)
        })
    })
  }
};

const mutations = {
  itemRequest: (state) => {
    state.loading = true
  },
  itemRequestError: (state, payload) => {
    state.loading = false
    state.errors = payload.errors
  },
  itemRequestSucces: (state, payload) => {
    state.items = payload.data
    state.loading = false
  }
}

export default {
  actions,
  state,
  mutations
}

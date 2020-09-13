import api from './../../../api'

const state = () => ({
  token: localStorage.getItem('bearer-token') || '',
  status: '',
  errors: []
})

const actions = {
  loginSubmit ({ commit }, req) {
    return new Promise((resolve, reject) => {
      commit('authRequest')
      api.login(req)
        .then(res => {
          const token = res.data.payload.token;
          localStorage.setItem('bearer-token', token);
          resolve(res.data)
        })
        .catch(err => {
          let errors = JSON.parse(err.request.response)
          commit({
            type: 'authError',
            errors: errors.errors ?? errors.message
          })
          reject(err)
        })
    })
  }
}

const getters = {
  isAuthenticated: state => !!state.token,
  authStatus: state => state.status
}

const mutations = {
  authRequest: (state) => {
    state.status = 'loading'
  },
  authSuccess: (state, token) => {
    state.status = 'success'
    state.token = token
  },
  authError: (state, payload) => {
    state.status = 'error'
    state.errors = payload.errors
  },
}

export default {
  namespaced: true,
  state,
  actions,
  getters,
  mutations
}

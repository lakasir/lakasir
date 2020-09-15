import api from './../../../api'
import trans from './../../../../trans';

const state = () => ({
  profile: {}
});

const actions = {
  getProfile({ commit }) {
    return new Promise(( resolve, reject ) => {
      api.profile()
        .then(res => {
          commit({
            type: 'setProfile',
            profile: res.data.payload
          })
          resolve(res)
        })
        .catch(err => {
          reject(err)
        })
    })
  }
};

const mutations = {
  setProfile: ( state, { profile } ) => {
    state.profile = profile
  }
};

export default {
  namespaced: true,
  actions,
  mutations,
  state
}

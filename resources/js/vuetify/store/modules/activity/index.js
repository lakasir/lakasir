import api from './../../../api'

const state = () => ({
  activities: []
});

const actions = {
  getActivity({ commit }, request) {
    return new Promise((resolve, reject) => {
      api.activity(request)
        .then(res => {
          commit({
            type: 'activityRequestSucces',
            data: res.data.payload
          })
          resolve(res.data.payload)
        })
        .catch(err => {
          reject(err)
        })
    })
  }
};

const mutations = {
  activityRequestSucces: ( state, { data } ) => {
    state.activities = data
  }
}

export default {
  namespaced: true,
  actions,
  mutations,
  state
}

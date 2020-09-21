import paymentMethods from './../../../modules/paymentMethods';

export default {
  namespaced: true,
  actions: paymentMethods.actions,
  mutations: paymentMethods.mutations,
  state: paymentMethods.state
}

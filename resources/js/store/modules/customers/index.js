import customers from './../../../modules/customers';

export default {
  namespaced: true,
  actions: customers.actions,
  mutations: customers.mutations,
  state: customers.state
}

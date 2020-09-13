import axios from 'axios'
import helpers from './../helpers.js';

let token = helpers.auth.token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const api = {
  login: (request) => axios.post(route('api.auth.login'), request),
  list_item: (request) => axios.get(route('api.selling.index', request)),
  submit_item: (request) => axios.post(route('api.selling.store'), request),
  payment_method: (request) => axios.get(route('api.payment_method.index', request)),
}

export default api

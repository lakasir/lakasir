import axios from 'axios'
import route from './../helpers'

let token = localStorage.getItem('token')
axios.defaults.headers.common['Authorization'] = `${token}`
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const api = {
  list_item: (request) => axios.get(route.route('api.selling.index', request)),
  payment_method_list: (request) => axios.get(route.route('payment_method.index', request)),
  submit_item: (request) => axios.post(route.route('api.selling.store'), request),
}

export default api


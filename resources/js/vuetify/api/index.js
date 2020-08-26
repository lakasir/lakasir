import axios from 'axios'
const api = {
  login: (request) => axios.post(route('api.auth.login'), request),
  list_item: (request) => axios.get(route('api.selling.index',{
    search: request.search
  })),
}

export default api

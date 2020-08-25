import axios from 'axios'
const api = {
  login: (request) => axios.post(route('api.auth.login'), request)
}

export default api

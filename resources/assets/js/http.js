import axios from 'axios'

const http = axios.create({
  baseURL: window.baseUrl,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }
})

http.interceptors.response.use(function (response) {
  return response
}, function (error) {
  if (error.response.status === 401) {
    window.location = '/login'
    return
  }
  return Promise.reject(error)
})

export default http

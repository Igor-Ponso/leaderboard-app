import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

export const baseApiUrl = 'http://localhost:5000'

export const api = axios.create({
  baseURL: baseApiUrl,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

let csrfLoaded = false

const getCsrfToken = () => {
  const cookies = document.cookie.split('; ')
  const token = cookies.find(c => c.startsWith('XSRF-TOKEN='))
  return token ? decodeURIComponent(token.split('=')[1]) : null
}

api.interceptors.request.use(
  async (config) => {
    const needsCsrf = ['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase() || '')

    if (needsCsrf && !csrfLoaded) {
      try {
        await api.get('/sanctum/csrf-cookie')
        csrfLoaded = true
      } catch (err) {
        useToast().error('Failed to load CSRF token')
        return Promise.reject(err)
      }
    }

    const csrf = getCsrfToken()
    if (csrf) {
      config.headers['X-XSRF-TOKEN'] = csrf
    }

    // Token
    const auth = useAuthStore()
    if (auth.token) {
      config.headers['Authorization'] = `Bearer ${auth.token}`
    }

    return config
  },
  (error) => {
    useToast().error('Request error: ' + error.message)
    return Promise.reject(error)
  }
)

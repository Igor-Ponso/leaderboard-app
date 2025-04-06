import { ref } from 'vue'
import { defineStore } from 'pinia'
import { api } from '@/api/config'
import { endpoints } from '@/api/paths'
import { useToast } from 'vue-toastification'

const STORAGE_KEY = 'leaderboard_user'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref<string | null>(null)
  const toast = useToast()

  const persist = () => {
    localStorage.setItem(
      STORAGE_KEY,
      JSON.stringify({ user: user.value, token: token.value }),
    )
  }

  const restore = () => {
    const saved = localStorage.getItem(STORAGE_KEY)
    if (saved) {
      try {
        const parsed = JSON.parse(saved)
        user.value = parsed.user || null
        token.value = parsed.token || null
      } catch {
        localStorage.removeItem(STORAGE_KEY)
      }
    }
  }

  const clear = () => {
    localStorage.removeItem(STORAGE_KEY)
  }

  const register = async (payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
  }) => {
    try {
      const response = await api.post(endpoints.auth.register, payload)
      toast.success('Account created successfully!')
      return response.data
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Error creating account.')
      throw error
    }
  }

  const login = async (payload: { email: string; password: string }) => {
    try {
      const response = await api.post(endpoints.auth.login, payload)
      token.value = response.data.token
      user.value = response.data.user || null
      persist()
      return response.data
    } catch (error: any) {
      toast.error(
        error.response?.data?.message ||
          'Error logging in. Please check your credentials.',
      )
      throw error
    }
  }

  const logout = async () => {
    try {
      await api.post(endpoints.auth.logout)
      user.value = null
      token.value = null
      clear()
      toast.info('Logged out successfully.')
    } catch (error: any) {
      toast.error('Error logging out.')
    }
  }

  // Auto restore on store load
  restore()

  return {
    user,
    token,
    register,
    login,
    logout,
    restore,
  }
})
